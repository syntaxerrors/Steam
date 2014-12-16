<?php namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use Syntax\SteamApi\Containers\Player;

class User extends Client {

	public function __construct($steamId)
	{
		parent::__construct();
		$this->interface = 'ISteamUser';
		$this->steamId   = $steamId;
	}

	public function GetPlayerSummaries($steamId = null)
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v0002';

		if ($steamId == null) {
			$steamId = $this->steamId;
		}

		// Set up the arguments
		$arguments = [
			'steamids' => $steamId
		];

		// Get the client
		$client = $this->setUpClient($arguments)->response;

		// Clean up the games
		$players = $this->convertToObjects($client->players);

		return $players;
	}
	
	public function GetPlayerBans($steamId = null)
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v1';

		if ($steamId == null) {
			$steamId = $this->steamId;
		}

		// Set up the arguments
		$arguments = [
			'steamids' => $steamId
		];

		// Get the client
		$client = $this->setUpClient($arguments);

		return $client->players;
	}
	
	public function GetFriendList($relationship = 'all')
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v0001';

		// Set up the arguments
		$arguments = [
			'steamid' => $this->steamId,
			'relationship' => $relationship
		];

		// Get the client
		$client = $this->setUpClient($arguments)->friendslist;

		// Clean up the games
		$steamIds = array();

		foreach ($client->friends as $friend) {
			$steamIds[] = $friend->steamid;
		}

		$friends = $this->GetPlayerSummaries(implode(',', $steamIds));

		return $friends;
	}

	protected function convertToObjects($players)
	{
		$cleanedPlayers = array();

		foreach ($players as $player) {
			$cleanedPlayers[] = new Player($player);
		}

		return $cleanedPlayers;
	}
}
