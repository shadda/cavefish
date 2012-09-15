<?php
	
	chdir('..');
	require 'init.php';
	require 'class/ajax.class.php';

	if(isset($_REQUEST['module'], $_REQUEST['action'])) {
		
		$class = strtolower('\\Ajax\\'.$_GET['module']);
		$request = array_merge($_POST, $_GET);
		
		if(class_exists( $class )) 
		{	
			$page = Controller::Load($class, $request['action'], $request);
			echo $page->Render();
			exit;
		}
	}
