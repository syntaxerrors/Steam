<?php

namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use Syntax\SteamApi\Containers\Group as GroupContainer;

class Group extends Client
{
    public function GetGroupSummary($group)
    {
        // Set up the api details
        $this->method = 'memberslistxml';

        if (is_numeric($group)) {
            $this->url = 'https://steamcommunity.com/gid/';
        } else {
            $this->url = 'https://steamcommunity.com/groups/';
        }

        $this->url = $this->url . $group;

        // Set up the arguments
        $arguments = [
            'xml' => 1,
        ];

        // Get the client
        $client = $this->setUpXml($arguments);

        // Clean up the games
        $group = new GroupContainer($client);

        return $group;
    }
}
