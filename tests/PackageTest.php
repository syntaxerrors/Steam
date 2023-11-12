<?php

require_once('BaseTester.php');

/** @group Package */
class PackageTest extends BaseTester
{
    /** @test */
    public function it_gets_details_for_an_package_by_id()
    {
        $details = $this->steamClient->package()->packageDetails($this->packageId);

        $this->assertCount(1, $details);

        $detail = $details->first();

        $this->checkPackageProperties($detail);
        $this->checkPackageClasses($detail);
    }

    /**
     * @param $detail
     */
    private function checkPackageClasses($detail)
    {
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Package::class, $detail);
    }
}
