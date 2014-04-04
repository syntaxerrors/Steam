<?php namespace Syntax\SteamApi;

class Steam_App extends Client {

	public function __construct()
	{
		parent::__construct();
		$this->url       = 'http://store.steampowered.com/';
		$this->interface = 'api';
	}

	public function appDetails($appIds)
	{
		// Set up the api details
		$this->method     = 'appdetails';
		$this->version    = null;

		// Set up the arguments
		$arguments = 'appids='. $appIds;

		// Get the client
		$client = $this->setUpClient($arguments);
		$apps   = $this->convertToObjects($client);

		return $apps;
	}

	public function GetAppList()
	{
		// Set up the api details
		$this->url        = 'http://api.steampowered.com/';
		$this->interface  = 'ISteamApps';
		$this->method     = __FUNCTION__;
		$this->version    = 'v0001';

		// Get the client
		$client = $this->setUpClient();

		return $client->applist->apps->app;
	}

	protected function convertToObjects($apps)
	{
		$cleanedApps = new \Utility_Collection;

		foreach ($apps as $app) {
			if (isset($app->data)) {
				$cleanedApps->add(new Containers\App($app->data));
			}
		}

		$apps = $cleanedApps->sortBy(function ($app) {
			return $app->name;
		});

		return $apps;
	}
}