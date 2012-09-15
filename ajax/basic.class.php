<?php
	
	namespace Ajax;

	class Basic extends Ajax 
	{
		public function Greet()
		{
			if(isset($_GET['name']))
			{
				return sprintf("Hello %s", $_GET['name']);
			}
		}
	}