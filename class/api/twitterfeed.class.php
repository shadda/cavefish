<?php

	namespace API;

	class TwitterFeed 
	{
		const FEED_URL = "https://twitter.com/status/user_timeline/%s.json?count=%d";
		public static function getFeedForUser($username, $limit=10)
		{

			$_twitter_url = sprintf(TwitterFeed::FEED_URL, $username, (int) $limit);
			$_twitter_data = \Tools::FetchURL($_twitter_url);

			$_decoded = json_decode($_twitter_data);
			
			$tweets = array();
			$i = 0;
			foreach($_decoded as $tweet)
			{
				$x_tweet = new \stdClass;
				$x_tweet->offset = $i & 1 ? 'a' : 'b';

				$x_tweet->username = $tweet->user->screen_name;
				$x_tweet->content = $tweet->text;
				$x_tweet->profile_image_url = $tweet->user->profile_image_url;

				$tweets[] = $x_tweet;
				$i++;
			}

			return $tweets;
		}

	}