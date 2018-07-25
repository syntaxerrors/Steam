<?php

use Orchestra\Testbench\TestCase;
use Syntax\SteamApi\Client;

class BaseTester extends TestCase {

    protected $id32      = 'STEAM_1:1:31085444';

    protected $id64      = 76561198022436617;

    protected $id3       = '[U:1:62170889]';

    protected $altId64   = 76561197979958413;

    protected $appId     = 620;

    protected $packageId = 76710;

    protected $itemid = 440;

    protected $groupId   = 103582791429521412;

    protected $groupName = 'Valve';

    protected $steamClient;

    public function setUp()
    {
        parent::setUp();
        $this->steamClient = new Client();
    }

    /** @test */
    public function empty_test()
    {
    }

    protected function assertObjectHasAttributes($attributes, $object)
    {
        foreach ($attributes as $attribute) {
            $this->assertObjectHasAttribute($attribute, $object);
        }
    }

    protected function checkSteamIdsProperties($steamId)
    {
        $attributes = [
            'id32', 'id64', 'id3', 'communityId', 'steamId'
        ];
        $this->assertObjectHasAttributes($attributes, $steamId);
    }

    protected function checkPlayerProperties($friendsList)
    {
        $attributes = [
            'steamId', 'steamIds', 'communityVisibilityState', 'profileState', 'lastLogoff', 'profileUrl', 'realName', 'primaryClanId', 'timecreated'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'avatar', 'avatarMedium', 'avatarFull', 'avatarUrl', 'avatarMediumUrl', 'avatarFullUrl',
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'personaName', 'personaState', 'personaStateId', 'personaStateFlags'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'locCountryCode', 'locStateCode', 'locCityId', 'location'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $this->checkSteamIdsProperties($friendsList[0]->steamIds);
    }

    protected function checkAchievementProperties($achievement)
    {
        $attributes = [
            'apiName', 'achieved', 'name', 'description'
        ];
        $this->assertObjectHasAttributes($attributes, $achievement);
    }

    protected function checkAppProperties($app)
    {
        $this->checkMainAppProperties($app);
        $this->checkGeneralAppProperties($app);
        $this->checkNestedAppProperties($app);
    }

    protected function checkPackageProperties($package)
    {
        $this->checkNestedPackageProperties($package);
    }

    protected function checkGroupProperties($group)
    {
        $this->checkGroupMainSummaryProperties($group);
        $this->checkGroupDetailProperties($group);
        $this->checkGroupMemberDetailsProperties($group);
        $this->checkGroupMemberProperties($group);
    }

    /**
     * @param $app
     */
    private function checkMainAppProperties($app)
    {
        $attributes = [
            'id', 'type', 'name', 'controllerSupport', 'description', 'about', 'fullgame', 'header', 'website'
        ];
        $this->assertObjectHasAttributes($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkGeneralAppProperties($app)
    {
        $attributes = [
            'pcRequirements', 'legal', 'developers', 'publishers', 'price', 'platforms', 'metacritic', 'categories', 'genres', 'release'
        ];
        $this->assertObjectHasAttributes($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkNestedAppProperties($app)
    {
        $this->assertObjectHasAttribute('minimum', $app->pcRequirements);

        $attributes = ['currency', 'initial', 'final', 'discount_percent'];
        $this->assertObjectHasAttributes($attributes, $app->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasAttributes($attributes, $app->platforms);

        $attributes = ['score', 'url'];
        $this->assertObjectHasAttributes($attributes, $app->metacritic);
    }

    /**
     * @param $packahe
     */
    private function checkNestedPackageProperties($packahe)
    {
        $attributes = ['currency', 'initial', 'final', 'discount_percent', 'individual'];
        $this->assertObjectHasAttributes($attributes, $packahe->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasAttributes($attributes, $packahe->platforms);
    }

    /**
     * @param $item
     */
    private function checkItemProperties($item)
    {
        $attributes = ['id', 'originalId', 'level', 'quality', 'quantity'];
        $this->assertObjectHasAttributes($attributes, $item->item);
    }

    /**
     * @param $group
     */
    private function checkGroupMainSummaryProperties($group)
    {
        $this->assertObjectHasAttribute('groupID64', $group);
        $this->assertObjectHasAttribute('groupDetails', $group);
        $this->assertObjectHasAttribute('memberDetails', $group);
        $this->assertObjectHasAttribute('startingMember', $group);
        $this->assertObjectHasAttribute('members', $group);
    }

    /**
     * @param $group
     */
    private function checkGroupDetailProperties($group)
    {
        $this->assertObjectHasAttribute('name', $group->groupDetails);
        $this->assertObjectHasAttribute('url', $group->groupDetails);
        $this->assertObjectHasAttribute('headline', $group->groupDetails);
        $this->assertObjectHasAttribute('summary', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarIcon', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarMedium', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarFull', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarIconUrl', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarMediumUrl', $group->groupDetails);
        $this->assertObjectHasAttribute('avatarFullUrl', $group->groupDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberDetailsProperties($group)
    {
        $this->assertObjectHasAttribute('count', $group->memberDetails);
        $this->assertObjectHasAttribute('inChat', $group->memberDetails);
        $this->assertObjectHasAttribute('inGame', $group->memberDetails);
        $this->assertObjectHasAttribute('online', $group->memberDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberProperties($group)
    {
        $startingMember = $group->members->get($group->startingMember);

        $this->assertObjectHasAttribute('id32', $startingMember);
        $this->assertObjectHasAttribute('id64', $startingMember);
        $this->assertObjectHasAttribute('id3', $startingMember);
    }

}