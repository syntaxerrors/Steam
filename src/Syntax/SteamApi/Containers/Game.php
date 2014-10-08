<?php namespace Syntax\SteamApi\Containers;

class Game {
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
		$this->name                     = isset($app->name) ? $app->name : null;
		$this->playtimeTwoWeeks         = isset($app->playtime_2weeks) ? $this->convertFromMinutes($app->playtime_2weeks) : '0 minutes';
		$this->playtimeForever          = isset($app->playtime_forever) ? $app->playtime_forever : 0;
		$this->playtimeForeverReadable  = $this->convertFromMinutes($this->playtimeForever);
		$this->icon                     = isset($app->img_icon_url) ? $this->getImageForGame($app->appid, $app->img_icon_url) : null;
		$this->logo                     = isset($app->img_logo_url) ? $this->getImageForGame($app->appid, $app->img_logo_url) : null;
		$this->header                   = 'http://cdn.steampowered.com/v/gfx/apps/'. $this->appId .'/header.jpg';
		$this->hasCommunityVisibleStats = isset($app->has_community_visible_stats) ? $app->has_community_visible_stats : 0;
	}

	protected function getImageForGame($appId, $hash)
	{
		if ($hash != null) {
			return 'http://media.steampowered.com/steamcommunity/public/images/apps/'. $appId .'/'. $hash .'.jpg';
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
		$hours = floor($hourSeconds / $secondsInAnHour);

		// extract minutes
		$minuteSeconds = $hourSeconds % $secondsInAnHour;
		$minutes = floor($minuteSeconds / $secondsInAMinute);

		// extract the remaining seconds
		$remainingSeconds = $minuteSeconds % $secondsInAMinute;
		$seconds = ceil($remainingSeconds);

		// return the final string
		$output = '';

		if ($days > 0) $output .= $days .' days ';
		if ($hours > 0) $output .= $hours .' hours ';

		$output .= $minutes .' minutes';

		return $output;
	}

}