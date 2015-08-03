<?php namespace Syntax\SteamApi\Containers;

class Achievement
{
    public $apiName;

    public $achieved;

    public $name;

    public $description;

    public $icon;

    public $iconGray;

    public function __construct($achievement, $stats)
    {
        $this->apiName     = $achievement->apiname;
        $this->achieved    = $achievement->achieved;
        $this->name        = $achievement->name;
        $this->description = $achievement->description;

        $stat = array_where($stats, function ($key, $value) use ($achievement) {
            return $value->name == $achievement->apiname;
        });

        if (count($stat) > 0) {
            $stat = array_shift($stat);

            $this->icon     = $stat->icon;
            $this->iconGray = $stat->icongray;
        }
    }

}