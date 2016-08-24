<?php

namespace Syntax\SteamApi\Containers\Group;

class MemberDetails
{
    public $count;

    public $inChat;

    public $inGame;

    public $online;

    function __construct($details)
    {
        $this->count  = (int)(string)$details->memberCount;
        $this->inChat = (int)(string)$details->membersInChat;
        $this->inGame = (int)(string)$details->membersInGame;
        $this->online = (int)(string)$details->membersOnline;
    }
}
