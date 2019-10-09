<?php

require_once 'BaseTester.php';

/** @group Item */
class ItemTest extends BaseTester
{
    /** @test */
    public function it_gets_items_for_an_by_user_id()
    {
        $items = $this->steamClient->item()->GetPlayerItems($this->itemid, $this->id64);

        $this->assertCount(1, $items);

        $item = $items->first();

        $this->checkItemProperties($item);
    }
}
