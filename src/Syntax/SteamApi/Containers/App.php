<?php namespace Syntax\SteamApi\Containers;

use Syntax\SteamApi\Collection;

class App {
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
		$this->id                 = $app->steam_appid;
		$this->name               = $app->name;
		$this->controllerSupport  = isset($app->controller_support) ? $app->controller_support : 'None';
		$this->description        = $app->detailed_description;
		$this->about              = $app->about_the_game;
		$this->header             = $app->header_image;
		$this->website            = !is_null($app->website) ? $app->website : 'None';
		$this->pcRequirements     = $app->pc_requirements;
		$this->legal              = isset($app->legal_notice) ? $app->legal_notice : 'None';
		$this->developers         = isset($app->developers) ? new Collection($app->developers) : null;
		$this->publishers         = new Collection($app->publishers);
		$this->price              = isset($app->price_overview) ? $app->price_overview : $this->getFakePriceObject();
		$this->platforms          = $app->platforms;
		$this->metacritic         = isset($app->metacritic) ? $app->metacritic : $this->getFakeMetacriticObject();
		$this->categories         = isset($app->categories) ? new Collection($app->categories) : null;
		$this->genres             = isset($app->genres) ? new Collection($app->genres) : null;
		$this->release            = $app->release_date;
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