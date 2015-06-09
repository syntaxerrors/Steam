# Steam

[![Join the chat at https://gitter.im/syntaxerrors/Steam](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/syntaxerrors/Steam?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/syntaxerrors/Steam.svg)](https://travis-ci.org/syntaxerrors/Steam)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/syntaxerrors/Steam/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/syntaxerrors/Steam/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/syntax/steam-api/v/stable.svg)](https://packagist.org/packages/syntax/steam-api)
[![Total Downloads](https://poser.pugx.org/syntax/steam-api/downloads.svg)](https://packagist.org/packages/syntax/steam-api)
[![License](https://poser.pugx.org/syntax/steam-api/license.svg)](https://packagist.org/packages/syntax/steam-api)

**For Laravel 4, checkout the documentation on the [Laravel 4 branch](https://github.com/syntaxerrors/Steam/tree/Laravel4).**

- [Installation](#install)
- [Usage](#usage)
- [Contributors](#contributors)

This package provides an easy way to get details from the steam api service.  The services it can access are:

- `ISteamNews`
- `IPlayerService`
- `ISteamUser`
- `ISteamUserStats`
- `ISteamApp`

## Installation

Begin by installing this package with composer.

	"require": {
		"syntax/steam-api": "2.0.*"
	}
	
Next, update composer from the terminal.

	composer update syntax/steam-api

> Alternately, you can run "composer require syntax/steam-api:dev-master" from the command line.

Once that is finished, add the service provider to `config/app.php`

	'Syntax\SteamApi\SteamApiServiceProvider',

> The alias to Steam is already handled by the package.

Lastly, publish the config file.  You can get your API key from [Steam](http://steamcommunity.com/dev/apikey)

	php artisan vendor:publish

## Usage

Each service from the steam api has it's own methods you can use.

- [Global](#global)
- [News](#news)
- [Player](#player)
- [User](#user)
- [User Stats](#user-stats)
- [App](#app)
- [Group](#group)

### Global
These are methods that are available to each service

#### convertId
This will convert the given steam ID to each type of steam ID (64 bit, 32 bit and steam ID3).

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
id| string  | The id you want to convert | Yes
format | string | The format you want back. | No | null

> Possible formats are ID64, id64, 64, ID32, id32, 32, ID3, id3 and 3.

##### Example usage

```php
Steam::convertId($id, $format);
```

> Example Output: [convertId](./examples/global/convertId.txt)

### News
The [Steam News](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetNewsForApp_.28v0002.29) web api is used to get articles for games.

```php
Steam::news()
```

#### GetNewsForApp
This method will get the news articles for a given app id.  It has three parameters.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int  | The id for the app you want news on | Yes
count | int | The number of news items to return | No | 5
maxlength | int | The maximum number of characters to return | No | null

##### Example usage

```php
<?php
	$news = Steam::news()->GetNewsForApp($appId, 5, 500)->newsitems;
?>
```

> Example Output: [GetNewsForApp](./examples/news/GetNewsForApp.txt)

### Player
The [Player Service](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetOwnedGames_.28v0001.29) is used to get details on players.

When instantiating the player class, you are required to pass a steamId or steam community ID.

```php
Steam::player($steamId)
```

#### GetSteamLevel
This method will return the level of the steam user given.  It simply returns the integer of their current level.


> Example Output: [GetSteamLevel](./examples/player/GetSteamLevel.txt)

#### GetPlayerLevelDetails
This will return a Syntax\Containers\Player_Level object with full details for the players level. 


> Example Output: [GetPlayerLevelDetails](./examples/player/GetPlayerLevelDetails.txt)

#### GetBadges
This call will give you a list of the badges that the player currently has.  There is currently no schema for badges so all you will get is the id and details.


> Example Output: [GetBadges](./examples/player/GetBadges.txt)

#### GetOwnedGames
GetOwnedGames returns a list of games a player owns along with some playtime information, if the profile is publicly visible. Private, friends-only, and other privacy settings are not supported unless you are asking for your own personal details (ie the WebAPI key you are using is linked to the steamid you are requesting).

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
includeAppInfo| boolean  | Whether or not to include game details | No | true
includePlayedFreeGames | boolean | Whether or not to include free games | No | false
appIdsFilter | array | An array of appIds.  These will be the only ones returned if the user has them | No | array()


> Example Output: [GetOwnedGames](./examples/player/GetOwnedGames.txt)

#### GetRecentlyPlayedGames
GetRecentlyPlayedGames returns a list of games a player has played in the last two weeks, if the profile is publicly visible. Private, friends-only, and other privacy settings are not supported unless you are asking for your own personal details (ie the WebAPI key you are using is linked to the steamid you are requesting).

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
count| int  | The number of games to return | No | null

> Example Output: [GetRecentlyPlayedGames](./examples/player/GetRecentlyPlayedGames.txt)

#### IsPlayingSharedGame
IsPlayingSharedGame returns the original owner's SteamID if a borrowing account is currently playing this game. If the game is not borrowed or the borrower currently doesn't play this game, the result is always 0.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int  | The game to check for  | Yes |


> Example Output: [IsPlayingSharedGame](./examples/player/IsPlayingSharedGame.txt)

### User
The [User](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetFriendList_.28v0001.29) WebAPI call is used to get details about the user specifically.

When instantiating the user class, you are required to pass a steamId or steam community ID.

```php
Steam::user($steamId)
```

#### ResolveVanityURL
This will return details on the user from their display name.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
displayName| string  | The display name to get the steam ID for.  In `http://steamcommunity.com/id/gabelogannewell` it would be `gabelogannewell`.  | Yes | NULL

```php
	$player = Steam::user($steamId)->ResolveVanityURL('gabelogannewell');
```

> Example Output: [ResolveVanityURL](./examples/user/ResolveVanityURL.txt)

#### GetPlayerSummaries
This will return details on the user.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
steamId| int[]  | An array of (or singular) steam id(s) to get details for  | No | Steam id passed to user()

```php
	$player = Steam::user($steamId)->GetPlayerSummaries()[0];
```

> Example Output: [GetPlayerSummaries](./examples/user/GetPlayerSummaries.txt)

#### GetFriendList
Returns the friend list of any Steam user, provided his Steam Community profile visibility is set to "Public".

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
relationship| string (all or friend)  | The type of friends to get | No | all

Once the list of friends is gathered, it is passed through [GetPlayerSummaries](#GetPlayerSummaries).  This allows you to get back a collection of Player objects.


> Example Output: [GetFriendList](./examples/user/GetFriendList.txt)

#### GetPlayerBans
Returns the possible bans placed on the provided steam ID(s).

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
steamId| int[]  | An array of (or singular) steam id(s) to get details for  | No | Steam id passed to user()


> Example Output: [GetPlayerBans](./examples/user/GetPlayerBans.txt)

### User Stats
The [User Stats](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetPlayerAchievements_.28v0001.29) WebAPI call is used to get details about a user's gaming.

When instantiating the user stats class, you are required to pass a steamId or steam community ID.

```php
Steam::userStats($steamId)
```

#### GetPlayerAchievements
Returns a list of achievements for this user by app id.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The id of the game you want the user's achievements in | Yes |


> Example Output: [GetPlayerAchievements](./examples/user/stats/GetPlayerAchievements.txt)

#### GetGlobalAchievementPercentagesForApp
This method will return a list of all chievements for the specified game and the percentage that each achievement has been unlocked.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The id of the game you want the user's achievements in | Yes |


> Example Output: [GetGlobalAchievementPercentagesForApp](./examples/user/stats/GetGlobalAchievementPercentageForApp.txt)

#### GetUserStatsForGame
Returns a list of achievements for this user by app id.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The id of the game you want the user's achievements in | Yes |
all| boolean | If you want all stats and not just the achievements set to true.| No | FALSE


> Example Output: [GetUserStatsForGame](./examples/user/stats/GetUserStatsForGame.txt) | [GetUserStatsForGame (all)](./examples/user/stats/GetUserStatsForGameAll.txt)

### App
This area will get details for games.

```php
Steam::app()
```

#### appDetails
This gets all the details for a game.  This is most of the infomation from the store page of a game.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appIds| int[] | The ids of the games you want details for | Yes |


> Example Output: [appDetails](./examples/app/appDetails.txt)

#### GetAppList
This method will return an array of app objects directly from steam.  It includes the appId and the app name.

> Example Output: [GetAppList](./examples/app/GetAppList.txt)

### Group
This service is used to get details on a steam group.

```php
Steam::group()
```

#### GetGroupSummary
This method will get the details for a group.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
group| string or int  | The id or the name of the group. | Yes

##### Example usage

```php
<?php
	$news = Steam::group()->GetGroupSummary('Valve');
?>
```

> Example Output: [GetGroupSummary](./examples/group/GetGroupSummary.txt)
