<?php defined('SYSPATH') or die('No direct script access.');

class Model_App_Version {

	public static function create_table()
	{
		$query = DB::query(Database::SELECT, "SHOW TABLES like 'app_versions'")
			->execute();

		if ( ! count($query))
		{
			$sql = '
				CREATE  TABLE IF NOT EXISTS `app_versions`
				(
					`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`timestamp` varchar(14) NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE = InnoDB
			';

			DB::query(NULL, $sql)
				->execute();

			DB::insert('app_versions', array('timestamp'))
				->values(array(0))
				->execute();
		}
	}

	public static function get_current()
	{
		return DB::select('timestamp')
			->from('app_versions')
			->limit(1)
			->execute()
			->get('timestamp');
	}

	public static function set_current($timestamp)
	{
		DB::update('app_versions')
			->set(array('timestamp' => $timestamp))
			->execute();
	}
}