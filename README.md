# Steam

This package provides an easy way to get details from the steam api service.  The services it can access are:

- `Steam_News`
- `Steam_Player`
- `Steam_User`
- `Steam_User_Stats`
- `Steam_App`

## Installation

Begin by installing this package with composer.

	"require": {
		"syntax/steam-api": "dev-master"
	}

Next, update composer from the terminal.

	composer update syntax/steam-api

> Alternately, you can run "composer require syntax/steam-api:dev-master" from the command line.

Once that is finished, add the service provider to `app/config/app.php`

	'Syntax\SteamApi\SteamApiServiceProvider',

> The alias to Steam is already handled by the package.