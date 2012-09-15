<?php

	if(isset($_GET['c'], $_GET['t'])) 
	{	
		chdir('..');
		require 'init.php';
		
		header("Expires: Tue, 01 Jan 2015 05:00:00 GMT"); 
		
		switch(strtolower($_GET['t'])) 
		{	
			case 'css':
				header('Content-type: text/css');
			break;
			case 'js':
				header('Content-type: text/javascript');
			break;
				
		}

		$key = md5($_GET['c']);
		$memcache = \Database\Memcache::GetInstance();
		$cache  = false;#$memcache->get($key);
		 
		if($cache) 
		{	
			echo $cache;
			exit;	
		}
		
		$parts = explode("|", $_GET['c']);
		$content = '';
		
		foreach($parts as $part) 
		{	
			$base = './www/';
			$path = realpath($base . $part);
			
			if(file_exists($path)) 
			{	
				$content .= file_get_contents($path);				
			}
		}
		
		$memcache->set($key, $content, MEMCACHE_COMPRESSED, 0);
		
		echo $content;
		exit;
	}
	