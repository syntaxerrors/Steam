<?php

namespace Syntax\SteamApi\Containers;

class Achievement
{
    public $apiName;

    public $achieved;

    public $name;

    public $description;

    public $icon;

    public $iconGray;

    public $unlockTimestamp;

    public function __construct($achievement)
    {
        $this->apiName         = (string) $achievement->apiname;
        $this->achieved        = (int)(string) $achievement['closed'];
        $this->name            = (string) $achievement->name;
        $this->description     = (string) $achievement->description;
        $this->icon            = (string) $achievement->iconClosed;
        $this->iconGray        = (string) $achievement->iconOpen;
        $this->unlockTimestamp = isset($achievement->unlockTimestamp) ? (int)(string) $achievement->unlockTimestamp : null;
    }
}
