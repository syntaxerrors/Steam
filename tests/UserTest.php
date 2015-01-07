<?php

/** @group User */
class UserTest extends BaseTester {

    /** @test */
    public function it_gets_all_users_in_friend_list()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetFriendList('all');

        $this->assertGreaterThan(0, $friendsList);

        $this->checkMainProperties($friendsList);
        $this->checkSteamIdsProperties($friendsList[0]->steamIds);
        $this->checkPlayerClasses($friendsList);
    }

    /** @test */
    public function it_gets_friend_users_in_friend_list()
    {
        $friendsList = $this->steamClient->user($this->id64)->GetFriendList('friend');

        $this->assertGreaterThan(0, $friendsList);

        $this->checkMainProperties($friendsList);
        $this->checkSteamIdsProperties($friendsList[0]->steamIds);
        $this->checkPlayerClasses($friendsList);
    }

    /** @test */
    public function it_throws_exception_to_invalid_relationship_types()
    {
        $this->setExpectedException('InvalidArgumentException', 'Provided relationship [nonFriend] is not valid.  Please select from: all, friend');

        $this->steamClient->user($this->id64)->GetFriendList('nonFriend');
    }

    private function checkMainProperties($friendsList)
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
            'locCountryCode', 'locStateCode', 'locCityId'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);
    }

    private function checkPlayerClasses($friendsList)
    {
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Player', $friendsList[0]);
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Id', $friendsList[0]->steamIds);
    }
}