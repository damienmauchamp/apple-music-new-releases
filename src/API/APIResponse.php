<?php

namespace API;

use Psr\Http\Message\ResponseInterface;

class APIResponse {

	private ResponseInterface $response;

	public function __construct(ResponseInterface $response) {
		$this->response = $response;
	}

	public function getStatusCode(): int {
		return $this->response->getStatusCode();
	}

	public function getData(): array {
		return json_decode($this->response->getBody()->getContents(), true) ?: [];
	}

}