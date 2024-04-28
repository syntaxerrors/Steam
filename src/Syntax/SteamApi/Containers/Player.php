<?php

namespace Syntax\SteamApi\Containers;

class Player extends BaseContainer
{
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

    public $commentPermission;

    public $gameDetails = null;

    public function __construct($player)
    {
        $this->steamId                  = $player->steamid;
        $this->steamIds                 = (new Id((int)$this->steamId));
        $this->communityVisibilityState = $player->communityvisibilitystate;
        $this->profileState             = $this->checkIssetField($player, 'profilestate');
        $this->personaName              = $player->personaname;
        $this->lastLogoff               = date('F jS, Y h:ia', $this->checkIssetField($player, 'lastlogoff'));
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
        $this->commentPermission        = $this->checkIssetField($player, 'commentpermission');

        $gameDetails = [
            'gameServerIp'      => $this->checkIssetField($player, 'gameserverip'),
            'gameServerSteamId' => $this->checkIssetField($player, 'gameserversteamid'),
            'gameExtraInfo'     => $this->checkIssetField($player, 'gameextrainfo'),
            'gameId'            => $this->checkIssetField($player, 'gameid'),
        ];

        if (! empty(array_filter($gameDetails)))
        {
            $this->gameDetails = (new GameDetails($player));
        }
    }

    /**
     * @throws \JsonException
     */
    protected function getLocation(): \stdClass
    {
        $countriesFile = json_decode(\file_get_contents(__DIR__ . '/../Resources/countries.json'), null, 512, JSON_THROW_ON_ERROR);
        $result        = new \stdClass;

        if ($this->locCountryCode != null && isset($countriesFile->{$this->locCountryCode})) {
            $result->country = $countriesFile->{$this->locCountryCode}->name;

            if ($this->locStateCode != null && isset($countriesFile->{$this->locCountryCode}->states->{$this->locStateCode})) {
                $result->state = $countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->name;
            }

            if ($this->locCityId != null && isset($countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}) && ! empty($countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->cities)) {
                if (isset($countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->cities->{$this->locCityId})) {
                    $result->city = $countriesFile->{$this->locCountryCode}->states->{$this->locStateCode}->cities->{$this->locCityId}->name;
                }
            }
        }

        return $result;
    }

    protected function convertPersonaState(int $personaState): string
    {
        return match ($personaState) {
            0 => '<span class="text-error">Offline</span>',
            1 => '<span class="text-success">Online</span>',
            2 => '<span class="text-warning">Busy</span>',
            3 => '<span class="text-warning">Away</span>',
            4 => '<span class="text-warning">Snooze</span>',
            5 => 'Looking to Trade',
            6 => 'Looking to Play',
            default => 'Unknown',
        };
    }
}
