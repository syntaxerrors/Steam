<?php namespace Syntax\SteamApi\Containers;

class Player extends BaseContainer {

	public $steamId;

	public $steamIds;

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

	public $location;

	public function __construct($player)
	{
		$this->steamId                  = $player->steamid;
		$this->steamIds                 = (new Id((int)$this->steamId));
		$this->communityVisibilityState = $player->communityvisibilitystate;
		$this->profileState             = $this->checkIssetField($player, 'profilestate');
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
		$this->realName                 = $this->checkIssetField($player, 'realname');
		$this->primaryClanId            = $this->checkIssetField($player, 'primaryclanid');
		$this->timecreated              = $this->checkIssetField($player, 'timecreated');
		$this->personaStateFlags        = $this->checkIssetField($player, 'personastateflags');
		$this->locCountryCode           = $this->checkIssetField($player, 'loccountrycode');
		$this->locStateCode             = $this->checkIssetField($player, 'locstatecode');
		$this->locCityId                = $this->checkIssetField($player, 'loccityid');
		$this->location                 = $this->getLocation();
	}

	protected function getLocation()
	{
		$countriesFile = json_decode(\file_get_contents(__DIR__ . '/../Resources/countries.json'));
		$result        = new \stdClass;

		if ($this->locCountryCode != null) {
			$result->country = $countriesFile->{$this->locCountryCode}->name;
		}

		if ($this->locCountryCode != null && $this->locStateCode != null) {
			$result->state = $countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->name;
		}

		if ($this->locCountryCode != null && $this->locStateCode != null && $this->locCityId != null) {
			if (! empty($countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->cities)) {
				$result->city = $countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->cities->{$this->locCityId}->name;
			}
		}

		return $result;
	}

	protected function convertPersonaState($personaState)
	{
		switch ($personaState) {
			case 0:
				return '<span class="text-error">Offline</span>';
			case 1:
				return '<span class="text-success">Online</span>';
			case 2:
				return '<span class="text-warning">Busy</span>';
			case 3:
				return '<span class="text-warning">Away</span>';
			case 4:
				return '<span class="text-warning">Snooze</span>';
			case 5:
				return 'Looking to Trade';
			case 6:
				return 'Looking to Play';
		}
	}

}
