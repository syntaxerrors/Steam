<?php namespace Syntax\SteamApi\Containers;

use Syntax\SteamApi\Client;

class Player {

	public $steamId;

	public $communityId;

	public $communityVisibilityState;

	public $profileState;

	public $personaName;

	public $lastLogoff;

	public $profileUrl;

	public $avatar;

	public $avatarMedium;

	public $avatarFull;

	public $avatarUrl;

	public $avatarMediumUrl;

	public $avatarFullUrl;

	public $personaState;

	public $personaStateId;

	public $realName;

	public $primaryClanId;

	public $timecreated;

	public $personaStateFlags;

	public $locCountryCode;

	public $locStateCode;

	public $locCityId;

	public function __construct($player)
	{
		$this->steamId                  = $player->steamid;
		$this->communityId              = (new Client)->convertCommunityIdToSteamId((int) $this->steamId);
		$this->communityVisibilityState = $player->communityvisibilitystate;
		$this->profileState             = $player->profilestate;
		$this->personaName              = $player->personaname;
		$this->lastLogoff               = date('F jS, Y h:ia', $player->lastlogoff);
		$this->profileUrl               = $player->profileurl;
		$this->avatar                   = $this->getImageForAvatar($player->avatar);
		$this->avatarMedium             = $this->getImageForAvatar($player->avatarmedium);
		$this->avatarFull               = $this->getImageForAvatar($player->avatarfull);
		$this->avatarUrl                = $player->avatar;
		$this->avatarMediumUrl          = $player->avatarmedium;
		$this->avatarFullUrl            = $player->avatarfull;
		$this->personaState             = $this->convertPersonaState($player->personastate);
		$this->personaStateId           = $player->personastate;
		$this->realName                 = isset($player->realname) ? $player->realname : null;
		$this->primaryClanId            = isset($player->primaryclanid) ? $player->primaryclanid : null;
		$this->timecreated              = isset($player->timecreated) ? date('F jS, Y h:ia', $player->timecreated) : null;
		$this->personaStateFlags        = isset($player->personastateflags) ? $player->personastateflags : null;
		$this->locCountryCode           = isset($player->loccountrycode) ? $player->loccountrycode : null;
		$this->locStateCode             = isset($player->locstatecode) ? $player->locstatecode : null;
		$this->locCityId                = isset($player->loccityid) ? $player->loccityid : null;
	}

	protected function getImageForAvatar($image)
	{
		return \HTML::image($image);
	}

	protected function convertPersonaState($personaState)
	{
		switch ($personaState) {
			case 0:
				return '<span class="text-error">Offline</span>';
				break;
			case 1:
				return '<span class="text-success">Online</span>';
				break;
			case 2:
				return '<span class="text-warning">Busy</span>';
				break;
			case 3:
				return '<span class="text-warning">Away</span>';
				break;
			case 4:
				return '<span class="text-warning">Snooze</span>';
				break;
			case 5:
				return 'Looking to Trade';
				break;
			case 6:
				return 'Looking to Play';
				break;
		}
	}

}