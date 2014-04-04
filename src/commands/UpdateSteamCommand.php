<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\StreamOutput;

class UpdateSteamCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'syntax:update-steam';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Perform any steps needed for syntax\steam-api updates.';

	/**
	 * The output stream for any artisan commands
	 *
	 * @var string
	 */
	protected $stream;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->stream      = fopen('php://output', 'w');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Handle Forum
		if ($this->confirm('Do you want to update Syntax\SteamApi? [yes|no]')) {
			$requestedVersion = $this->ask('What version of the steam-api do you require?  [Hit enter to leave as dev-master]', 'dev-master');

			$this->comment('Running composer update...');
			$this->composerUpdate($requestedVersion);

			$currentVersion = Config::get('steam-api::version');
			$newVersion     = Syntax\SteamApi\SteamApiServiceProvider::version;

			$this->comment('Checking for update steps to perform...');
			$this->update($currentVersion, $newVersion);

			$this->comment('Updating the version in the config...');
			$this->updateVersion($newVersion);
		}

		$this->comment('Done!');
	}

	/********************************************************************
	 * Update Methods
	 *******************************************************************/
	public function update($start, $end)
	{
		$versionRange = range($this->cleanVersion($start), $this->cleanVersion($end));

		if (count($versionRange) == 1) {
			$this->comment('Already on the latest version: '. $end);
			return true;
		}

		$performedUpdate = false;

		foreach ($versionRange as $version) {
			if ($version == $start) {
				$previousVersion = $version;
				continue;
			}

			$method = 'update'. $previousVersion .'To'. $version;

			if (method_exists($this, $method)) {
				$performedUpdate = true;

				$this->comment('Updating from version '. $previousVersion .' to '. $version .'...');
			}

			$previousVersion = $version;
		}

		if ($performedUpdate === false) {
			$this->comment('No updates needed...');
		}
	}

	/********************************************************************
	 * Helper Methods
	 *******************************************************************/
	protected function composerUpdate($version)
	{
		$commands = [
			'cd '. base_path(),
			'composer update syntax/steam-api:'. $version,
		];

		SSH::run($commands, function ($line) {
			echo $line.PHP_EOL;
		});
	}

	protected function cleanVersion($version)
	{
		return str_replace('.', '', $version);
	}

	protected function updateVersion($version)
	{
		list($path, $contents) = $this->getConfig('packages/syntax/steam-api/config.php');

		$contents = str_replace($this->laravel['config']['steam-api::version'], $version, $contents);

		File::put($path, $contents);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	protected function getConfig($file)
	{
		$path = $this->laravel['path'].'/config/'. $file;

		$contents = File::get($path);

		return array($path, $contents);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
