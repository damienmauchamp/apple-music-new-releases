<?php

namespace src\Token;

/**
 * Class JWT
 *
 * @package Mapkit
 * @source https://github.com/includable/mapkit-jwt/blob/master/src/JWT.php
 */
class JWT {

	public static function getToken(string $private_key, string $key_id, string $team_id, ?string $origin = null, int $expiry = 15552000): string {
		$payload = [
			'iss' => $team_id,
			'iat' => time(),
			'exp' => time() + $expiry
		];

		if($origin) {
			$payload['origin'] = $origin;
		}

		$header = [
			"kid" => sprintf('"%"', $key_id),
			"typ" => "JWT"
		];

		return \Firebase\JWT\JWT::encode($payload, $private_key, 'ES256', $key_id, $header);
	}

	/**
	 * Generates a JWT token that can be used for MapKit JS or MusicKit authorization.
	 *
	 * @param string $private_key Contents of, or path to, private key file
	 * @param string $key_id Key ID provided by Apple
	 * @param string $team_id Apple Developer Team Identifier
	 * @param string|null $origin Optionally limit header origin
	 * @param int $expiry The expiry timeout in seconds (defaults to 15552000 = 108 days)
	 * @return string|false
	 */
	public static function getTokenOld(string $private_key, string $key_id, string $team_id, ?string $origin = null, int $expiry = 15552000) {
		$header = [
			'alg' => 'ES256',
			'typ' => 'JWT',
			"kid" => sprintf('"%"', $key_id),
		];
		$body = [
			'iss' => $team_id,
			'iat' => time(),
			'exp' => time() + $expiry
		];

		if($origin) {
			$body['origin'] = $origin;
		}

		$payload = self::encode(json_encode($header)).'.'.self::encode(json_encode($body));

		if(!$key = openssl_pkey_get_private($private_key)) {
			return false;
		}

		if(!openssl_sign($payload, $result, $key, OPENSSL_ALGO_SHA256)) {
			return false;
		}

		return $payload.'.'.self::encode($result);
	}

	/**
	 * URL-safe base64 encoding.
	 *
	 * @param string $data
	 * @return string
	 */
	private static function encode($data): string {
		$encoded = strtr(base64_encode($data), '+/', '-_');
		return rtrim($encoded, '=');
	}
}
