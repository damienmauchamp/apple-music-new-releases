<?php

namespace API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class APIRequest {

	private Client $client;
	private string $methode;
	private string $uri;
	private array $parameters;
	private array $options;
	private bool $retrying;
	private int $page = 1;

	public function __construct(Client $client, string $methode, string $uri, array $parameters, array $options, bool $retrying) {
		$this->client = $client;
		$this->methode = $methode;
		$this->uri = $uri;
		$this->parameters = $parameters;
		$this->options = $options;
		$this->retrying = $retrying;
	}

	/**
	 * @throws GuzzleException
	 */
	public function run(): APIResponse {
		switch($this->methode) {
			case 'GET':
				$request = $this->client->get($this->uri, $this->options);
				break;
			case 'POST':
				$request = $this->client->post($this->uri, $this->options);
				break;
			default:
				$request = $this->client->request($this->methode, $this->uri, $this->options);
		}

		return new APIResponse($request);

//		$this->last_request = $request;
//		$this->last_response = new APIResponse($request);

//		return $this->last_response;
	}

}