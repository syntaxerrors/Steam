<?php

namespace Syntax\SteamApi\Containers\Group;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Syntax\SteamApi\Containers\BaseContainer;

class Details extends BaseContainer implements Arrayable, Jsonable
{
    public $name;

    public $url;

    public $headline;

    public $summary;

    public $avatarIcon;

    public $avatarMedium;

    public $avatarFull;

    public $avatarIconUrl;

    public $avatarMediumUrl;

    public $avatarFullUrl;

    function __construct($details)
    {
        $this->name            = (string)$details->groupName;
        $this->url             = (string)$details->groupUrl;
        $this->headline        = (string)$details->headline;
        $this->summary         = (string)$details->summary;
        $this->avatarIcon      = $this->getImageForAvatar((string)$details->avatarIcon);
        $this->avatarMedium    = $this->getImageForAvatar((string)$details->avatarMedium);
        $this->avatarFull      = $this->getImageForAvatar((string)$details->avatarFull);
        $this->avatarIconUrl   = (string)$details->avatarIcon;
        $this->avatarMediumUrl = (string)$details->avatarMedium;
        $this->avatarFullUrl   = (string)$details->avatarFull;
    }

    public function toArray()
    {
        return [
            "name" => $this->name,
            "url" => $this->url,
            "headline" => $this->headline,
            "summary" => $this->summary,
            "avatarIcon" => $this->avatarIcon,
            "avatarMedium" => $this->avatarMedium,
            "avatarFull" => $this->avatarFull,
            "avatarIconUrl" => $this->avatarIconUrl,
            "avatarMediumUrl" => $this->avatarMediumUrl,
            "avatarFullUrl" => $this->avatarFullUrl,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}
