<?php

	namespace Database;

	class Memcache extends \Memcache
	{
		use \Singleton;

		public function __construct()
		{
			$this->connect(
				\Config::Get('Memcache::host'), 
				\Config::Get('Memcache::port')
			);
		}
	}