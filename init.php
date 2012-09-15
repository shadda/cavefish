<?php
	
	function _get_environment()
	{	
		$path = '/domain/environment.dat';
		if(file_exists($path))
		{
			$environment = file_get_contents($path);
			return trim($environment);
		}

		return 'dev';
	}	

	define('BASE_PATH', realpath( dirname(__FILE__) ));
	define('START_TIME', time());
	define('ENVIRONMENT', _get_environment());

	require 'class/exceptions/exception.class.php';
	require 'class/util.class.php';
	require 'class/config.class.php';

	Config::$_config = Config::$_global_config[ENVIRONMENT];

	$_include_paths = array(
		'class/',
		'ajax/',
		'pages/'
	);

	$_include_path = (string) get_include_path();

	foreach($_include_paths as $path)
	{
		$_include_path .=  (string) (PATH_SEPARATOR . BASE_PATH . "/$path");
	}

	set_include_path($_include_path);
	
	spl_autoload_register(function($className) 
	{	
		$_class_name = strtolower($className);
		$_path = str_replace('__', '/', $className);
		$_path = str_replace('\\', '/', $className);
		$_path .= '.class.php';
		
		if(stream_resolve_include_path($_path))
		{
			require $_path;
		}
	});

	register_shutdown_function(function()
	{
		$_SESSION['token'] = uniqid();
	});

	session_start();

	$_SESSION['create_time'] = Tools::Coalesce($_SESSION['create_time'], time());
	$_SESSION['previous_token'] = Tools::Coalesce($_SESSION['token'], null);
	$_SESSION['token'] = uniqid();