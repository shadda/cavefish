<?php

	namespace Ajax;

	class Twitter extends Ajax
	{
		protected $UseDB = true;

		public function getFeed()
		{
			$_twitter_user = \Tools::Coalesce($this->request['twat'], 'wesscope');
			$_limit = \Tools::Coalesce($this->request['limit'], 10);
			$_limit = min(100, $_limit);
			$_limit = max(10, $_limit);

			$_SESSION['twitter_user'] = $_twitter_user;

			$tweets = \API\TwitterFeed::getFeedForUser($_twitter_user, $_limit);

			return $tweets;
		}
	}