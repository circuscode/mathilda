<?php

/* 
Security
*/

if (!defined('ABSPATH')) { exit; }

function mathilda_tweet_filter ($tweet_cache, $num_tweets) {

/* Building Fiter Array */

$tweet_filter_cache=array();

for($i=0; $i < $num_tweets; $i++) 
{
$tweet_filter_cache[]=array($tweet_cache[$i][0],
				    		$tweet_cache[$i][1],
							$tweet_cache[$i][2],
							$tweet_cache[$i][3],
							$tweet_cache[$i][4],
							$tweet_cache[$i][5],
							$tweet_cache[$i][6]
							 );
}

/* Filter: Retweets */

$retweets_yes_or_no = get_option('mathilda_retweets');

if($retweets_yes_or_no == 0)
{
		for($i=0; $i < $num_tweets; $i++) 
		{
				$retweet_pos=strpos($tweet_filter_cache[$i][1], "RT ");
				if($retweet_pos === 0)
				{
				unset($tweet_filter_cache[$i]);	
				}
				// skip empty arrays
				if(isset($tweet_filter_cache[$i][1]))
				{
					$retweet_pos=strpos($tweet_filter_cache[$i][1], " RT ");
					if($retweet_pos > 0)
					{
					unset($tweet_filter_cache[$i]);	
					}
				}
				
		}
}

/* Filter: Replies */

$replies_yes_or_no = get_option('mathilda_replies');

if($replies_yes_or_no == 0)
{
		for($i=0; $i < $num_tweets; $i++) 
		{
				// Replies
				if(isset($tweet_filter_cache[$i][1]))
				{
						$replies_pos=strpos($tweet_filter_cache[$i][1], "@");
						if($replies_pos === 0)
						{
						unset($tweet_filter_cache[$i]);	
						}
				}
				// Reply all
				if(isset($tweet_filter_cache[$i][1]))
				{
						$replies_pos=strpos($tweet_filter_cache[$i][1], ".@");
						if($replies_pos === 0)
						{
						unset($tweet_filter_cache[$i]);	
						}
				}
				// Reply all
				if(isset($tweet_filter_cache[$i][1]))
				{
						$replies_pos=strpos($tweet_filter_cache[$i][1], ". @");
						if($replies_pos === 0)
						{
						unset($tweet_filter_cache[$i]);	
						}
				}
		}
}

return $tweet_filter_cache;

}

?>