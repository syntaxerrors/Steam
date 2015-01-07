<?php

require_once('BaseTester.php');

/** @group App */
class AppTest extends BaseTester {

    /** @test */
    public function it_gets_details_for_an_app_by_id()
    {
        $details = $this->steamClient->app()->appDetails($this->appId);

        $this->assertCount(1, $details);

        $detail = $details->first();

        $this->checkMainProperties($detail);
        $this->checkGeneralProperties($detail);
        $this->checkNestedProperties($detail);
        $this->checkClasses($detail);
    }

    /** @test */
    public function it_gets_a_list_of_all_apps()
    {
        $apps = $this->steamClient->app()->GetAppList();

        $this->assertGreaterThan(0, $apps);
        $this->assertObjectHasAttributes(['appid', 'name'], $apps[0]);
    }

    /**
     * @param $detail
     */
    private function checkMainProperties($detail)
    {
        $attributes = [
            'id', 'name', 'controllerSupport', 'description', 'about', 'header', 'website'
        ];
        $this->assertObjectHasAttributes($attributes, $detail);
    }

    /**
     * @param $detail
     */
    private function checkGeneralProperties($detail)
    {
        $attributes = [
            'pcRequirements', 'legal', 'developers', 'publishers', 'price', 'platforms', 'metacritic', 'categories', 'genres', 'release'
        ];
        $this->assertObjectHasAttributes($attributes, $detail);
    }

    /**
     * @param $detail
     */
    private function checkNestedProperties($detail)
    {
        $this->assertObjectHasAttribute('minimum', $detail->pcRequirements);

        $attributes = ['currency', 'initial', 'final', 'discount_percent'];
        $this->assertObjectHasAttributes($attributes, $detail->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasAttributes($attributes, $detail->platforms);

        $attributes = ['score', 'url'];
        $this->assertObjectHasAttributes($attributes, $detail->metacritic);
    }

    /**
     * @param $detail
     */
    private function checkClasses($detail)
    {
        $this->assertInstanceOf('Syntax\SteamApi\Containers\App', $detail);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $detail->developers);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $detail->publishers);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $detail->categories);
        $this->assertInstanceOf('Syntax\SteamApi\Collection', $detail->genres);
    }
}