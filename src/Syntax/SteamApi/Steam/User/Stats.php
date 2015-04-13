<?php namespace Syntax\SteamApi\Steam\User;

use Syntax\SteamApi\Client;
use Syntax\SteamApi\Containers\Achievement;

class Stats extends Client {

	public function __construct($steamId) {
		parent::__construct();
		$this->interface = 'ISteamUserStats';
		$this->steamId = $steamId;
	}

	public function GetPlayerAchievements($appId) {
		// Set up the api details
		$this->method = __FUNCTION__;
		$this->version = 'v0001';

		// Set up the arguments
		$arguments = [
			'steamid' => $this->steamId,
			'appid' => $appId,
			'l' => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->playerstats;

		// Clean up the games
		$achievements = $this->convertToObjects($client->achievements);

		return $achievements;
	}

	public function GetGlobalAchievementPercentagesForApp($gameId) {
		// Set up the api details
		$this->method = __FUNCTION__;
		$this->version = 'v0002';

		// Set up the arguments
		$arguments = [
			'gameid' => $gameId,
			'l' => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->achievementpercentages;

		return $client->achievements;
	}
	/*
	 * @param boolean $all (return stats and not only achievements)
	 */

	public function GetUserStatsForGame($appId, $all = false) {
		// Set up the api details
		$this->method = __FUNCTION__;
		$this->version = 'v0002';

		// Set up the arguments
		$arguments = [
			'steamid' => $this->steamId,
			'appid' => $appId,
			'l' => 'english',
		];

		// Get the client
		$client = $this->setUpClient($arguments)->playerstats;

		// Do you want also stats and not only achievements? Like CSGO or Dota2 stats
		if ($all) {
			return $client;
		}
		return $client->achievements;
	}

	protected function convertToObjects($achievements) {
		$cleanedAchievements = array();

		foreach ($achievements as $achievement) {
			$cleanedAchievements[] = new Achievement($achievement);
		}

		return $cleanedAchievements;
	}
}