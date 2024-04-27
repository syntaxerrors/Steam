<?php

use Orchestra\Testbench\TestCase;
use Syntax\SteamApi\Client;
use Dotenv\Dotenv;

class BaseTester extends TestCase {

    protected string $id32   = 'STEAM_1:1:31085444';

    protected int $id64      = 76561198022436617;

    protected string $id3    = '[U:1:62170889]';

    protected int $altId64   = 76561197979958413;

    protected int $appId     = 620; // Portal 2

    protected int $packageId = 469; // The Orange Box

    protected int $itemAppId = 5021; // Mann Co. Supply Crate Key

    protected int $groupId   = 103582791429521412; // Valve

    protected string $groupName = 'Valve';

    protected Client $steamClient;

    protected function setUp(): void
    {
        parent::setUp();

        $root = dirname(__DIR__, 1);

        // Use .env when available
        if (file_exists($root . '/.env')) {
            $dotenv = Dotenv::createUnsafeImmutable($root);
            $dotenv->load();
        }

        $this->steamClient = new Client();
    }

    /** @test */
    public function empty_test()
    {
        $this->assertTrue(true);
    }

    protected function assertObjectHasProperties($attributes, $object): void
    {
        foreach ($attributes as $attribute) {
            $this->assertObjectHasProperty($attribute, $object);
        }
    }

    protected function checkSteamIdsProperties($steamId): void
    {
        $attributes = [
            'id32', 'id64', 'id3', 'communityId', 'steamId'
        ];
        $this->assertObjectHasProperties($attributes, $steamId);
    }

    protected function checkPlayerProperties($friendsList): void
    {
        $attributes = [
            'steamId', 'steamIds', 'communityVisibilityState', 'profileState', 'lastLogoff', 'profileUrl', 'realName', 'primaryClanId', 'timecreated'
        ];
        $this->assertObjectHasProperties($attributes, $friendsList[0]);

        $attributes = [
            'avatar', 'avatarMedium', 'avatarFull', 'avatarUrl', 'avatarMediumUrl', 'avatarFullUrl',
        ];
        $this->assertObjectHasProperties($attributes, $friendsList[0]);

        $attributes = [
            'personaName', 'personaState', 'personaStateId', 'personaStateFlags'
        ];
        $this->assertObjectHasProperties($attributes, $friendsList[0]);

        $attributes = [
            'locCountryCode', 'locStateCode', 'locCityId', 'location'
        ];
        $this->assertObjectHasProperties($attributes, $friendsList[0]);

        $this->checkSteamIdsProperties($friendsList[0]->steamIds);
    }

    protected function checkAchievementProperties($achievement): void
    {
        $attributes = [
            'apiName', 'achieved', 'name', 'description'
        ];
        $this->assertObjectHasProperties($attributes, $achievement);
    }

    protected function checkAppProperties($app): void
    {
        $this->checkMainAppProperties($app);
        $this->checkGeneralAppProperties($app);
        $this->checkNestedAppProperties($app);
    }

    protected function checkPackageProperties($package): void
    {
        $this->checkNestedPackageProperties($package);
    }

    protected function checkGroupProperties($group): void
    {
        $this->checkGroupMainSummaryProperties($group);
        $this->checkGroupDetailProperties($group);
        $this->checkGroupMemberDetailsProperties($group);
        $this->checkGroupMemberProperties($group);
    }

    /**
     * @param $item
     */
    protected function checkItemProperties($item): void
    {
        $attributes = ['id', 'originalId', 'level', 'quality', 'quantity'];
        $this->assertObjectHasProperties($attributes, $item);
    }

    /**
     * @param $app
     */
    private function checkMainAppProperties($app): void
    {
        $attributes = [
            'id', 'type', 'name', 'controllerSupport', 'description', 'about', 'fullgame', 'header', 'website', 'shortDescription'
        ];
        $this->assertObjectHasProperties($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkGeneralAppProperties($app): void
    {
        $attributes = [
            'pcRequirements', 'legal', 'developers', 'publishers', 'price', 'platforms', 'metacritic', 'categories', 'genres', 'release', 'requiredAge', 'isFree', 'supportedLanguages', 'recommendations'
        ];
        $this->assertObjectHasProperties($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkNestedAppProperties($app): void
    {
        $this->assertObjectHasProperty('minimum', $app->pcRequirements);

        $attributes = ['currency', 'initial', 'final', 'discount_percent'];
        $this->assertObjectHasProperties($attributes, $app->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasProperties($attributes, $app->platforms);

        $attributes = ['score', 'url'];
        $this->assertObjectHasProperties($attributes, $app->metacritic);

        $attributes = ['total'];
        $this->assertObjectHasProperties($attributes, $app->recommendations);

        $attributes = ['total'];
        $this->assertObjectHasProperties($attributes, $app->achievements);
    }

    /**
     * @param $package
     */
    private function checkNestedPackageProperties($package): void
    {
        $attributes = ['currency', 'initial', 'final', 'discount_percent', 'individual'];
        $this->assertObjectHasProperties($attributes, $package->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasProperties($attributes, $package->platforms);
    }

    /**
     * @param $group
     */
    private function checkGroupMainSummaryProperties($group): void
    {
        $this->assertObjectHasProperty('groupID64', $group);
        $this->assertObjectHasProperty('groupDetails', $group);
        $this->assertObjectHasProperty('memberDetails', $group);
        $this->assertObjectHasProperty('startingMember', $group);
        $this->assertObjectHasProperty('members', $group);
    }

    /**
     * @param $group
     */
    private function checkGroupDetailProperties($group): void
    {
        $this->assertObjectHasProperty('name', $group->groupDetails);
        $this->assertObjectHasProperty('url', $group->groupDetails);
        $this->assertObjectHasProperty('headline', $group->groupDetails);
        $this->assertObjectHasProperty('summary', $group->groupDetails);
        $this->assertObjectHasProperty('avatarIcon', $group->groupDetails);
        $this->assertObjectHasProperty('avatarMedium', $group->groupDetails);
        $this->assertObjectHasProperty('avatarFull', $group->groupDetails);
        $this->assertObjectHasProperty('avatarIconUrl', $group->groupDetails);
        $this->assertObjectHasProperty('avatarMediumUrl', $group->groupDetails);
        $this->assertObjectHasProperty('avatarFullUrl', $group->groupDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberDetailsProperties($group): void
    {
        $this->assertObjectHasProperty('count', $group->memberDetails);
        $this->assertObjectHasProperty('inChat', $group->memberDetails);
        $this->assertObjectHasProperty('inGame', $group->memberDetails);
        $this->assertObjectHasProperty('online', $group->memberDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberProperties($group): void
    {
        $startingMember = $group->members->get($group->startingMember);

        $this->assertObjectHasProperty('id32', $startingMember);
        $this->assertObjectHasProperty('id64', $startingMember);
        $this->assertObjectHasProperty('id3', $startingMember);
    }

    protected function expectApiCallFailedException(string $message): void
    {
        $this->expectException(\Syntax\SteamApi\Exceptions\ApiCallFailedException::class);
        $this->expectExceptionMessage($message);
    }

    protected function expectEmptyResponseException(): void
    {
        $this->expectApiCallFailedException( 'Api call failed to complete due to an empty response');
    }

}
