<?php

namespace Syntax\SteamApi\Containers;

class Item extends BaseContainer
{
    public $appId;

    public $name;

    public $playtimeTwoWeeks;

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
        $this->playtimeTwoWeeks         = isset($app->playtime_2weeks) ? $this->convertFromMinutes($app->playtime_2weeks) : '0 minutes';
        $this->playtimeForever          = $this->checkIssetField($app, 'playtime_forever', 0);
        $this->playtimeForeverReadable  = $this->convertFromMinutes($this->playtimeForever);
        $this->icon                     = $this->checkIssetImage($app, 'img_icon_url');
        $this->logo                     = $this->checkIssetImage($app, 'img_logo_url');
        $this->header                   = 'http://cdn.steampowered.com/v/gfx/apps/' . $this->appId . '/header.jpg';
        $this->hasCommunityVisibleStats = $this->checkIssetField($app, 'has_community_visible_stats', 0);
    }

    /**
     * @param        $app
     * @param string $field
     * @param string $value
     *
     * @return null|string
     */
    protected function checkIssetImage($app, $field, $value = null)
    {
        return isset($app->$field) ? $this->getImageForGame($app->appid, $app->$field) : $value;
    }

    protected function getImageForGame($appId, $hash)
    {
        if ($hash != null) {
            return 'http://media.steampowered.com/steamcommunity/public/images/apps/' . $appId . '/' . $hash . '.jpg';
        }

        return null;
    }

    protected function convertFromMinutes($minutes)
    {
        $seconds = $minutes * 60;

        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor($seconds / $secondsInADay);

        // extract hours
        $hourSeconds = $seconds % $secondsInADay;
        $hours       = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes       = floor($minuteSeconds / $secondsInAMinute);

        // return the final string
        $output = '';

        if ($days > 0) {
            $output .= $days . ' days ';
        }
        if ($hours > 0) {
            $output .= $hours . ' hours ';
        }

        $output .= $minutes . ' minutes';

        return $output;
    }
}
