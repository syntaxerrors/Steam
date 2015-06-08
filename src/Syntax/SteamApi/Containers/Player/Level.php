<?php namespace Syntax\SteamApi\Containers\Player;

class Level {
	public $playerXp;

	public $playerLevel;

	public $xpToLevelUp;

	public $xpForCurrentLevel;

	public $currentLevelFloor;

	public $currentLevelCeiling;

	public $percentThroughLevel;

	public function __construct($levelDetails)
	{
		$this->playerXp          = $levelDetails->player_xp;
		$this->playerLevel       = $levelDetails->player_level;
		$this->xpToLevelUp       = $levelDetails->player_xp_needed_to_level_up;
		$this->xpForCurrentLevel = $levelDetails->player_xp_needed_current_level;

		$this->currentLevelFloor    = $this->xpForCurrentLevel;
		$this->currentLevelCeiling = $this->playerXp + $this->xpToLevelUp;

		// arbitrary range formula. n = value in the middle ( n - min ) / ( max - min ) * 100
		$this->percentThroughLevel = ( $this->playerXp - $this->currentLevelFloor ) / ( $this->currentLevelCeiling - $this->currentLevelFloor ) * 100;		
	}

}