<?php

namespace AppleMusic;

use GuzzleHttp\Client;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class AppleMusicAPI {

	private string $url = 'https://api.music.apple.com/v1';
	private $storefront;

	private $developer_token;
	private string $music_user_token;

	const LOG_FILE = DEFAULT_PATH.'/logs/apple_music_api.log';
	private Logger $logger;

//	private Client $client;

	public function __construct(?string $developer_token = null, string $music_user_token = '') {
		$this->developer_token = $developer_token ?: getenv('DEVELOPER_TOKEN') ?: ($_ENV['DEVELOPER_TOKEN'] ?? '');
		$this->music_user_token = $music_user_token;

		$this->storefront = getenv('STOREFRONT') ?: ($_ENV['STOREFRONT'] ?? '') ?: 'us';

		$this->logger = new Logger('AppleMusicAPI');
		$this->logger->pushHandler(new RotatingFileHandler(self::LOG_FILE, 7, Logger::API));

//		$this->client = new Client();
	}

	public function get(string $endpoint, array $params = []): array {
		return $this->request('GET', $endpoint.($params ? '?'.http_build_query($params) : ''));
	}

	public function post(string $endpoint, array $params = []): array {
		return $this->request('POST', $endpoint, $params);
	}

	public function setMusicUserToken($token): void {
		$this->music_user_token = $token;
	}

	public function getStorefront(): string {
		return $this->storefront;
	}

	private function logRequest(string $method, string $endpoint, array $params = [], array $data = []): void {
		$this->logger->info("Request {$method} {$endpoint}".json_encode([
				'method' => $method,
				'endpoint' => $endpoint,
				'params' => $params,
				'data' => $data,
			]));
	}

	private function request(string $method, string $endpoint, array $params = []): array {

		//
//		defaults' => [ 'verify' => false

		$curl = curl_init();

		$url = "{$this->url}{$endpoint}";
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		];

		if($this->developer_token) {
			$options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer {$this->developer_token}";
		}
		if(strstr($endpoint, '/me/')) {
			$options[CURLOPT_HTTPHEADER][] = "Music-User-Token: {$this->music_user_token}";
		}

		if($method == 'POST') {
			$options[CURLOPT_POSTFIELDS] = json_encode($params);
		}


		curl_setopt_array($curl, $options);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);

		$this->logRequest($method, $endpoint, [
			'url' => $url,
			'options' => $options,
			'status' => $infos['http_code'] ?? null,
			'infos' => $infos,
			'body' => $response,
			'error' => $err,
		]);

		return [
			'status' => $infos['http_code'] ?? null,
			'infos' => $infos,
			'body' => json_decode($response, true),
			'error' => $err,
		];
	}
}