<?php

namespace Syntax\SteamApi\Containers;

class Package extends BaseContainer
{
    public $id;
    public $name;
    public $page_image;
    public $header;
    public $small_logo;
    public $apps;
    public $page_content;
    public $price;
    public $platforms;
    public $release;
    private $controller;

    public function __construct($package, $id)
    {
        $this->id = (int) $id;
        $this->name = $package->name;
        $this->apps = $package->apps;
        $this->page_content = $this->checkIssetField($package, 'page_content', 'none');
        $this->header = $this->checkIssetField($package, 'header_image', 'none');
        $this->small_logo = $this->checkIssetField($package, 'small_logo', 'none');
        $this->page_image = $this->checkIssetField($package, 'page_image', 'none');
        $this->price = $this->checkIssetField($package, 'price', $this->getFakePriceObject());
        $this->platforms = $package->platforms;
        $this->controller = $package->controller;
        $this->release = $package->release_date;
    }

    protected function getFakePriceObject(): \stdClass
    {
        $object        = new \stdClass();
        $object->final = 'No price found';
        return $object;
    }
}
