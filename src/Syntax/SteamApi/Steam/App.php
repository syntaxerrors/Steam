<?php

namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use NukaCode\Database\Collection;
use Syntax\SteamApi\Containers\App as AppContainer;

class App extends Client
{
    public function __construct()
    {
        parent::__construct();
        $this->url       = 'http://store.steampowered.com/';
        $this->interface = 'api';
    }

    public function appDetails($appIds, $country = null, $language = null)
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
        $apps   = $this->convertToObjects($client);

        return $apps;
    }

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

    protected function convertToObjects($apps)
    {
        $convertedApps = $this->convertGames($apps);

        $apps = $this->sortObjects($convertedApps);

        return $apps;
    }

    /**
     * @param $apps
     *
     * @return Collection
     */
    protected function convertGames($apps)
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
