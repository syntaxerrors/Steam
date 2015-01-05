<?php  namespace Syntax\SteamApi\Containers\Group;

class Details {

	public $name;

	public $url;

	public $headline;

	public $summary;

	public $avatarIcon;

	public $avatarMedium;

	public $avatarFull;

	function __construct($details)
	{
		$this->name         = (string)$details->groupName;
		$this->url          = (string)$details->groupUrl;
		$this->headline     = (string)$details->headline;
		$this->summary      = (string)$details->summary;
		$this->avatarIcon   = $this->getImageForAvatar((string)$details->avatarIcon);
		$this->avatarMedium = $this->getImageForAvatar((string)$details->avatarMedium);
		$this->avatarFull   = $this->getImageForAvatar((string)$details->avatarFull);
	}

	protected function getImageForAvatar($image)
	{
		return \HTML::image($image);
	}

}