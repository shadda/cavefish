<?php

	$dirname = dirname($_SERVER['SCRIPT_NAME']);
	$dirname = trim($dirname, "/");
	$basename = basename($_SERVER['SCRIPT_FILENAME']);	
	
	$URI = explode('?', $_SERVER['REQUEST_URI']);
	$URI[0] = str_replace( array( $basename, $dirname ), '', $URI[0]);
	$URI[0] = ltrim($URI[0], '/');
	
	
	@list($page, $action) = explode('/', $URI[0]);
	$URI[1] = isset($URI[1]) ? $URI[1] : '';
	
	parse_str($URI[1], $request);

	chdir('..');
	require 'init.php';
	require 'class/xtemplate.class.php';
	require 'class/page.class.php';

	try 
	{			
		$request = array_merge($_POST, $_GET);
		$request['page'] = Tools::Coalesce($page, 'DefaultPage');
		$request['action'] = Tools::Coalesce($action, '_Default');
		
		$request['page'] = str_replace('index', 'DefaultPage', $request['page']);

		$page = Controller::Load('\\Pages\\' . $request['page'], $request['action'], $request);
		print $page->Render( !isset($_GET['xml']) );
		
		$_SESSION['captcha'] = false;
		$_SESSION['referrer'] = Controller::$Remember ? $_SERVER['REQUEST_URI'] : (isset($_SESSION['referrer']) ? $_SESSION['referrer'] : Tools::GetBaseURL());	
	} 
	catch(\Exceptions\PageNotFound $e) 
	{
		echo "page not found";	
	}
	