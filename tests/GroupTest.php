<?php

/** @group Group */
class GroupTest extends BaseTester {

    /** @test */
    public function it_gets_a_summary_of_a_group_by_id()
    {
        $group = $this->steamClient->group()->GetGroupSummary($this->groupId);

        // Check main properties
        $this->assertObjectHasAttribute('groupID64', $group);
        $this->assertObjectHasAttribute('groupDetails', $group);
        $this->assertObjectHasAttribute('memberDetails', $group);
        $this->assertObjectHasAttribute('startingMember', $group);
        $this->assertObjectHasAttribute('members', $group);

        // Check group details properties
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

        // Check group group member properties
        $this->assertObjectHasAttribute('count', $group->memberDetails);
        $this->assertObjectHasAttribute('inChat', $group->memberDetails);
        $this->assertObjectHasAttribute('inGame', $group->memberDetails);
        $this->assertObjectHasAttribute('online', $group->memberDetails);

        // Check member properties
        $startingMember = $group->members->get($group->startingMember);

        $this->assertObjectHasAttribute('id32', $startingMember);
        $this->assertObjectHasAttribute('id64', $startingMember);
        $this->assertObjectHasAttribute('id3', $startingMember);

        // Check classes
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group', $group);
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group\Details', $group->groupDetails);
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group\MemberDetails', $group->memberDetails);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $group->members);
    }

}
