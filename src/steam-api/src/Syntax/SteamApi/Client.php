<?php namespace Syntax\SteamApi;

class Client {

	protected $url = 'http://api.steampowered.com/';

	protected $interface;

	protected $method;

	protected $version = 'v0002';

	protected $apiKey;

	protected $apiFormat = 'json';

	protected $steamId;

	protected $isService = false;

	public $validFormats = array('json', 'xml', 'vdf');

	public function __construct()
	{
		$apiKey    = \Config::get('steam-api::steamApiKey');

		if ($apiKey == 'YOUR-API-KEY') {
			throw new Exceptions\InvalidApiKeyException();
		}

		$this->apiKey    = $apiKey;

		return $this;
	}

	protected function setUpClient($arguments = null)
	{
		if ($this->isService) {
			if ($arguments == null) {
				throw new ApiArgumentRequired;
			}
			$steamUrl = $this->url . $this->interface .'/'. $this->method .'/'. $this->version .'/?key='. $this->apiKey .'&format='. $this->apiFormat .'&input_json='. $arguments;
		} else {
			$steamUrl = $this->url . $this->interface .'/'. $this->method .'/';
			if (!is_null($this->version)) {
				$steamUrl .= $this->version .'/';
			}
			$steamUrl .= '?key='. $this->apiKey .'&format='. $this->apiFormat;

			if ($arguments != null) {
				$steamUrl .= '&'. $arguments;
			}
		}

		return json_decode(\cURL::get($steamUrl));
	}

	public function news()
	{
		return new Steam_News;
	}

	public function user($steamId)
	{
		return new Steam_User($steamId);
	}

	public function player($steamId)
	{
		return new Steam_Player($steamId);
	}

	public function userStats($steamId)
	{
		return new Steam_User_Stats($steamId);
	}

	public function app()
	{
		return new Steam_App;
	}

	public function get()
	{
		return $this;
	}
}