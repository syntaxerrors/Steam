<?php

namespace Syntax\SteamApi\Steam;

use GuzzleHttp\Exception\GuzzleException;
use Syntax\SteamApi\Client;
use Illuminate\Support\Collection;
use Syntax\SteamApi\Containers\Item as ItemContainer;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Inventory;

class Item extends Client
{
    public function __construct()
    {
        parent::__construct();
        $this->url       = 'http://store.steampowered.com/';
        $this->isService = true;
        $this->interface = 'api';
    }

    /**
     * @throws ApiCallFailedException
     * @throws GuzzleException
     */
    public function GetPlayerItems($appId, $steamId): Inventory
    {
        // Set up the api details
        $this->url       = 'http://api.steampowered.com/';
        $this->interface = 'IEconItems_' . $appId;
        $this->method    = __FUNCTION__;
        $this->version   = 'v0001';

        $arguments = ['steamId' => $steamId];

        $client = $this->setUpClient($arguments);

        // Clean up the items
        $items = $this->convertToObjects($client->result->items);

        // Return a full inventory
        return new Inventory($client->result->num_backpack_slots, $items);
    }

    protected function convertToObjects($items): Collection
    {
        return $this->convertItems($items);
    }

    /**
     * @param array $items
     *
     * @return Collection
     */
    protected function convertItems(array $items): Collection
    {
        $convertedItems = new Collection();

        foreach ($items as $item) {
            $convertedItems->add(new ItemContainer($item));
        }

        return $convertedItems;
    }
}
