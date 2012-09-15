<?php

	namespace Pages;

	class DefaultPage extends Page
	{	
		protected $LoginRequired = false;
		protected $Secure = false;

		public function _Default()
		{	
			$this->page->AC( new \XE('twitter-feed') );
		}
	}