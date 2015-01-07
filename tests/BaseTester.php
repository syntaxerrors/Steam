<?php

use Orchestra\Testbench\TestCase;
use Syntax\SteamApi\Client;

class BaseTester extends TestCase {

    protected $id32      = 'STEAM_1:1:9846342';

    protected $id64      = 76561197979958413;

    protected $id3       = '[U:1:19692685]';

    protected $appId     = 620;

    protected $groupId   = 103582791429521412;

    protected $groupName = 'Valve';

    protected $steamClient;

    public function setUp()
    {
        parent::setUp();
        $this->steamClient = new Client();
    }

    /** @test */
    public function empty_test()
    {
    }

}