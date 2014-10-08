<?php namespace Syntax\SteamApi\Containers;

class Achievement {
	public $apiName;

	public $achieved;

	public $name;

	public $description;

	public function __construct($achievement)
	{
		$this->apiName     = $achievement->apiname;
		$this->achieved    = $achievement->achieved;
		$this->name        = $achievement->name;
		$this->description = $achievement->description;
	}

}