<?php

namespace Syntax\SteamApi;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use stdClass;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Syntax\SteamApi\Exceptions\ApiArgumentRequired;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Exceptions\ClassNotFoundException;
use Syntax\SteamApi\Exceptions\InvalidApiKeyException;
use Syntax\SteamApi\Steam\App;
use Syntax\SteamApi\Steam\Group;
use Syntax\SteamApi\Steam\Item;
use Syntax\SteamApi\Steam\News;
use Syntax\Steamapi\Steam\Package;
use Syntax\SteamApi\Steam\Player;
use Syntax\SteamApi\Steam\User;
use Syntax\SteamApi\Steam\User\Stats;

/**
 * @method News       news()
 * @method Player     player($steamId)
 * @method User       user($steamId)
 * @method Stats      userStats($steamId)
 * @method App        app()
 * @method Package    package()
 * @method Group      group()
 * @method Item       item($appId)
 */
class Client
{
    use SteamId;

    public $validFormats = ['json', 'xml', 'vdf'];

    protected $url = 'http://api.steampowered.com/';

    protected $client;

    protected $interface;

    protected $method;

    protected $version = 'v0002';

    protected $apiKey;

    protected $apiFormat = 'json';

    protected $steamId;

    protected $isService = false;

    /**
     * @throws InvalidApiKeyException
     */
    public function __construct()
    {
        $apiKey = $this->getApiKey();

        $this->client = new GuzzleClient();
        $this->apiKey = $apiKey;

        // Set up the Ids
        $this->setUpFormatted();
    }

    public function get()
    {
        return $this;
    }

    public function getSteamId()
    {
        return $this->steamId;
    }

    /**
     * @param null $arguments
     *
     * @return stdClass
     *
     * @throws ApiArgumentRequired
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    protected function setUpService($arguments = null): stdClass
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

        // Send the request and get the results
        $request  = new Request('GET', $steamUrl . '?' . $parameters);
        $response = $this->sendRequest($request);

        // Pass the results back
        return $response->body;
    }

    /**
     * @throws GuzzleException
     * @throws ApiCallFailedException
     */
    protected function setUpClient(array $arguments = [])
    {
        $versionFlag = ! is_null($this->version);
        $steamUrl    = $this->buildUrl($versionFlag);

        $parameters = [
            'key'    => $this->apiKey,
            'format' => $this->apiFormat,
        ];

        if (! empty($arguments)) {
            $parameters = array_merge($arguments, $parameters);
        }

        // Build the query string
        $parameters = http_build_query($parameters);

        $headers = [];
        if (array_key_exists('l', $arguments)) {
            $headers = [
                'Accept-Language' => $arguments['l'],
            ];
        }

        // Send the request and get the results
        $request  = new Request('GET', $steamUrl . '?' . $parameters, $headers);
        $response = $this->sendRequest($request);

        // Pass the results back
        return $response->body;
    }

    protected function setUpXml(array $arguments = []): \SimpleXMLElement|null
    {
        $steamUrl = $this->buildUrl();

        // Build the query string
        $parameters = http_build_query($arguments);

        // Pass the results back
        libxml_use_internal_errors(true);
        $result = simplexml_load_file($steamUrl . '?' . $parameters);

        if (! $result) {
            return null;
        }

        return $result;
    }

    public function getRedirectUrl(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        $this->url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
    }

    /**
     *
     * @param Request $request
     * @return stdClass
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    protected function sendRequest(Request $request): stdClass
    {
        // Try to get the result.  Handle the possible exceptions that can arise
        try {
            $response = $this->client->send($request);

            $result       = new stdClass();
            $result->code = $response->getStatusCode();
            $result->body = json_decode((string) $response->getBody(true), null, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new ApiCallFailedException($e->getMessage(), $e->getResponse()->getStatusCode(), $e);
        } catch (ServerException $e) {
            throw new ApiCallFailedException('Api call failed to complete due to a server error.', $e->getResponse()->getStatusCode(), $e);
        } catch (Exception $e) {
            throw new ApiCallFailedException($e->getMessage(), $e->getCode(), $e);
        }

        // If all worked out, return the result
        return $result;
    }

    private function buildUrl($version = false): string
    {
        // Set up the basic url
        $url = $this->url . $this->interface . '/' . $this->method . '/';

        // If we have a version, add it
        if ($version) {
            return $url . $this->version . '/';
        }

        return $url;
    }

    /**
     * @throws ClassNotFoundException
     */
    public function __call($name, $arguments)
    {
        // Handle a steamId being passed
        if (! empty($arguments) && count($arguments) == 1) {
            $this->steamId = $arguments[0];

            $this->convertSteamIdTo64();
        }

        // Inside the root steam directory
        $class      = ucfirst((string) $name);
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

    /**
     * @param Collection $objects
     *
     * @return Collection
     */
    protected function sortObjects(Collection $objects): Collection
    {
        return $objects->sortBy(fn($object) => $object->name);
    }

    /**
     * @param string $method
     * @param string $version
     */
    protected function setApiDetails(string $method, string $version): void
    {
        $this->method  = $method;
        $this->version = $version;
    }

    /**
     * @throws ApiArgumentRequired
     * @throws ApiCallFailedException
     * @throws GuzzleException
     * @throws \JsonException
     */
    protected function getServiceResponse($arguments)
    {
        $arguments = json_encode($arguments, JSON_THROW_ON_ERROR);

        // Get the client
        return $this->setUpService($arguments)->response;
    }

    /**
     * @return string
     * @throws Exceptions\InvalidApiKeyException
     */
    protected function getApiKey(): string
    {
        $apiKey = Config::get('steam-api.steamApiKey');

        if ($apiKey == 'YOUR-API-KEY') {
            throw new Exceptions\InvalidApiKeyException();
        }

        if (is_null($apiKey) || $apiKey === '' || $apiKey == []) {
            $apiKey = getenv('apiKey');
        }

        return $apiKey;
    }

    private function convertSteamIdTo64(): void
    {
        if (is_array($this->steamId)) {
            array_walk($this->steamId, function (&$id) {
                // Convert the id to all types and grab the 64 bit version
                $id = $this->convertToAll($id)->id64;
            });
        } else {
            // Convert the id to all types and grab the 64 bit version
            $this->steamId = $this->convertToAll($this->steamId)->id64;
        }
    }
}
