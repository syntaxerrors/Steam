<?php

require_once('BaseTester.php');

use PHPUnit\Framework\Attributes\Test;

/** @group Item */
class ItemTest extends BaseTester
{
    #[Test]
    public function it_gets_items_for_an_app_by_user_id()
    {
        $inventory = $this->steamClient->item()->GetPlayerItems($this->appId, 76561198022436617);

        $this->assertCount(3, $inventory->items);

        $item = $inventory->items->first();

        $this->checkItemProperties($item);
    }
}