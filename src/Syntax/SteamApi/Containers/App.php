<?php

namespace Syntax\SteamApi\Containers;

use NukaCode\Database\Collection;

class App extends BaseContainer
{
    public $id;

    public $name;

    public $controllerSupport;

    public $description;

    public $about;

    public $header;

    public $website;

    public $pcRequirements;

    public $legal;

    public $developers;

    public $publishers;

    public $price;

    public $platforms;

    public $metacritic;

    public $categories;

    public $genres;

    public $release;

    public function __construct($app)
    {
        $this->id                = $app->steam_appid;
        $this->name              = $app->name;
        $this->controllerSupport = $this->checkIssetField($app, 'controller_support', 'None');
        $this->description       = $app->detailed_description;
        $this->about             = $app->about_the_game;
        $this->header            = $app->header_image;
        $this->website           = $this->checkIsNullField($app, 'website', 'None');
        $this->pcRequirements    = $app->pc_requirements;
        $this->legal             = $this->checkIssetField($app, 'legal_notice', 'None');
        $this->developers        = $this->checkIssetCollection($app, 'developers');
        $this->publishers        = new Collection($app->publishers);
        $this->price             = $this->checkIssetField($app, 'price_overview', $this->getFakePriceObject());
        $this->platforms         = $app->platforms;
        $this->metacritic        = $this->checkIssetField($app, 'metacritic', $this->getFakeMetacriticObject());
        $this->categories        = $this->checkIssetCollection($app, 'categories');
        $this->genres            = $this->checkIssetCollection($app, 'genres');
        $this->release           = $app->release_date;
    }

    protected function getFakeMetacriticObject()
    {
        $object        = new \stdClass();
        $object->url   = null;
        $object->score = 'No Score';

        return $object;
    }

    protected function getFakePriceObject()
    {
        $object        = new \stdClass();
        $object->final = 'No price found';

        return $object;
    }
}
