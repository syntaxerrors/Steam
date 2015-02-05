<?php namespace Syntax\SteamApi;

use stdClass;
use Guzzle\Http\Client as GuzzleClient;
use Exception;
use Illuminate\Support\Facades\Config;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Exceptions\ClassNotFoundException;

/**
 * @method news()
 * @method player($steamId)
 * @method user($steamId)
 * @method userStats($steamId)
 * @method app()
 * @method group()
 */
class Client {

    use SteamId;

    public    $validFormats = ['json', 'xml', 'vdf'];

    protected $url          = 'http://api.steampowered.com/';

    protected $client;

    protected $interface;

    protected $method;

    protected $version      = 'v0002';

    protected $apiKey;

    protected $apiFormat    = 'json';

    protected $steamId;

    protected $isService    = false;

    public function __construct()
    {
        $apiKey = $this->getApiKey();

        $this->client = new GuzzleClient($this->url);
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
     * @param string $arguments
     *
     * @return string
     *
     * @throws ApiArgumentRequired
     * @throws ApiCallFailedException
     */
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
            $parameters = array_merge($arguments, $parameters);
        }

        // Build the query string
        $parameters = http_build_query($parameters);

        // Send the request and get the results
        $request  = $this->client->get($steamUrl . '?' . $parameters);
        $response = $this->sendRequest($request);

        // Pass the results back
        return $response->body;
    }

    protected function setUpXml(array $arguments = [])
    {
        $steamUrl = $this->buildUrl();

        // Build the query string
        $parameters = http_build_query($arguments);

        // Pass the results back
        return simplexml_load_file($steamUrl . '?' . $parameters);
    }

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
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

            $this->convertSteamIdTo64();
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

    /**
     * @param Collection $objects
     *
     * @return $this
     */
    protected function sortObjects($objects)
    {
        return $objects->sortBy(function ($object) {
            return $object->name;
        });
    }

    /**
     * @param string $method
     * @param string $version
     */
    protected function setApiDetails($method, $version)
    {
        $this->method  = $method;
        $this->version = $version;
    }

    protected function getServiceResponse($arguments)
    {
        $arguments = json_encode($arguments);

        // Get the client
        $client = $this->setUpService($arguments)->response;

        return $client;
    }

    /**
     * @return string
     * @throws Exceptions\InvalidApiKeyException
     */
    protected function getApiKey()
    {
        $apiKey = \Config::get('steam-api.steamApiKey');

        if ($apiKey == 'YOUR-API-KEY') {
            throw new Exceptions\InvalidApiKeyException();
        }
        if (is_null($apiKey) || $apiKey == '' || $apiKey == []) {
            $apiKey = getenv('apiKey');
        }

        return $apiKey;
    }

    private function convertSteamIdTo64()
    {
        if (is_array($this->steamId)) {
            array_walk($this->steamId, function (&$id) {
                if (strpos($id, ':') !== false) {
                    // Convert the id to all types and grab the 64 bit version
                    $id = $this->convertToAll($id)->id64;
                }
            });
        } elseif (strpos(':', $this->steamId) !== false) {
            // Convert the id to all types and grab the 64 bit version
            $this->steamId = $this->convertToAll($this->steamId)->id64;
        }
    }
}