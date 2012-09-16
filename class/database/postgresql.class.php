<?php
	namespace Database;

	use \Config as Config;

	class PostgreSQL extends Database
	{
		use \Singleton;

		public function __construct()
		{
			$_config = Config::Get('PostgreSQL');
			$dsn = sprintf("pgsql:host=%s; port=%s; dbname=%s", $_config['host'], $_config['port'], $_config['database']);

			parent::__construct($dsn, $_config['username'], $_config['password']);
		}
	}