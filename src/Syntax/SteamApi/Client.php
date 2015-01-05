<?php namespace Syntax\SteamApi;

use stdClass;
use Guzzle\Http\Client as GuzzleClient;
use Exception;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Exceptions\ClassNotFoundException;

class Client {

	public    $validFormats = ['json', 'xml', 'vdf'];

	protected $url          = 'http://api.steampowered.com/';

	protected $interface;

	protected $method;

	protected $version      = 'v0002';

	protected $apiKey;

	protected $apiFormat    = 'json';

	protected $steamId;

	protected $isService    = false;

	public function __construct()
	{
		$apiKey = \Config::get('steam-api::steamApiKey');

		if ($apiKey == 'YOUR-API-KEY') {
			throw new Exceptions\InvalidApiKeyException();
		}

		$this->client = new GuzzleClient($this->url);
		$this->apiKey = $apiKey;

		return $this;
	}

	public function get()
	{
		return $this;
	}

	public function convertCommunityIdToSteamId(int $id)
	{
		$x = ($id - 76561197960265728) / 2;

		return 'STEAM_0:' . is_float($x) . ':' . (int)$x;
	}

	public function convertSteamIdToCommunityId(string $id)
	{
		$x = explode(':', $id);
		return (string) ($x[2] * 2) + 76561197960265728 + $x[1];
	}

	protected function setUpService($arguments = null)
	{
		// Services have a different url syntax
		if ($arguments == null) {
			throw new ApiArgumentRequired;
		}

		$parameters = [
			'key'        => $this->apiKey,
			'format'     => $this->apiFormat,
			'input_json' => $arguments,
		];

		$steamUrl = $this->buildUrl(true);

		// Build the query string
		$parameters = http_build_query($parameters);

		//ppd($steamUrl . '?' . $parameters);

		// Send the request and get the results
		$request  = $this->client->get($steamUrl . '?' . $parameters);
		$response = $this->sendRequest($request);

		// Pass the results back
		return $response->body;
	}

	protected function setUpClient(array $arguments = [])
	{
		$versionFlag = ! is_null($this->version);
		$steamUrl    = $this->buildUrl($versionFlag);

		$parameters = [
			'key'    => $this->apiKey,
			'format' => $this->apiFormat
		];

		if (! empty($arguments)) {
			$parameters = array_merge($parameters, $arguments);
		}

		// Build the query string
		$parameters = http_build_query($parameters);

		// Send the request and get the results
		$request  = $this->client->get($steamUrl . '?' . $parameters);
		$response = $this->sendRequest($request);

		// Pass the results back
		return $response->body;
	}

	/**
	 * @param $request
	 *
	 * @throws ApiCallFailedException
	 * @return stdClass
	 */
	protected function sendRequest($request)
	{
		// Try to get the result.  Handle the possible exceptions that can arise
		try {
			$response = $this->client->send($request);

			$result       = new stdClass();
			$result->code = $response->getStatusCode();
			$result->body = json_decode($response->getBody(true));

		} catch (ClientErrorResponseException $e) {
			throw new ApiCallFailedException($e->getMessage(), $e->getResponse()->getStatusCode(), $e);

		} catch (ServerErrorResponseException $e) {
			throw new ApiCallFailedException('Api call failed to complete due to a server error.', $e->getResponse()->getStatusCode(), $e);

		} catch (Exception $e) {
			throw new ApiCallFailedException($e->getMessage(), $e->getCode(), $e);

		}

		// If all worked out, return the result
		return $result;
	}

	private function buildUrl($version = false)
	{
		// Set up the basic url
		$url = $this->url . $this->interface . '/' . $this->method . '/';

		// If we have a version, add it
		if ($version) {
			return $url . $this->version . '/';
		}

		return $url;
	}

	public function __call($name, $arguments)
	{
		// Handle a steamId being passed
		if (! empty($arguments) && count($arguments) == 1) {
			$this->steamId = $arguments[0];

			if (strpos(':', $this->steamId) !== false) {
				$this->steamId = $this->convertSteamIdToCommunityId($this->steamId);
			}
		}

		// Inside the root steam directory
		$class      = ucfirst($name);
		$steamClass = '\Syntax\SteamApi\Steam\\' . $class;

		if (class_exists($steamClass)) {
			return new $steamClass($this->steamId);
		}

		// Inside a nested directory
		$class      = implode('\\', preg_split('/(?=[A-Z])/', $class, -1, PREG_SPLIT_NO_EMPTY));
		$steamClass = '\Syntax\SteamApi\Steam\\' . $class;

		if (class_exists($steamClass)) {
			return new $steamClass($this->steamId);
		}

		// Nothing found
		throw new ClassNotFoundException($name);
	}
}