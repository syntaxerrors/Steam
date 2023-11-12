<?php

namespace Syntax\SteamApi\Steam;

use GuzzleHttp\Exception\GuzzleException;
use Syntax\SteamApi\Client;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;

class News extends Client
{
    public function __construct()
    {
        parent::__construct();
        $this->interface = 'ISteamNews';
    }

    /**
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    public function GetNewsForApp($appId, $count = 5, $maxLength = null)
    {
        // Set up the api details
        $this->method  = __FUNCTION__;
        $this->version = 'v0002';

        // Set up the arguments
        $arguments = [
            'appid' => $appId,
            'count' => $count,
        ];

        if (! is_null($maxLength)) {
            $arguments['maxlength'] = $maxLength;
        }

        // Get the client
        $client = $this->setUpClient($arguments);

        return $client->appnews;
    }
}
