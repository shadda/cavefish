<?php

	class Tools
	{
		public static function Coalesce(&$value, $ifnull=null)
		{
			if(empty($value))
			{
				return $ifnull;
			}
			return $value;
		}

		public static function GetPageURL($page, $action, array $query_string = array())
		{
			$_page_url = sprintf('%s/%s/%s?%s', 
				Tools::GetBaseURL(),
				$page, 
				$action,
				http_build_query($query_string)
			);

			return $_page_url;
		}

		public static function GetBaseURL()
		{
			$protocol = stripos('https', $_SERVER['SERVER_PROTOCOL']) !== false ? 'https://' : 'http://';
			$host = $_SERVER['HTTP_HOST'];
			$uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			
			return $protocol . $host . $uri ;
		}

		public static function GetCurrentURL($with_protocol = true)
		{
			$protocol = stripos('https', $_SERVER['SERVER_PROTOCOL']) !== false ? 'https://' : 'http://';
			$host = $_SERVER['HTTP_HOST'];
			
			$uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			
			return sprintf("%s%s%s", 
				$with_protocol ? $protocol : '',
				$host,
				$uri
			);
		}

		public static function BuildQueryString(array $query)
		{
			return http_build_query($query);
		}

		public static function GetClassName($object)
		{
			$_class_name = get_class($object);
			if(strpos($_class_name, '\\') !== false)
			{
				$_class_name = substr($_class_name, strrpos($_class_name, '\\')+1);
			}

			return $_class_name;
		}

		public static function FetchURL($url, array $post_data = array())
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; AOL 7.0; Windows NT 5.1; SV1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.1.4322; .NET CLR 2.0.50727)');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 

			if(count($post_data) > 0)
			{
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, self::BuildQueryString($post_data));
			}

			$result = curl_exec($ch);
			curl_close($ch);

			return $result;
		}
	}