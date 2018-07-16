<?php

return array(

	/**
	 * You can get a steam API key from http://steamcommunity.com/dev/apikey
	 * Once you get your key, add it here.
	*/
	'steamApiKey' => env('STEAM_API_KEY'),
	
	/**
	 * Localized language paramter
	 * Steam will default back to English if the requested language is not available
	*/
	'steamLang' => env('STEAM_LANG', 'english'),

);
