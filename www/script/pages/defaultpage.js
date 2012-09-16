$(document).ready(function() {

	var twitter_form = $('form#twitter_form');
	var twitter_input = $('input#twitter_username');
	var twitter_feed = $('div#twitter-feed');

	var getTwitterData = function()
	{
		var username = twitter_input.val();
		twitter_feed.slideUp('slow', function() {

			$.getJSON('twitter.getfeed.ajax', {'twat': username}, function(results) 
			{
				twitter_feed.slideDown('slow');
				twitter_feed.html('');
				
				for(i in results['data'])
				{
					var data = results['data'][i];
					var div = $('<div></div>').attr('class', data.offset );

					var username_span = $('<span></span>').html(data.username);
					var message_p = $('<p></p>').html(data.content);
					var thumb_a = $('<a></a>').addClass('thumb').css({
						'background-image': 'url(' + data.profile_image_url + ')'
					});

					div.append(thumb_a);
					div.append(username_span);
					div.append(message_p);

					twitter_feed.append(div);
				}
			});
		});
	}

	twitter_form.bind('submit', function()
	{
		getTwitterData();
		return false;
	})

})