<?php

namespace Syntax\SteamApi\Steam\User;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Syntax\SteamApi\Client;
use Syntax\SteamApi\Containers\Achievement;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;

class Stats extends Client
{
    public function __construct($steamId)
    {
        parent::__construct();
        $this->interface = 'ISteamUserStats';
        $this->steamId   = $steamId;
    }

    /**
     * @param $appId
     *
     * @return array|null
     * @throws GuzzleException
     * @throws ApiCallFailedException
     * @deprecated
     *
     */
    public function GetPlayerAchievementsAPI($appId): ?array
    {
        // Set up the api details
        $this->method  = 'GetPlayerAchievementsAPI';
        $this->version = 'v0001';

        // Set up the arguments
        $arguments = [
            'steamid' => $this->steamId,
            'appid'   => $appId,
            'l'       => 'english',
        ];

        // Get the client
        $stats = $this->GetSchemaForGame($appId);

        // Make sure the game has achievements
        if ($stats == null || $stats->game->availableGameStats->achievements == null) {
            return null;
        }

        $client = $this->setUpClient($arguments)->playerstats;
        $stats  = $stats->game->availableGameStats->achievements;

        // Clean up the games
        return $this->convertToObjects($client->achievements);
    }

    public function GetPlayerAchievements($appId): ?array
    {
        // Set up the api details
        $this->interface = null;
        $this->method    = 'achievements';

        if (is_numeric($this->steamId)) {
            $this->url = 'http://steamcommunity.com/profiles/';
        } else {
            $this->url = 'http://steamcommunity.com/id/';
        }

        $this->url = $this->url . $this->steamId . '/stats/' . $appId;

        // Set up the arguments
        $arguments = [
            'xml' => 1,
        ];

        try {
            // Get the client
            $client = $this->setUpXml($arguments);

            // Clean up the games
            return $this->convertToObjects($client->achievements->achievement);
        } catch (Exception) {
            // In rare cases, games can force the use of a simplified name instead of an app ID
            // In these cases, try again by grabbing the redirected url.
            if (is_int($appId)) {
                $this->getRedirectUrl();

                try {
                    // Get the client
                    $client = $this->setUpXml($arguments);

                    // Clean up the games
                    return $this->convertToObjects($client->achievements->achievement);
                } catch (Exception) {
                    return null;
                }
            }

            // If the name and ID fail, return null.
            return null;
        }
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
    public function GetUserStatsForGame($appId, $all = false): mixed
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

    /**
     * @param $appId
     *
     * @link https://wiki.teamfortress.com/wiki/WebAPI/GetSchemaForGame
     *
     * @return mixed
     */
    public function GetSchemaForGame($appId): mixed
    {
        // Set up the api details
        $this->method  = __FUNCTION__;
        $this->version = 'v0002';

        // Set up the arguments
        $arguments = [
            'appid' => $appId,
            'l'     => 'english',
        ];

        // Get the client
        return $this->setUpClient($arguments);
    }

    protected function convertToObjects($achievements): array
    {
        $cleanedAchievements = [];

        foreach ($achievements as $achievement) {
            $cleanedAchievements[] = new Achievement($achievement);
        }

        return $cleanedAchievements;
    }
}
