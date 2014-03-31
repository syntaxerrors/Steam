<?php namespace Syntax\SteamApi\Containers;

class Player_Level {
	public $playerXp;

	public $playerLevel;

	public $xpToLevelUp;

	public $xpForCurrentLevel;

	public $currentLevelFloor;

	public $currentLevelCeieling;

	public $percentThroughLevel;

	public function __construct($levelDetails)
	{
		$this->playerXp          = $levelDetails->player_xp;
		$this->playerLevel       = $levelDetails->player_level;
		$this->xpToLevelUp       = $levelDetails->player_xp_needed_to_level_up;
		$this->xpForCurrentLevel = $levelDetails->player_xp_needed_current_level;

		$this->currentLevelFloor    = $this->xpForCurrentLevel;
		$this->currentLevelCeieling = $this->playerXp + $this->xpToLevelUp;

		$levelRange = $this->currentLevelCeieling - $this->currentLevelFloor;

		$this->percentThroughLevel = percent($this->xpToLevelUp, $levelRange);
	}

}