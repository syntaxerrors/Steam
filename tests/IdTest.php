<?php

use Mockery as m;
use Orchestra\Testbench\TestCase;
use Syntax\SteamApi\Client;

class IdTest extends TestCase {

    protected $id32 = 'STEAM_1:1:9846342';

    protected $id64 = 76561197979958413;

    protected $id3  = '[U:1:19692685]';

    private $steamClient;

    public function setUp()
    {
        parent::setUp();
        $this->steamClient = new Client();
    }


    /** @test */
    public function it_converts_id64_to_id32()
    {
        $id32 = $this->steamClient->convertId($this->id64, 'ID32');

        $this->assertEquals($this->id32, $id32);
    }

}
