<?php

require_once('BaseTester.php');

/** @group Player */
class PlayerTest extends BaseTester {

    /** @test */
    public function it_gets_the_steam_level_by_user_id()
    {
        $steamLevel = $this->steamClient->player($this->id64)->GetSteamLevel();

        $this->assertIsInt($steamLevel);
    }

    /** @test */
    public function it_gets_the_player_details_by_user_id()
    {
        $details = $this->steamClient->player($this->id64)->GetPlayerLevelDetails();

        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Player\Level::class, $details);

        $attributes = [
            'playerXp', 'playerLevel', 'xpToLevelUp', 'xpForCurrentLevel', 'currentLevelFloor',
            'currentLevelCeiling', 'percentThroughLevel'
        ];
        $this->assertObjectHasProperties($attributes, $details);
    }

    /** @test */
    public function it_gets_the_badges_by_user_id()
    {
        $badges = $this->steamClient->player($this->id64)->GetBadges();

        $attributes = ['badges', 'player_xp', 'player_level', 'player_xp_needed_to_level_up', 'player_xp_needed_current_level'];
        $this->assertObjectHasProperties($attributes, $badges);

        $attributes = ['badgeid', 'level', 'completion_time', 'xp', 'scarcity'];
        $this->assertObjectHasProperties($attributes, $badges->badges[0]);
    }

    /** @test */
    public function it_gets_the_badge_progress_by_user_id()
    {
        $progress = $this->steamClient->player($this->id64)->GetCommunityBadgeProgress();

        $this->assertObjectHasProperty('quests', $progress);

        $attributes = ['questid', 'completed'];
        $this->assertObjectHasProperties($attributes, $progress->quests[0]);
    }

    /** @test */
    public function it_gets_the_owned_games_by_user_id()
    {
        $games = $this->steamClient->player($this->id64)->GetOwnedGames();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $games);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Game::class, $games->first());

        $attributes = [
            'appId', 'name', 'playtimeTwoWeeks', 'playtimeTwoWeeksReadable', 'playtimeForever', 'playtimeForeverReadable',
            'icon', 'logo', 'header', 'hasCommunityVisibleStats'
        ];
        $this->assertObjectHasProperties($attributes, $games->first());
    }

    /** @test */
    public function it_gets_the_owned_games_by_user_id_without_app_details()
    {
        $games = $this->steamClient->player($this->id64)->GetOwnedGames(false);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $games);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Game::class, $games->first());

        $attributes = [
            'appId', 'name', 'playtimeTwoWeeks', 'playtimeTwoWeeksReadable', 'playtimeForever', 'playtimeForeverReadable',
            'icon', 'logo', 'header', 'hasCommunityVisibleStats'
        ];
        $this->assertObjectHasProperties($attributes, $games->first());

        $this->assertNull($games->first()->name);
        $this->assertNull($games->first()->icon);
        $this->assertNull($games->first()->logo);
    }

    /** @test */
    public function it_filters_the_owned_games_by_user_id()
    {
        $games = $this->steamClient->player($this->id64)->GetOwnedGames(true, false, $this->appId);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $games);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Game::class, $games->first());
        $this->assertEquals(1, $games->count());

        $attributes = [
            'appId', 'name', 'playtimeTwoWeeks', 'playtimeTwoWeeksReadable', 'playtimeForever', 'playtimeForeverReadable',
            'icon', 'logo', 'header', 'hasCommunityVisibleStats'
        ];
        $this->assertObjectHasProperties($attributes, $games->first());
    }

    /** @test */
    public function it_gets_recently_played_games_by_user_id()
    {
        $games = $this->steamClient->player($this->id64)->GetRecentlyPlayedGames();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $games);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Game::class, $games->first());

        $attributes = [
            'appId', 'name', 'playtimeTwoWeeks', 'playtimeTwoWeeksReadable', 'playtimeForever', 'playtimeForeverReadable',
            'icon', 'logo', 'header', 'hasCommunityVisibleStats'
        ];
        $this->assertObjectHasProperties($attributes, $games->first());
    }

    /** @test */
    public function it_gets_a_single_recently_played_game_by_user_id()
    {
        $games = $this->steamClient->player($this->id64)->GetRecentlyPlayedGames(1);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $games);
        $this->assertInstanceOf(\Syntax\SteamApi\Containers\Game::class, $games->first());
        $this->assertEquals(1, $games->count());

        $attributes = [
            'appId', 'name', 'playtimeTwoWeeks', 'playtimeTwoWeeksReadable', 'playtimeForever', 'playtimeForeverReadable',
            'icon', 'logo', 'header', 'hasCommunityVisibleStats'
        ];
        $this->assertObjectHasProperties($attributes, $games->first());
    }

    /** @test */
    public function it_checks_if_playing_a_shared_game_by_user_and_app_id()
    {
        $playingSharedGame = $this->steamClient->player($this->id64)->IsPlayingSharedGame($this->appId);

        $this->assertNotNull($playingSharedGame);
    }
}
