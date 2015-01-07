<?php

/** @group Id */
class IdTest extends BaseTester {

    /** @test */
    public function it_converts_an_id()
    {
        $ids = $this->steamClient->convertId($this->id64);

        $this->assertEquals($this->id32, $ids->id32);
        $this->assertEquals($this->id64, $ids->id64);
        $this->assertEquals($this->id3, $ids->id3);
    }

}
