<?php

	namespace Pages;

	class DefaultPage extends Page
	{	
		protected $LoginRequired = false;
		protected $Secure = false;

		public function _Default()
		{	
			$x_twitter_feed = $this->page->AC( new \XE('twitter-feed') );

			$_twitter_user = \Tools::Coalesce($_SESSION['twitter_user'], 'wesscope');
			$tweets = \API\TwitterFeed::getFeedForUser($_twitter_user, 10);

			$i = 0;
			foreach($tweets as $tweet)
			{
				$tweet->offset = $i & 1 ? 'a' : 'b';
				$x_tweet = $x_twitter_feed->AC( new \XE('tweet') );
				$x_tweet->AR($tweet);

				$i++;
			}

		}
	}