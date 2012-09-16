<?php

	namespace Ajax;

	class Twitter extends Ajax
	{
		protected $UseDB = true;

		public function getFeed()
		{
			$_twitter_user = \Tools::Coalesce($this->request['twat'], 'wesscope');
			$_twitter_url = "https://twitter.com/status/user_timeline/$_twitter_user.json?count=10";
			$_twitter_data = \Tools::FetchURL($_twitter_url);

			$_decoded = json_decode($_twitter_data);
			
			$tweets = array();
			$insert = $this->db->prepare("
				INSERT INTO twitter_feeds 
				(event_time, username, message, profile_image_url, id_str)
				VALUES
				(:event_time::timestamp, :username, :message, :profile_image_url, :id_str)
			");

			$i = 0;
			foreach($_decoded as $tweet)
			{
				$x_tweet = new \stdClass;
				$x_tweet->offset = $i & 1 ? 'a' : 'b';

				$x_tweet->username = $tweet->user->screen_name;
				$x_tweet->content = $tweet->text;
				$x_tweet->profile_image_url = $tweet->user->profile_image_url;

				try 
				{
					$insert->execute( 
						array(
							':event_time' => $tweet->created_at,
							':username' => $tweet->user->screen_name,
							':message' => $tweet->text,
							':profile_image_url' => $tweet->user->profile_image_url,
							':id_str' => $tweet->id_str
						)
					);
				} 
				catch(\PDOException $e)
				{

				}

				$tweets[] = $x_tweet;
				$i++;
			}

			return $tweets;
		}
	}