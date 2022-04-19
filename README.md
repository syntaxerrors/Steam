# Steam API

[![Join the chat at https://gitter.im/syntaxerrors/Steam](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/syntaxerrors/Steam?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

![Unit Tests](https://github.com/syntaxerrors/Steam/workflows/Unit%20Tests/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/eb99d8de80e750fd4c27/maintainability)](https://codeclimate.com/github/syntaxerrors/Steam/maintainability)
<a href="https://codeclimate.com/github/syntaxerrors/Steam/test_coverage"><img src="https://api.codeclimate.com/v1/badges/eb99d8de80e750fd4c27/test_coverage" /></a>
[![Latest Stable Version](https://poser.pugx.org/syntax/steam-api/v/stable.svg)](https://packagist.org/packages/syntax/steam-api)
[![Total Downloads](https://poser.pugx.org/syntax/steam-api/downloads.svg)](https://packagist.org/packages/syntax/steam-api)
[![License](https://poser.pugx.org/syntax/steam-api/license.svg)](https://packagist.org/packages/syntax/steam-api)

**Version Support**  
`Laravel >= 6.0`  
`PHP >= 7.3.0`  

- [Installation](#installation)
- [Usage](#usage)
- [Contributors](#contributors)

This package provides an easy way to get details from the Steam API service.  The services it can access are:

- `ISteamNews`
- `IPlayerService`
- `ISteamUser`
- `ISteamUserStats`
- `ISteamApp`

## Installation

Begin by installing this package with composer.

	"require": {
		"syntax/steam-api": "2.3.*"
	}
	
Next, update composer from the terminal.

	composer update syntax/steam-api

> Alternately, you can run "composer require syntax/steam-api:dev-master" from the command line.

Lastly, publish the config file.  You can get your API key from [Steam](http://steamcommunity.com/dev/apikey).

	php artisan vendor:publish

## Usage

Each service from the Steam API has its own methods you can use.

- [Global](#global)
- [News](#news)
- [Player](#player)
- [User](#user)
- [User Stats](#user-stats)
- [App](#app)
- [Package](#package)
- [Item](#item)
- [Group](#group)

### Global
These are methods that are available to each service.

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
This method will get the news articles for a given app ID.  It has three parameters.

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

When instantiating the player class, you are required to pass a steamId or Steam community ID.

```php
Steam::player($steamId)
```

#### GetSteamLevel
This method will return the level of the Steam user given.  It simply returns the integer of their current level.


> Example Output: [GetSteamLevel](./examples/player/GetSteamLevel.txt)

#### GetPlayerLevelDetails
This will return a Syntax\Containers\Player_Level object with full details for the players level. 


> Example Output: [GetPlayerLevelDetails](./examples/player/GetPlayerLevelDetails.txt)

#### GetBadges
This call will give you a list of the badges that the player currently has. There is currently no schema for badges, so all you will get is the ID and details.


> Example Output: [GetBadges](./examples/player/GetBadges.txt)

#### GetOwnedGames
GetOwnedGames returns a list of games a player owns along with some playtime information, if the profile is publicly visible. Private, friends-only, and other privacy settings are not supported unless you are asking for your own personal details (i.e. the WebAPI key you are using is linked to the steamID you are requesting).

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
includeAppInfo| boolean  | Whether or not to include game details | No | true
includePlayedFreeGames | boolean | Whether or not to include free games | No | false
appIdsFilter | array | An array of appIds.  These will be the only ones returned if the user has them | No | array()


> Example Output: [GetOwnedGames](./examples/player/GetOwnedGames.txt)

#### GetRecentlyPlayedGames
GetRecentlyPlayedGames returns a list of games a player has played in the last two weeks, if the profile is publicly visible. Private, friends-only, and other privacy settings are not supported unless you are asking for your own personal details (i.e. the WebAPI key you are using is linked to the steamID you are requesting).

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

When instantiating the user class, you are required to pass at least one steamId or steam community ID.

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
This will return details on one or more users.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
steamId| int[]  | An array of (or singular) steam ID(s) to get details for  | No | Steam ID passed to user()

```php
	// One user
	$steamId = 76561197960287930;
	$player = Steam::user($steamId)->GetPlayerSummaries()[0];
	
	// Several users
	$steamIds = [76561197960287930, 76561197968575517]
	$players = Steam::user($steamIds)->GetPlayerSummaries();
```

> Example Output: [GetPlayerSummaries](./examples/user/GetPlayerSummaries.txt)

#### GetFriendList
Returns the friend list of any Steam user, provided his Steam Community profile visibility is set to "Public".

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
relationship| string (all or friend)  | The type of friends to get | No | all
summaries| bool (true or false)  | To return the friend player summaries, or only steamIds | No | true

Once the list of friends is gathered, if `summaries` is not set to `false`; it is passed through [GetPlayerSummaries](#GetPlayerSummaries).  This allows you to get back a collection of Player objects.


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

When instantiating the user stats class, you are required to pass a steamID or Steam community ID.

```php
Steam::userStats($steamId)
```

#### GetPlayerAchievements
Returns a list of achievements for this user by app ID.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The id of the game you want the user's achievements in | Yes |


> Example Output: [GetPlayerAchievements](./examples/user/stats/GetPlayerAchievements.txt)

#### GetGlobalAchievementPercentagesForApp
This method will return a list of all achievements for the specified game and the percentage of all users that have unlocked each achievement.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The ID of the game you want the user's achievements in | Yes |


> Example Output: [GetGlobalAchievementPercentagesForApp](./examples/user/stats/GetGlobalAchievementPercentageForApp.txt)

#### GetUserStatsForGame
Returns a list of achievements for this user by app ID.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The ID of the game you want the user's achievements in | Yes |
all| boolean | If you want all stats and not just the achievements set to true.| No | FALSE


> Example Output: [GetUserStatsForGame](./examples/user/stats/GetUserStatsForGame.txt) | [GetUserStatsForGame (all)](./examples/user/stats/GetUserStatsForGameAll.txt)

#### GetSchemaForGame
Returns a list of game details, including achievements and stats.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int | The ID of the game you want the details for. | Yes |


> Example Output: [GetSchemaForGame](./examples/user/stats/GetSchemaForGame.txt)

### App
This area will get details for games.

```php
Steam::app()
```

#### appDetails
This gets all the details for a game.  This is most of the information from the store page of a game.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appIds| int[] | The ids of the games you want details for | Yes |
cc | string | The cc is the country code, you can get appropriate currency values according to [ISO 3166-1](https://wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements) | No |
l | string | The l is the language parameter, you can get the appropriate language according to [ISO 639-1](https://wikipedia.org/wiki/List_of_ISO_639-1_codes) | No |


> Example Output: [appDetails](./examples/app/appDetails.txt)

#### GetAppList
This method will return an array of app objects directly from Steam.  It includes the appID and the app name.

> Example Output: [GetAppList](./examples/app/GetAppList.txt)

### Package
This method will get details for packages.

```php
Steam::package()
```

#### packageDetails
This gets all the details for a package. This is most of the information from the store page of a package.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
packIds| int[] | The ids of the packages you want details for | Yes |
cc | string | The cc is the country code, you can get appropriate currency values according to [ISO 3166-1](https://wikipedia.org/wiki/ISO_3166-1) | No |
l | string | The l is the language parameter, you can get the appropriate language according to [ISO 639-1](https://wikipedia.org/wiki/ISO_639-1) (If there is one) | No |


> Example Output: [packageDetails](./examples/package/packageDetails.txt)

### Item
This method will get user inventory for item.

```php
Steam::item()
```

#### GetPlayerItems
This gets all the item for a user inventory.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
appId| int  | The appid of the game you want for | Yes |
steamid | int | The steamid of the Steam user you want for | Yes |

⚠️ **Now known to supports**:`440`, `570`, `620`, `730`, `205790`, `221540`, `238460`

> Example Output: [GetPlayerItems](./examples/item/GetPlayerItems.txt)

### Group
This service is used to get details on a Steam group.

```php
Steam::group()
```

#### GetGroupSummary
This method will get the details for a group.

##### Arguments

Name | Type | Description | Required | Default
-----|------|-------------|----------|---------
group| string or int  | The ID or the name of the group. | Yes

##### Example usage

```php
<?php
	$news = Steam::group()->GetGroupSummary('Valve');
?>
```

> Example Output: [GetGroupSummary](./examples/group/GetGroupSummary.txt)

## Testing the Steam Package

A Steam API key must be provided or most tests will fail.
  
**Run Tests**  
```
# Build container
docker-compose build

# Install dependancies
docker-compose run php composer install

# Run tests (assumes apiKey is set in .env file)
docker-compose run php composer test

# Or with the apiKey inline
docker-compose run -e api=YOUR_STEAM_API_KEY php composer test

# With coverage
docker-compose run php composer coverage

# Play around
docker-compose run php bash
```

## Contributors
- [Stygiansabyss](https://github.com/stygiansabyss)
- [nicekiwi](https://github.com/nicekiwi)
- [rannmann](https://github.com/rannmann)
- [Amegatron](https://github.com/Amegatron)
- [mjmarianetti](https://github.com/mjmarianetti)
- [MaartenStaa](https://github.com/MaartenStaa)
- [JRizzle88](https://github.com/JRizzle88)
- [jastend](https://github.com/jastend)
- [Teakowa](https://github.com/Teakowa)
- [Ben Sherred](https://github.com/bensherred)
