<?php

namespace Syntax\SteamApi\Containers;

use SimpleXMLElement;
use Syntax\SteamApi\Client;
use Illuminate\Support\Collection;
use Syntax\SteamApi\Containers\Group\Details;
use Syntax\SteamApi\Containers\Group\MemberDetails;

class Group
{
    public string $groupID64;

    public Details $groupDetails;

    public MemberDetails $memberDetails;

    public int $startingMember;

    public Collection $members;

    /**
     * @param SimpleXMLElement $group
     */
    function __construct(SimpleXMLElement $group)
    {
        $this->groupID64      = (string)$group->groupID64;
        $this->groupDetails   = new Details($group->groupDetails);
        $this->memberDetails  = new MemberDetails($group->groupDetails);
        $this->startingMember = (int)(string)$group->startingMember;

        $this->members = new Collection;

        foreach ($group->members->steamID64 as $member) {
            $this->members->add((new Client)->convertId((string)$member));
        }
    }
}
