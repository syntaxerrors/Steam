<?php

namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use Illuminate\Support\Collection;
use Syntax\SteamApi\Containers\Game;
use Syntax\SteamApi\Containers\Player\Level;

class Player extends Client
{
    public function __construct($steamId)
    {
        parent::__construct();
        $this->interface = 'IPlayerService';
        $this->isService = true;
        $this->steamId   = $steamId;
    }

    public function GetSteamLevel()
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];

        // Get the client
        $client = $this->getServiceResponse($arguments);

        return $client->player_level;
    }

    public function GetPlayerLevelDetails(): ?Level
    {
        $details = $this->GetBadges();

        if(count((array) $details) == 0) {
            return null;
        }

        $details = new Level($details);

        return $details;
    }

    public function GetBadges(): string
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];

        // Get the client
        return $this->getServiceResponse($arguments);
    }

    public function GetCommunityBadgeProgress($badgeId = null): string
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];
        if ($badgeId != null) {
            $arguments['badgeid'] = $badgeId;
        }

        // Get the client
        return $this->getServiceResponse($arguments);
    }

    public function GetOwnedGames($includeAppInfo = true, $includePlayedFreeGames = false, $appIdsFilter = [])
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];
        if ($includeAppInfo) {
            $arguments['include_appinfo'] = $includeAppInfo;
        }
        if ($includePlayedFreeGames) {
            $arguments['include_played_free_games'] = $includePlayedFreeGames;
        }

        $appIdsFilter = (array) $appIdsFilter;

        if (count($appIdsFilter) > 0) {
            $arguments['appids_filter'] = $appIdsFilter;
        }

        // Get the client
        $client = $this->getServiceResponse($arguments);

        // Clean up the games
        return $this->convertToObjects(isset($client->games) ? $client->games : []);
    }

    public function GetRecentlyPlayedGames($count = null): ?Collection
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];
        if (! is_null($count)) {
            $arguments['count'] = $count;
        }

        // Get the client
        $client = $this->getServiceResponse($arguments);

        if (isset($client->total_count) && $client->total_count > 0) {
            // Clean up the games
            return $this->convertToObjects($client->games);
        }

        return null;
    }

    public function IsPlayingSharedGame($appIdPlaying)
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = [
            'steamId'       => $this->steamId,
            'appid_playing' => $appIdPlaying,
        ];

        // Get the client
        $client = $this->getServiceResponse($arguments);

        return $client->lender_steamid;
    }

    protected function convertToObjects($games): Collection
    {
        $convertedGames = $this->convertGames($games);

        $games = $this->sortObjects($convertedGames);

        return $games;
    }

    private function convertGames($games): Collection
    {
        $convertedGames = new Collection;

        foreach ($games as $game) {
            $convertedGames->add(new Game($game));
        }

        return $convertedGames;
    }
}
