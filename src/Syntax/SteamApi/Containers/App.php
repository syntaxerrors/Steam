<?php

namespace Syntax\SteamApi\Containers;

use Illuminate\Support\Collection;
use stdClass;

class App extends BaseContainer
{
    public $id;

    public $type;

    public $name;

    public $controllerSupport;

    public $description;

    public $about;

    public $fullgame;

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

    public $requiredAge;

    public $isFree;

    public $shortDescription;

    public $supportedLanguages;

    public $recommendations;

    public $achievements;

    public $dlc;
    
    public $movies;

    public function __construct($app)
    {

        $this->id                 = $app->steam_appid;
        $this->type               = $app->type;
        $this->name               = $app->name;
        $this->controllerSupport  = $this->checkIssetField($app, 'controller_support', 'None');
        $this->description        = $app->detailed_description;
        $this->about              = $app->about_the_game;
        $this->fullgame           = $this->checkIssetField($app, 'fullgame', $this->getFakeFullgameObject());
        $this->header             = $app->header_image;
        $this->website            = $this->checkIsNullField($app, 'website', 'None');
        $this->pcRequirements     = $app->pc_requirements;
        $this->legal              = $this->checkIssetField($app, 'legal_notice', 'None');
        $this->developers         = $this->checkIssetCollection($app, 'developers');
        $this->publishers         = new Collection($app->publishers);
        $this->price              = $this->checkIssetField($app, 'price_overview', $this->getFakePriceObject());
        $this->platforms          = $app->platforms;
        $this->metacritic         = $this->checkIssetField($app, 'metacritic', $this->getFakeMetacriticObject());
        $this->categories         = $this->checkIssetCollection($app, 'categories');
        $this->genres             = $this->checkIssetCollection($app, 'genres');
        $this->release            = $app->release_date;
        $this->requiredAge        = (int)$app->required_age;
        $this->isFree             = $app->is_free;
        $this->shortDescription   = $app->short_description;
        $this->supportedLanguages = $this->checkIssetField($app, 'supported_languages', 'None');
        $this->recommendations    = $this->checkIssetField($app, 'recommendations', $this->getFakeRecommendationsObject());
        $this->achievements       = $this->checkIssetField($app, 'achievements', $this->getFakeAchievementsObject());
        $this->dlc                = $this->checkIssetCollection($app, 'dlc', new Collection());
        $this->movies             = $this->checkIssetCollection($app, 'movies', new Collection());

    }

    protected function getFakeMetacriticObject(): stdClass
    {
        $object        = new stdClass();
        $object->url   = null;
        $object->score = 'No Score';
        return $object;
    }

    protected function getFakePriceObject(): stdClass
    {
        $object        = new stdClass();
        $object->final = 'No price found';
        return $object;
    }

    protected function getFakeFullgameObject(): stdClass
    {
        $object        = new stdClass();
        $object->appid = null;
        $object->name  = 'No parent game found';
        return $object;
    }

    protected function getFakeRecommendationsObject(): stdClass
    {
        $object        = new stdClass();
        $object->total = 0;
        return $object;
    }

    protected function getFakeAchievementsObject(): stdClass
    {
        $object        = new stdClass();
        $object->total = 0;
        return $object;
    }
}
