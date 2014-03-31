<?php namespace Syntax\SteamApi\Queue;

class AddAppDetails {

	public function fire($job, $data)
	{
		$app = $data['app'];

		$existingDetail = \Steam_App_Detail::where('steam_app_id', $app->id)->first();
		$details = \Steam::app()->appDetails($app->appId);

		$appDetail                    = $existingDetail == null ? new \Steam_App_Detail : $existingDetail;
		$appDetail->steam_app_id      = $app->id;
		$appDetail->controllerSupport = $details->controllerSupport;
		$appDetail->description       = $details->description;
		$appDetail->about             = $details->about;
		$appDetail->about             = $details->about;
		$appDetail->header            = $details->header;
		$appDetail->website           = $details->website;
		$appDetail->legal             = $details->legal;
		$appDetail->windows           = $details->platforms->windows == 1 ? 1 : 0;
		$appDetail->mac               = $details->platforms->mac == 1 ? 1 : 0;
		$appDetail->linux             = $details->platforms->linux == 1 ? 1 : 0;
		$appDetail->release           = $details->release->date == null ? date('Y-m-d', strtotime($details->release->coming_soon)) : date('Y-m-d', strtotime($details->release->date));

		$appDetail->save();

		$job->delete();
	}
}