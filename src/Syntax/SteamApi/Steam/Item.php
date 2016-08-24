<?php

namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use NukaCode\Database\Collection;
use Syntax\SteamApi\Containers\Item as ItemContainer;

class Item extends Client
{
    public function __construct()
    {
        parent::__construct();
        $this->url       = 'http://store.steampowered.com/';
        $this->isService = true;
        $this->interface = 'api';
    }

    public function GetPlayerItems($appId, $steamId)
    {
        // Set up the api details
        $this->url       = 'http://api.steampowered.com/';
        $this->interface = 'IEconItems_' . $appId;
        $this->method    = __FUNCTION__;
        $this->version   = 'v0001';

        $arguments = ['steamId' => $steamId];

        // Get the client
        $client = $this->setUpClient($arguments);
        dd($client);

        // Clean up the items
        $items = $this->convertToObjects($client->result->items, $client->result->qualities);

        return $items;
    }

    protected function convertToObjects($items, $qualities)
    {
        $convertedItems = $this->convertGames($items, $qualities);

        $items = $this->sortObjects($convertedItems);

        return $items;
    }

    /**
     * @param array $items
     *
     * @return Collection
     */
    protected function convertItems($items, $qualities)
    {
        $convertedItems = new Collection();

        foreach ($items as $item) {
            if (isset($item->data)) {
                $convertedItems->add(new ItemContainer($item->data, $qualities));
            }
        }

        return $convertedItems;
    }
}
