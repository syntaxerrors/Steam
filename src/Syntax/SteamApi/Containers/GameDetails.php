<?php

namespace Syntax\SteamApi\Containers;

class GameDetails extends BaseContainer
{
    public $gameId;

    public $serverIp;

    public $serverSteamId;

    public $extraInfo;

    public function __construct($gameDetails)
    {
        $gameId = $this->checkIssetField($gameDetails, 'gameid');

        $this->serverIp      = $this->checkIssetField($gameDetails, 'gameserverip');
        $this->serverSteamId = $this->checkIssetField($gameDetails, 'gameserversteamid');
        $this->extraInfo     = $this->checkIssetField($gameDetails, 'gameextrainfo');
        $this->gameId        = is_null($gameId) ? null : (int)$gameId;
    }
}
