<?php

namespace AppleMusic;

class AppleMusicAPI {

	private $url = 'https://api.music.apple.com/v1';

	private $developer_token;
	private $music_user_token;

	public function __construct(?string $developer_token = null, string $music_user_token = '') {
		$this->developer_token = $developer_token ?: getenv('DEVELOPER_TOKEN');
		$this->music_user_token = $music_user_token;
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

	private function request(string $method, string $endpoint, array $params = []): array {
		$curl = curl_init();
		$options = [
			CURLOPT_URL => "{$this->url}{$endpoint}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
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

		return [
			'status' => $infos['http_code'] ?? null,
			'infos' => $infos,
			'body' => json_decode($response, true),
			'error' => $err,
		];
	}
}