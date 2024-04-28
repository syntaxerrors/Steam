<?php

require_once('BaseTester.php');

/** @group News */
class NewsTest extends BaseTester {

    /** @test */
    public function it_gets_news_by_app_id()
    {
        $newsArticle = $this->steamClient->news()->GetNewsForApp($this->appId, 1, 20);

        $this->assertObjectHasProperty('appid', $newsArticle);
        $this->assertEquals($this->appId, $newsArticle->appid);
        $this->assertObjectHasProperty('newsitems', $newsArticle);
        $this->assertGreaterThan(0, count($newsArticle->newsitems));

        $attributes = [
            'gid', 'title', 'url', 'is_external_url', 'author', 'contents', 'feedlabel', 'date', 'feedname'
        ];
        $this->assertObjectHasProperties($attributes, $newsArticle->newsitems[0]);

        $this->assertTrue(strlen(strip_tags((string) $newsArticle->newsitems[0]->contents)) <= 23);
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
        foreach ($defaultNewsCall->newsitems as $newsItem) {
            if (strlen(strip_tags((string) $newsItem->contents)) > 0) {
                $this->assertGreaterThan(23, strlen(strip_tags((string) $newsItem->contents)));
            }
        }
    }

}
