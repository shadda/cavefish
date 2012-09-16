<?php
	
	abstract class Config
	{	
		public static $_global_config = array(
			'dev' => array(
				'PostgreSQL' => array(
					'host' => 'localhost',
					'port' => '5432',
					'database' => 'cavefish',
					'username' => 'cavefish',
					'password' => '',
				),
				'Memcache' => array(
					'host' => 'localhost',
					'port' => '11211'
				)
			)
		);

		public static $_config  = array();

		public static function Get($key, $default=null, $required=false)
		{
			if(strpos($key, '::'))
			{	
				list($directive, $_key) = explode('::', $key, 2);

				if(isset(self::$_config[$directive], self::$_config[$directive][$_key]))
				{
					return self::$_config[$directive][$_key];
				}

				if($required)
				{
					throw new Exceptions\KeyError($key);
				}

				return $default;
			}

			if(!isset(self::$_config[$key]))
			{	
				if($required)
				{
					throw new Exceptions\KeyError($key);	
				}
				return $default;
			}

			return self::$_config[$key];
		}

		public static function JSONUpdate($data)
		{
			$_json = json_decode($data);

			if(!$_json)
			{
				throw new Exceptions\RunTimeError('Supplied JSON data could not be decoded.', $data);
			}

			self::$_config = array_merge(self::$_config, $_json);
		}

		public static function Update(array $data)
		{
			self::$_config = array_merge(self::$_config, $data);
		}

		public static function getFromEnvironment($env, $default_value=null)
		{
			return isset($_ENV[$env]) ? $_ENV[$env] : $default_value;
		}
        }
