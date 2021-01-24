<?php

namespace Syntax\SteamApi\Containers;

class Game extends BaseContainer
{
    public $appId;

    public $name;

    public $playtimeTwoWeeks;

    public $playtimeTwoWeeksReadable;

    public $playtimeForever;

    public $playtimeForeverReadable;

    public $icon;

    public $logo;

    public $header;

    public $hasCommunityVisibleStats;

    public function __construct($app)
    {
        $this->appId                    = $app->appid;
        $this->name                     = $this->checkIssetField($app, 'name');
        $this->playtimeTwoWeeks         = $this->checkIssetField($app, 'playtime_2weeks', 0);
        $this->playtimeTwoWeeksReadable = $this->convertFromMinutes($this->playtimeTwoWeeks);
        $this->playtimeForever          = $this->checkIssetField($app, 'playtime_forever', 0);
        $this->playtimeForeverReadable  = $this->convertFromMinutes($this->playtimeForever);
        $this->icon                     = $this->checkIssetImage($app, 'img_icon_url');
        $this->logo                     = $this->checkIssetImage($app, 'img_logo_url');
        $this->header                   = 'https://cdn.akamai.steamstatic.com/steam/apps/' . $this->appId . '/header.jpg';
        $this->hasCommunityVisibleStats = $this->checkIssetField($app, 'has_community_visible_stats', 0);
    }

    /**
     * @param        $app
     * @param string $field
     * @param null $value
     *
     * @return null|string
     */
    protected function checkIssetImage($app, string $field, $value = null): ?string
    {
        return isset($app->$field) ? $this->getImageForGame($app->appid, $app->$field) : $value;
    }

    protected function getImageForGame($appId, $hash): ?string
    {
        if ($hash != null) {
            return 'https://media.steampowered.com/steamcommunity/public/images/apps/' . $appId . '/' . $hash . '.jpg';
        }

        return null;
    }
}
