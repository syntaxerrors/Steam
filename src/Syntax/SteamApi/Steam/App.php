<?php

namespace Syntax\SteamApi\Steam;

use GuzzleHttp\Exception\GuzzleException;
use Syntax\SteamApi\Client;
use Illuminate\Support\Collection;
use Syntax\SteamApi\Containers\App as AppContainer;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Exceptions\InvalidApiKeyException;

class App extends Client
{
    /**
     * @throws InvalidApiKeyException
     */

    public function __construct()
    {
        parent::__construct();

        $this->url       = 'http://store.steampowered.com/';
        $this->interface = 'api';
    }

    /**
     * @param $appIds
     * @param null $country
     * @param null $language
     * @return Collection
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    public function appDetails($appIds, $country = null, $language = null): Collection
    {
        // Set up the api details
        $this->method  = 'appdetails';
        $this->version = null;

        // Set up the arguments
        $arguments = [
            'appids' => $appIds,
            'cc' => $country,
            'l' => $language,
        ];

        // Get the client
        $client = $this->setUpClient($arguments);

        return $this->convertToObjects($client);
    }

    /**
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    public function GetAppList()
    {
        // Set up the api details
        $this->url       = 'http://api.steampowered.com/';
        $this->interface = 'ISteamApps';
        $this->method    = __FUNCTION__;
        $this->version   = 'v0001';

        // Get the client
        $client = $this->setUpClient();

        return $client->applist->apps->app;
    }

    protected function convertToObjects($apps): Collection
    {
        $convertedApps = $this->convertGames($apps);

        return $this->sortObjects($convertedApps);
    }

    /**
     * @param $apps
     *
     * @return Collection
     */
    protected function convertGames($apps): Collection
    {
        $convertedApps = new Collection();

        foreach ($apps as $app) {
            if (isset($app->data)) {
                $convertedApps->add(new AppContainer($app->data));
            }
        }

        return $convertedApps;
    }
}
