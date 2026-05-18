<?php

require_once('BaseTester.php');

use PHPUnit\Framework\Attributes\Test;

/** @group Package */
class PackageTest extends BaseTester
{
    #[Test]
    public function it_gets_details_for_an_package_by_id()
    {
        $details = $this->steamClient->package()->packageDetails($this->packageId);

        $this->assertCount(1, $details);

        $detail = $details->first();

        $this->checkPackageProperties($detail);
        $this->checkPackageClasses($detail);
    }

    private function checkPackageClasses($detail): void
    {
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Package::class, $detail);
    }
}