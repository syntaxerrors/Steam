<?php

require_once('BaseTester.php');

/** @group Group */
class GroupTest extends BaseTester {

    /** @test */
    public function it_gets_a_summary_of_a_group_by_id()
    {
        $group = $this->steamClient->group()->GetGroupSummary($this->groupId);

        $this->checkGroupProperties($group);
        $this->checkClasses($group);
    }

    /** @test */
    public function it_gets_a_summary_of_a_group_by_name()
    {
        $group = $this->steamClient->group()->GetGroupSummary($this->groupName);

        $this->checkGroupProperties($group);
        $this->checkClasses($group);
    }

    /**
     * @param $group
     */
    protected function checkClasses($group)
    {
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Group::class, $group);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Group\Details::class, $group->groupDetails);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Group\MemberDetails::class, $group->memberDetails);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $group->members);
    }

}
