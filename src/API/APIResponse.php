<?php

namespace API;

use Psr\Http\Message\ResponseInterface;
use Sunra\PhpSimple\HtmlDomParser;

class APIResponse {

	private ResponseInterface $response;
	private bool $scrapped;
	private ?int $parser = null;
	private string $content;

	const ITUNES_API_PARSER = 1;

	public function __construct(ResponseInterface $response,
								bool              $scrapped = false) {
		$this->response = $response;
		$this->content = $this->response->getBody()->getContents();
		$this->scrapped = $scrapped;
	}

	public function getStatusCode(): int {
		return $this->response->getStatusCode();
	}

	public function getContents(): string {
		return $this->content;
	}

	public function getData(): array {
		$string = $this->getContents();

		if($this->scrapped) {
			return [
				'body' => $this->parse(),
				'raw' => trim($string, "\ \t\n\r\0\x0B"),
			];
		}

		if($json = json_decode($string, true)) {
			return $json;
		}
		return json_decode(trim($string, "\ \t\n\r\0\x0B"), true) ?: [];
	}

	/**
	 * @param int|null $parser
	 * @return APIResponse
	 */
	public function setParser(?int $parser): APIResponse {
		$this->parser = $parser;
		return $this;
	}

	private function parse(): array {
		switch($this->parser) {
			case self::ITUNES_API_PARSER:

				$content = trim($this->getContents(), " \t\n\r\0\x0B");
				if(!preg_match('/<script[^>]*id="serialized-server-data">(.*)<\/script>/', $content, $matches)) {
					return [];
				}
				$json = $matches[1];
				$array = json_decode($json, true);
				return array_keys($array) === [0] ? $array[0] : $array;
			default:
				return [];
		}
	}

}