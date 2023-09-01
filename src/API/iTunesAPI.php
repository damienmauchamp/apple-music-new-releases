<?php

namespace API;

use GuzzleHttp\Exception\GuzzleException;

class iTunesAPI extends AbstractAPI {

	protected string $name = 'iTunes API';
	protected string $url = 'https://itunes.apple.com/';
	protected string $path = '';
	protected string $country;
	protected bool $developer = false;
	protected bool $scrapped = true;

	public function __construct(string $country = '',
								bool   $renew = false) {
		parent::__construct($renew);
		$this->setCountry($country ?: $this->getDefaultCountry());
	}


	public function setCountry(string $country): string {
		$this->country = $country;
		return $this->country;
	}

	public function getCountry(): string {
		return $this->country ?: $this->getDefaultCountry();
	}

	public function getDefaultCountry(): string {
		return $this->app->env('ITUNES_DEFAULT_COUNTRY', 'us');
	}

	protected function setUrl(&$uri, array $parameters = []): string {
		$uri .= $parameters ? sprintf('?%s', http_build_query($parameters)) : '';
		return $uri;
	}

	protected function get($uri, array $parameters = [], array $options = [], bool $retrying = false): APIResponse {
		$response = parent::get($uri, $parameters, $options, $retrying);
		$response->setParser(APIResponse::ITUNES_API_PARSER);
		return $response;
	}

	/**
	 * @throws GuzzleException 400 error
	 */
	public function test(): APIResponse {
		return $this->get('/lookup', [
			'id' => '1443426453',
			'country' => $this->getCountry(),
//			'entity' => 'album',
//			'limit' => 5,
//			'sort' =>
		]);
	}

}