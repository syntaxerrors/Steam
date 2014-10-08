<?php namespace Syntax\SteamApi\Containers\Player;

class Level {
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

		$this->percentThroughLevel = $this->percent($this->xpToLevelUp, $levelRange);
	}

	private function percent ($num_amount, $num_total)
	{
		if($num_amount == 0 || $num_total == 0){
			return 0;
		}
		else {
			$count1 = $num_amount / $num_total;
			$count2 = $count1 * 100;
			$count = number_format($count2, 0);
			return $count;
		}
	}

}