<?php

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
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group', $group);
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group\Details', $group->groupDetails);
        $this->assertInstanceOf('Syntax\SteamApi\Containers\Group\MemberDetails', $group->memberDetails);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $group->members);
    }

}
