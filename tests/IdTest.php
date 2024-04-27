<?php

use Syntax\SteamApi\Exceptions\UnrecognizedId;

require_once('BaseTester.php');

/** @group Id */
class IdTest extends BaseTester {

    /** @test
     * @throws UnrecognizedId
     */
    public function it_converts_an_id()
    {
        $ids = $this->steamClient->convertId(76561198022436617);

        $this->assertEquals('STEAM_1:1:31085444', $ids->id32);
        $this->assertEquals(76561198022436617, $ids->id64);
        $this->assertEquals('[U:1:62170889]', $ids->id3);
    }

}
