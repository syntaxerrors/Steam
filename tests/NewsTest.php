<?php

class NewsTest extends BaseTester {

    /** @test */
    public function it_gets_news_by_app_id()
    {
        $newsArticle = $this->steamClient->news()->GetNewsForApp($this->appId, 1, 20);

        $this->assertObjectHasAttribute('appid', $newsArticle);
        $this->assertEquals($this->appId, $newsArticle->appid);
        $this->assertObjectHasAttribute('newsitems', $newsArticle);
        $this->assertGreaterThan(0, count($newsArticle->newsitems));

        $this->assertObjectHasAttribute('gid', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('title', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('url', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('is_external_url', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('author', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('contents', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('feedlabel', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('date', $newsArticle->newsitems[0]);
        $this->assertObjectHasAttribute('feedname', $newsArticle->newsitems[0]);

        $this->assertEquals(23, strlen(strip_tags($newsArticle->newsitems[0]->contents)));
    }

    /** @test */
    public function it_gets_more_than_1_news_article_by_app_id()
    {
        $newsArticle = $this->steamClient->news()->GetNewsForApp($this->appId);

        $this->assertGreaterThan(1, count($newsArticle->newsitems));

        return $newsArticle;
    }

    /**
     * @test
     *
     * @depends it_gets_more_than_1_news_article_by_app_id
     *
     * @param $defaultNewsCall
     */
    public function it_has_full_news_article_by_app_id($defaultNewsCall)
    {
        $this->assertGreaterThan(23, strlen(strip_tags($defaultNewsCall->newsitems[0]->contents)));
    }

}
