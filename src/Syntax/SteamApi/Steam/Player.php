<?php namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use Syntax\SteamApi\Collection;
use Syntax\SteamApi\Containers\Game;
use Syntax\SteamApi\Containers\Player\Level;

class Player extends Client {

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

    public function GetPlayerLevelDetails()
    {
        $details = $this->GetBadges();

        $details = new Level($details);

        return $details;
    }

    public function GetBadges()
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];

        // Get the client
        $client = $this->getServiceResponse($arguments);

        return $client;
    }

    public function GetCommunityBadgeProgress($badgeId = null)
    {
        // Set up the api details
        $this->setApiDetails(__FUNCTION__, 'v0001');

        // Set up the arguments
        $arguments = ['steamId' => $this->steamId];
        if ($badgeId != null) {
            $arguments['badgeid'] = $badgeId;
        }

        // Get the client
        $client = $this->getServiceResponse($arguments);

        return $client;
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
        if (count($appIdsFilter) > 0) {
            $arguments['appids_filter'] = (array) $appIdsFilter;
        }

        // Get the client
        $client = $this->getServiceResponse($arguments);

        // Clean up the games
        $games = $this->convertToObjects(isset($client->games) ? $client->games : array());

        return $games;
    }

    public function GetRecentlyPlayedGames($count = null)
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

        if ($client->total_count > 0) {
            // Clean up the games
            $games = $this->convertToObjects($client->games);

            return $games;
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
            'appid_playing' => $appIdPlaying
        ];

        // Get the client
        $client = $this->getServiceResponse($arguments);

        return $client->lender_steamid;
    }

    protected function convertToObjects($games)
    {
        $convertedGames = $this->convertGames($games);

        $games = $this->sortObjects($convertedGames);

        return $games;
    }

    private function convertGames($games)
    {
        $convertedGames = new Collection;

        foreach ($games as $game) {
            $convertedGames->add(new Game($game));
        }

        return $convertedGames;
    }
}
