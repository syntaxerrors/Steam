<?php

namespace Syntax\SteamApi;

class Inventory
{
    public function __construct(public $numberOfBackpackSlots, public $items)
    {
    }
}
