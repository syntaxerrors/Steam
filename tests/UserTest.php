<?php

use Syntax\SteamApi\Exceptions\UnrecognizedId;

require_once('BaseTester.php');

/** @group User */
class UserTest extends BaseTester {

    /** @test */
    public function it_accepts_an_array_of_steam_ids()
    {
        $steamIds = [$this->id32, $this->altId64];

        $userService = $this->steamClient->user($steamIds);

        $this->assertCount(2, $userService->getSteamId());
        $this->assertEquals($this->id64, $userService->getSteamId()[0]);
    }

    /**
     * @test
     * @throws UnrecognizedId
     */
    public function it_throws_an_exception_when_no_display_name_is_provided()
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(\Syntax\SteamApi\Exceptions\UnrecognizedId::class);
        } else {
            $this->expectException(\Syntax\SteamApi\Exceptions\UnrecognizedId::class);
        }
        
        $steamObject = $this->steamClient->user($this->id64)->ResolveVanityURL();

        $this->assertEquals('No match', $steamObject);
    }

    /** @test
     * @throws UnrecognizedId
     */
    public function it_returns_no_match_from_an_invalid_display_name()
    {
        $steamObject = $this->steamClient->user($this->id64)->ResolveVanityURL('stygiansabyssINVALID');

        $this->assertEquals('No match', $steamObject);
    }

    /** @test
     * @throws UnrecognizedId
     */
    public function it_gets_the_steam_id_from_a_display_name()
    {
        $steamObject = $this->steamClient->user($this->id64)->ResolveVanityURL('stygiansabyss');

        $this->assertEquals($this->id64, $steamObject->id64);
    }

    /** @test */
    public function it_gets_the_base_users_player_summary()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetPlayerSummaries();

        $this->assertCount(1, $friendsList);
        $this->checkPlayerProperties($friendsList);
        $this->checkPlayerClasses($friendsList);
    }

    /** @test */
    public function it_gets_the_supplied_users_player_summary()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetPlayerSummaries($this->altId64);

        $this->assertCount(1, $friendsList);
        $this->checkPlayerProperties($friendsList);
        $this->checkPlayerClasses($friendsList);

        $this->assertNotEquals($friendsList[0]->steamId, $this->id64);
    }

    /** @test */
    public function it_gets_all_users_in_friend_list()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetFriendList('all');

        $this->assertGreaterThan(0, $friendsList);

        $this->checkPlayerProperties($friendsList);
        $this->checkPlayerClasses($friendsList);
    }

    /** @test */
    public function it_gets_friend_users_in_friend_list()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetFriendList('friend');

        $this->assertGreaterThan(0, $friendsList);

        $this->checkPlayerProperties($friendsList);
        $this->checkPlayerClasses($friendsList);
    }

    /** @test */
    public function it_throws_exception_to_invalid_relationship_types()
    {
        $expectedMessage = 'Provided relationship [nonFriend] is not valid.  Please select from: all, friend';
        
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException('InvalidArgumentException', $expectedMessage);
        } else {
            $this->expectException('InvalidArgumentException');
            $this->expectExceptionMessage($expectedMessage);
        }

        $this->steamClient->user($this->id64)->GetFriendList('nonFriend');
    }

    /** @test */
    public function it_gets_the_bans_for_the_base_user()
    {
        $bans = $this->steamClient->user($this->id64)->GetPlayerBans();

        $this->assertCount(1, $bans);

        $attributes = ['SteamId', 'CommunityBanned', 'VACBanned', 'NumberOfVACBans', 'DaysSinceLastBan', 'EconomyBan'];
        $this->assertObjectHasProperties($attributes, $bans[0]);
    }

    /** @test */
    public function it_gets_the_bans_for_the_supplied_user()
    {
        $bans = $this->steamClient->user($this->id64)->GetPlayerBans($this->altId64);

        $this->assertCount(1, $bans);

        $attributes = ['SteamId', 'CommunityBanned', 'VACBanned', 'NumberOfVACBans', 'DaysSinceLastBan', 'EconomyBan'];
        $this->assertObjectHasProperties($attributes, $bans[0]);

        $this->assertNotEquals($bans[0]->SteamId, $this->id64);
    }

    private function checkPlayerClasses($friendsList)
    {
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Player::class, $friendsList[0]);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Id::class, $friendsList[0]->steamIds);
    }
}
