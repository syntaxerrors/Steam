<?php namespace Syntax\SteamApi\Steam\User;

use Syntax\SteamApi\Client;
use Syntax\SteamApi\Containers\Achievement;

class Stats extends Client
{

	public function __construct($steamId)
	{
		parent::__construct();
		$this->interface = 'ISteamUserStats';
		$this->steamId   = $steamId;
	}

	public function GetPlayerAchievements($appId)
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v0001';

		// Set up the arguments
		$arguments = [
			'steamid' => $this->steamId,
			'appid'   => $appId,
			'l'       => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->playerstats;

		// Clean up the games
		$achievements = $this->convertToObjects($client->achievements);

		return $achievements;
	}

	public function GetGlobalAchievementPercentagesForApp($gameId)
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v0002';

		// Set up the arguments
		$arguments = [
			'gameid' => $gameId,
			'l'      => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->achievementpercentages;

		return $client->achievements;
	}

	/**
	 * @param $appId int Steam 64 id
	 * @param $all   bool Return all stats when true and only achievements when false
	 *
	 * @return mixed
	 */

	public function GetUserStatsForGame($appId, $all = false)
	{
		// Set up the api details
		$this->method  = __FUNCTION__;
		$this->version = 'v0002';

		// Set up the arguments
		$arguments = [
			'steamid' => $this->steamId,
			'appid'   => $appId,
			'l'       => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->playerstats;

		// Games like DOTA and CS:GO have additional stats here.  Return everything if they are wanted.
		if ($all === true) {
			return $client;
		}

		return $client->achievements;
	}

	protected function convertToObjects($achievements)
	{
		$cleanedAchievements = [];

		foreach ($achievements as $achievement) {
			$cleanedAchievements[] = new Achievement($achievement);
		}

		return $cleanedAchievements;
	}
}