<?php

namespace Syntax\SteamApi\Containers;

class GameDetails extends BaseContainer
{
    public ?int $gameId;

    public mixed $serverIp;

    public mixed $serverSteamId;
    
    public mixed $lobbyId;

    public mixed $extraInfo;

    public function __construct($gameDetails)
    {
        $gameId = $this->checkIssetField($gameDetails, 'gameid');

        $this->serverIp      = $this->checkIssetField($gameDetails, 'gameserverip');
        $this->serverSteamId = $this->checkIssetField($gameDetails, 'gameserversteamid');
        $this->extraInfo     = $this->checkIssetField($gameDetails, 'gameextrainfo');
        $this->lobbyId       = $this->checkIssetField($gameDetails, 'lobbysteamid');
        $this->gameId        = is_null($gameId) ? null : (int)$gameId;
    }
}
