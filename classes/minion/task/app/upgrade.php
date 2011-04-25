<?php defined('SYSPATH') or die('No direct script access.');

class Minion_Task_App_Upgrade extends Minion_Task {

	public function execute(array $config)
	{
		Model_App_Version::create_table();

		$upgrades = array();
		$files = Kohana::list_files('upgrades');
		$current = date_create(Model_App_Version::get_current());

		foreach ($files as $file)
		{
			$timestamp = date_create(pathinfo($file, PATHINFO_FILENAME));
			if ($timestamp > $current)
			{
				$upgrades[$timestamp] = $file;
			}
		}

		foreach ($upgrades as $timestamp => $path)
		{
			include_once $path;
			$class_name = 'Upgrade_'.$timestamp;
			$upgrade = new $class_name;
			$upgrade->execute();
			Model_App_Version::set_current($timestamp);
		}

		passthru(DOCROOT.'../minion db:migrate');
	}
}