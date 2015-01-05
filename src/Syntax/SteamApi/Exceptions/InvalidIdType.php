<?php namespace Syntax\SteamApi\Exceptions;

class InvalidIdType extends \Exception {

	public function __construct($supplied, array $valid, $code = 0, $previous = null)
	{
		$selectFrom = $this->humanReadableImplode($valid);
		$message    = 'Invalid steamId type passed [' . $supplied . '].  Select from: ' . $selectFrom;

		parent::__construct($message, $code, $previous);
	}

	private function humanReadableImplode($array)
	{
		$last  = array_slice($array, -1);
		$first = join(', ', array_slice($array, 0, -1));
		$both  = array_filter(array_merge([$first], $last));

		return join(' or ', $both);
	}
} 