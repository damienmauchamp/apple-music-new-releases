<?php

namespace API;

use GuzzleHttp\Exception\GuzzleException;

class iTunesScrappedAPI extends iTunesAPI {

	protected string $name = 'iTunes scrapped API';

	// scrapped = "https://itunes.apple.com/{$this->country}/artist/{$artistUrlName}/{$this->id}";


	protected function setUrl(&$uri, array $parameters = []): string {
		$uri = preg_replace('/\/+/', '/', sprintf('%s/%s', $this->getCountry(), $uri));
		return $uri;
	}

	/**
	 * @throws GuzzleException 400 error
	 */
	public function test(): APIResponse {
		return $this->get('/album/or-noir/1443426453', [
			'timestamp' => time(),
		]);
	}
}