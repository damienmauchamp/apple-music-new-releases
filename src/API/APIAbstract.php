<?php

namespace API;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use src\AbstractElement;

class APIAbstract extends AbstractElement {

	protected string $name = 'API';
	protected string $url = 'https://api.music.apple.com/';
	protected string $path = 'v1/';
	private string $storefront;
	//
	private string $developer_token = '';
	private int $token_expiracy = 3600; // 3600;
	//
	private ?int $token_expiracy_status = 401;
	private ?int $token_expiracy_status_try = 0;
	private ?int $token_expiracy_status_max_try = 2;
	//
	private Client $client;

	//

	public function __construct(bool $renew = false) {
		parent::__construct();
		$this->init($renew);
	}

	public function init(bool $renew = false) {
		$this->initDeveloperToken($renew);
		$this->initClient();
	}

	/**
	 * @throws Exception Too many failures
	 */
	public function prepare(bool $retrying = false) {
		if(!$retrying) {
			$this->token_expiracy_status_try = 0;
			return;
		}

		$this->token_expiracy_status_try++;

		if($this->token_expiracy_status_try > $this->token_expiracy_status_max_try) {
			// todo : custom exception
			throw new Exception('Too many failures');
		}
	}

	public function headers(): array {
		return [];
	}

	protected function setUrl(&$uri, array $parameters = []): string {
		$uri = preg_replace('/\/+/', '/', sprintf('%s/%s%s', $this->path, $uri,
			$parameters ? sprintf('?%s', http_build_query($parameters)) : ''));
		return $uri;
	}

	protected function initClient(?string $token = null): self {
		$options = [
			'base_uri' => $this->url,
//			'Accept' => 'application/json',
			'verify' => (bool) $this->app->env('SSL_VERIRY', false),
			'headers' => $this->headers(),
		];

		$token = ($token ?? $this->developer_token) ?: '';
		if($token) {
			$options['headers']['Authorization'] = "Bearer {$token}";
		}
		$this->client = new Client($options);
		return $this;
	}

	protected function tokenExpired(string $token): bool {
		$current_token = $this->developer_token;
		$this->initClient($token);

		$expired = false;
		try {
			$response = $this->test();
		} catch(GuzzleException $e) {
			$expired = true;
		}
		$this->developer_token = $current_token;
		return $expired;
	}

	protected function initDeveloperToken(bool $renew = false): void {

		// .env DEVELOPER_TOKEN
		if($this->app->env('DEVELOPER_TOKEN')) {
			$this->developer_token = $this->app->env('DEVELOPER_TOKEN');
			return;
		}

		// fetching the first not expired token
		$result = $this->app->manager()->run('SELECT token FROM token WHERE expiracy > now() ORDER BY expiracy');
		if(!$renew && $result->rowCount()) {
			foreach($result->fetch() as $token) {
				if($this->tokenExpired($token)) {
					$this->app->manager()->update('token', ['expiracy' => date('Y-m-d 00:00:00')], ['token' => $token]);
					continue;
				}
				$this->developer_token = $token;
			}
		}

		if(!$this->developer_token) {
			$this->developer_token = $this->app->getDeveloperToken($this->token_expiracy);
			$expiracy = (new \DateTime())->add(new \DateInterval("PT{$this->token_expiracy}S"));

			// saving new token
			$this->app->manager()->insert('token', [
				'token' => $this->developer_token,
				'expiracy' => $expiracy->format('Y-m-d H:i:s'),
				'notes' => '',
			]);
		}
	}

	/**
	 * @throws GuzzleException 400 error
	 * @throws Exception Too many failures
	 */
	protected function get($uri, array $parameters = [], array $options = [], bool $retrying = false): APIResponse {

		$this->prepare($retrying);
		$this->setUrl($uri, $parameters);

		try {
//			return $this->client->get($uri, $this->options);
			$request = new APIRequest($this->client, 'GET', $uri, $parameters, $options, $retrying);
			return $request->run();
		} catch(GuzzleException $e) {
			if($this->token_expiracy_status && $this->token_expiracy_status === $e->getCode()) {
				// retry
				$this->init(true);
				return $this->get($uri, $parameters, $options, true);
			}
			throw $e;
		}
	}

	/**
	 * @throws GuzzleException 400 error
	 */
	public function test(): APIResponse {
		return $this->get('/test');
	}

//	public function parse(ResponseInterface $response) {
//		return json_decode($response->getBody()->getContents(), true);
//	}

}