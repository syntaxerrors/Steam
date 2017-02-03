<?php

namespace Syntax\SteamApi;

class Inventory
{
    public $numberOfBackpackSlots;

    public $items;

    public function __construct($slots, $items)
    {
        $this->numberOfBackpackSlots = $slots;
        $this->items                 = $items;
    }
}
