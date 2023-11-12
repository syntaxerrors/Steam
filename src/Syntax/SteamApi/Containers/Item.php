<?php

namespace Syntax\SteamApi\Containers;

class Item extends BaseContainer
{
    public $id;

    public $originalId;

    public $defIndex;

    public $level;

    public $quality;

    public $quantity;

    public $inventory;

    public $origin;

    public $flags;

    public $containedItem;

    public $style;

    public $attributes;

    public $custom;

    public function __construct($item)
    {
        $this->id            = $item->id;
        $this->originalId    = $item->original_id;
        $this->defIndex      = $item->defindex;
        $this->level         = $item->level;
        $this->quality       = $item->quality;
        $this->quantity      = $item->quantity;
        $this->inventory     = $item->inventory;
        $this->origin        = $this->checkIssetField($item, 'origin');
        $this->containedItem = $this->checkIssetField($item, 'contained_item');
        $this->style         = $this->checkIssetField($item, 'style');
        $this->attributes    = $this->checkIssetField($item, 'attributes');

        $this->flags  = [
            'trade' => ! $this->checkIssetField($item, 'flag_cannot_trade', false),
            'craft' => ! $this->checkIssetField($item, 'flag_cannot_craft', false),
        ];
        $this->custom = [
            'name'        => $this->checkIssetField($item, 'custom_name'),
            'description' => $this->checkIssetField($item, 'custom_desc'),
        ];
    }
}
