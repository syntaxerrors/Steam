<?php namespace Syntax\SteamApi\Queue;

class AddApp {

	public function fire($job, $data)
	{
		$app = $data['app'];

		$newApp        = new \Steam_App;
		$newApp->appId = $app->appid;
		$newApp->name  = $app->name;

		$newApp->save();

		$job->delete();
	}
}