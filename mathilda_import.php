<?php

function mathilda_import_script() {

/*
Runtime
*/

set_time_limit(1800);

/*
Headline
*/

echo '
<h1 class="mathilda_tools_headline">Import Archive</h1>';

/*
Directories
*/

$upload_dir = wp_upload_dir();
$twitter_import_directory="mathilda-import";
$twitter_import_path = $upload_dir['basedir'].'/'.$twitter_import_directory;
$mathilda_images_dirname="mathilda-images";
$mathilda_images_dirname = $upload_dir['basedir'].'/'.$mathilda_images_dirname .'/';

/*
Arrays
*/

$list_import_files=array();
$number_of_files=0;
$list_import_content=array();
$number_of_content=0;
$highest_imported_tweet=0;

/*
Read Files @ Import Directory
*/ 

if ( is_dir ( $twitter_import_path ))
{
    if ( $handle = opendir($twitter_import_path) )
    {
        while (($file = readdir($handle)) !== false)
        {
			$list_import_files[]=array($file);
			$number_of_files++;
        }
    closedir($handle);
    }
}
else {
	echo '<p>Error: Import Folder does not exist. Please create folder ../wp-content/uploads/mathilda-import. </p>';
	return;
}

/*
Removing Inconsistant Array Entries
*/

for($i=0; $i<$number_of_files; $i++)
{
	     $unknown_pos=strpos($list_import_files[$i][0], ".");
		 if($unknown_pos === 0)
				{
				unset($list_import_files[$i]);
				}
	}

/* 
Create valid File List 
*/

for($i=0; $i<$number_of_files; $i++)
{
		if ($list_import_files[$i][0] <> 0) {
		
		$list_import_content[$number_of_content][0]=$list_import_files[$i][0];
		$number_of_content++;
		}
	
	}
	
/*
Check Number of Files 
*/
	
if ($number_of_content==0) {
	echo '<p><strong>About Import</strong></p>';
	echo '<p>Unfortunately Twitter API provides access to your latest 3200 tweets only.<br/>';
	echo 'That is why Mathilda Cron can not copy the tweets before.<br/>';
	echo 'With this import function you can load your older tweets into WordPress manually.<br/>';
	echo 'If you have written less than 3200 tweets, the import is not required.</p>';
	echo '<p><strong>Required Steps</strong></p>';
	echo '<p>1. Download your tweet archive from Twitter (Profile/Settings).<br/>';
	echo '2. Upload all files from /data/js/tweets to /wp-content/uploads/mathilda-import.<br/>';
	echo '3. Run the import again.</p>';
	echo '<p><strong>Notice</strong></p>';
	echo '<p>During the process you will see a blank page.<br/>';
	echo 'The import will run several minutes.<br/>';
	echo 'Please wait until you have the finish message at the bottom.<p>';
	return;
}
echo '<p class="mathilda_tools_description">Result</p>';

/*
Show Files to be processed
*/

for($i=0; $i<$number_of_content; $i++)
{
	if($i==0) {echo '<h2>Files for Import</h2><p>';}
	echo 'File ' . $list_import_content[$i][0] . '<br/>';
}
echo '</p><p>' . $number_of_content . ' Files overall.</p>';

/*
Read JSONs
*/

for($i=0; $i<$number_of_content; $i++)
{	
	     $filename_with_path=$twitter_import_path . '/' . $list_import_content[$i][0]; 
         $file_content = file_get_contents($filename_with_path);
		 $list_import_content[$i][1]=$file_content;
}
	
/*
Remove JSON Twitter Präfix
*/

for($i=0; $i<$number_of_content; $i++)
{
$upos=strpos($list_import_content[$i][1], "[");
$list_import_content[$i][1]=substr_replace ( $list_import_content[$i][1] , '' , 0, $upos );
}

/*
File Import Loop
*/

for($k=0; $k<$number_of_content; $k++)
{
	
		// Echo
		if ($k==0) { echo '<h2>File Select</h2>'; }
		echo '<h2>'.$list_import_content[$k][0] . '</h2>';

		// Counter
		$num_tweets=0;
		$num_hashtags=0;
		$num_mentions=0;
		$num_media=0;
		$num_urls=0;

		// Arrays
		$tweet_cache=array();
		$hashtag_cache=array();
		$mention_cache=array();
		$media_cache=array();
		$url_cache=array();

		// Decode JSON
		$json_content=$list_import_content[$k][1];
		$string = json_decode($json_content,$assoc = TRUE);

		/* Tweet Loop */

		foreach($string as $items)
		{
			
				/* Hashtags */
				
				$hashtag_text=false;
				$hashtag_index_s=false;
				$hashtag_index_e=false;
				$hashtags_yes_or_no="FALSE";
				
				if(array_key_exists('0', $items['entities']['hashtags'])) 
				{
					foreach($items['entities']['hashtags'] as $hashtags)
					{	
					$hashtag_text=$hashtags[text];
					$hashtag_index_s=$hashtags[indices][0];
					$hashtag_index_e=$hashtags[indices][1];
					$hashtags_yes_or_no="TRUE";
					$hashtag_cache[]=array($hashtag_text,$hashtag_index_s,$hashtag_index_e,$items['id_str']);
					$num_hashtags=$num_hashtags+1;
					}
				}
			
				/* Mentions */
				
				$mention_useridstr=false;
				$mention_screenname=false;
				$mention_fullname=false;
				$mention_index_start=false;
				$mention_index_end=false;
				$mentions_yes_or_no="FALSE";
				
				if(array_key_exists('0', $items['entities']['user_mentions'])) 
				{
					foreach($items['entities']['user_mentions'] as $mentions)
					{	
					$mention_useridstr=$mentions[id_str];
					$mention_screenname=$mentions[screen_name];
					$mention_fullname=$mentions[name];
					$mention_index_start=$mentions[indices][0];
					$mention_index_end=$mentions[indices][1];
					$mentions_yes_or_no="TRUE";
					$mention_cache[]=array($mention_useridstr,$mention_screenname,$mention_fullname,$mention_index_start,$mention_index_end,$items['id_str']);
					$num_mentions=$num_mentions+1;
					}
				}
			
				/* URLS */
				
				$url_tco=false;
				$url_extended=false;
				$url_display=false;
				$url_index_start=false;
				$url_index_end=false;
				$urls_yes_or_no="FALSE";
				
				if(array_key_exists('0', $items['entities']['urls'])) 
				{
					foreach($items['entities']['urls'] as $urls)
					{	
					$url_tco=$urls[url];
					$url_extended=$urls[expanded_url];
					$url_display=$urls[display_url];
					$url_index_start=$urls[indices][0];
					$url_index_end=$urls[indices][1];
					$urls_yes_or_no="TRUE";
					$url_cache[]=array($url_tco,$url_extended,$url_display,$url_index_start,$url_index_end,$items['id_str']);
					$num_urls=$num_urls+1;
					}
				}
			
				/* Media */
				
				$media_idstr=false;
				$media_mediaurl=false;
				$media_mediaurlhttps=false;
				$media_url=false;
				$media_displayurl=false;
				$media_extendedurl=false;
				$media_size_w=false;
				$media_size_h=false;
				$media_size_resize=false;	
				$media_type=false;
				$index_start=false;
				$index_end=false;
				$media_yes_or_no="FALSE";
				
				if(isset($items['entities']['media'])) 
				{
					foreach($items['entities']['media'] as $images)
					{	

					$media_idstr=$images[id_str];
					$media_mediaurl=$images[media_url];
					$media_mediaurlhttps=$images[media_url_https];
					$media_url=$images[url];
					$media_displayurl=$images[display_url];
					$media_extendedurl=$images[expanded_url];
					
					/*
					Twitter Export JSON does not contain the size-array (small, medium, large, xxx)
					Search Largest Value Pair
					*/

					$select_size=0;
					for ($x=0; $x<4; $x++)
					{
						if($images[sizes][$select_size][w]<$images[sizes][($x+1)][w])
						{
						$select_size=($x+1);
						}	
					}
					
					$media_size_w=$images[sizes][$select_size][w];
					$media_size_h=$images[sizes][$select_size][h];
					$media_size_resize=$images[sizes][$select_size][resize];
					$media_type='photo';
					$index_start=$images[indices][0];
					$index_end=$images[indices][1];
				
					// Get the file

					$image_url_large=$media_mediaurl.':large';
					$content = file_get_contents($image_url_large);
					
					// Store on filesystem
					
					$filename = substr(strrchr($media_mediaurl, "/"), 1);
					$mathilda_images_target = $mathilda_images_dirname . $filename;
					
					if (!file_exists($mathilda_images_target))
					{
					$fp = fopen($mathilda_images_target, "w");
					fwrite($fp, $content);
					fclose($fp);		
					}
					$loaded='TRUE';
					
					// Update Media Array
					
					$media_cache[]=array($media_idstr,$media_mediaurl,$media_mediaurlhttps,$media_url,$media_displayurl,$media_extendedurl, $media_size_w, $media_size_h, $media_size_resize,$media_type, $index_start, $index_end,$items['id_str'],$filename,$loaded);
					$media_yes_or_no="TRUE";
					$num_media=$num_media+1;
					}
				}
			
				/* Tweets */
				
				$tweet_cache[]=array($num_tweets,
										$items['id_str'],
										$items['text'],
										$items['created_at'],
										$hashtags_yes_or_no,
										$mentions_yes_or_no,
										$media_yes_or_no,
										$urls_yes_or_no
										);
										
				$num_tweets=$num_tweets+1;

		} // End Tweet Loop

		/* 
		Update Database 
		*/

		/* Tweets */

		for($i=0; $i < $num_tweets; $i++) 
		{

			// Convert Date

			$tweet_cache[$i][3]=str_replace ( '-' , '' , $tweet_cache[$i][3] );
			$tweet_cache[$i][3]=str_replace ( ' ' , '' , $tweet_cache[$i][3] );
			$tweet_cache[$i][3]=str_replace ( ':' , '' , $tweet_cache[$i][3] );
			$tweet_cache[$i][3]=str_replace ( '+0000' , '' , $tweet_cache[$i][3] );
			
			// Update Tweets
			
			if ($i==0) { echo '<h2>Tweet Import</h2>'; }
			
			$tweet_existing=2;
			$tweet_existing=mathilda_is_tweetid_existing($tweet_cache[$i][1]);
			
			if($tweet_existing==1) {
					echo ($i+1) . ': Tweet ' . $tweet_cache[$i][1] . ' already exists.<br/>';
			}
			else {
					mathilda_add_tweets($tweet_cache[$i][1],$tweet_cache[$i][2],$tweet_cache[$i][3],$tweet_cache[$i][4],$tweet_cache[$i][5], $tweet_cache[$i][6], $tweet_cache[$i][7]);		
					echo ($i+1) . ': Tweet ' . $tweet_cache[$i][1] . ' imported.<br/>';
			}
			
			if($tweet_cache[$i][1]>$highest_imported_tweet) {
				$highest_imported_tweet=$tweet_cache[$i][1];
			}
		
		}

		/* Hashtags */

		if($num_hashtags>0)
		{	
			for($i=0; $i < $num_hashtags; $i++) 
			{
				if ($i==0) { echo '<h2>Hashtag Import</h2>'; }
				
				$hashtag_existing=2;
				$hashtag_existing=mathilda_is_hashtag_existing($hashtag_cache[$i][3], $hashtag_cache[$i][2]);
				
				if($hashtag_existing==1) {
					echo ($i+1) . ': Hashtag ' . $hashtag_cache[$i][0] . ' already exists.<br/>';
				}
				else {
					mathilda_add_hashtags($hashtag_cache[$i][0],$hashtag_cache[$i][1],$hashtag_cache[$i][2],$hashtag_cache[$i][3]);
					echo ($i+1) . ': Hashtag ' . $hashtag_cache[$i][0] . ' imported.<br/>';
				}
			}
		}

		/* Mentions */

		if($num_mentions>0)
		{	
			for($i=0; $i < $num_mentions; $i++) 
			{
				if ($i==0) { echo '<h2>Mention Import</h2>'; }
				
				$mention_existing=2;
				$mention_existing=mathilda_is_mention_existing($mention_cache[$i][5], $mention_cache[$i][4]);
				
				if($mention_existing==1) {
					echo ($i+1) . ': Mention ' . $mention_cache[$i][1] . ' already exists.<br/>';
				}
				else {
					mathilda_add_mentions($mention_cache[$i][0],$mention_cache[$i][1],$mention_cache[$i][2],$mention_cache[$i][3],$mention_cache[$i][4],$mention_cache[$i][5]);
					echo ($i+1) . ': Mention ' . $mention_cache[$i][1] . ' imported.<br/>';
				}
			}
		}

		/* Media */

		if($num_media>0)
		{	
			for($i=0; $i < $num_media; $i++) 
			{
				if ($i==0) { echo '<h2>Media Import</h2>'; }
				
				$media_existing=2;
				$media_existing=mathilda_is_media_existing($media_cache[$i][12]);
				
				if($media_existing==1) {
					echo ($i+1) . ': Media ' . $media_cache[$i][1] . ' already exists.<br/>';
				}
				else {
					mathilda_add_media($media_cache[$i][0],$media_cache[$i][1],$media_cache[$i][2],$media_cache[$i][3],$media_cache[$i][4],$media_cache[$i][5],$media_cache[$i][6],$media_cache[$i][7],$media_cache[$i][8],$media_cache[$i][9],$media_cache[$i][10],$media_cache[$i][11],$media_cache[$i][12],$media_cache[$i][13],$media_cache[$i][14]);
					echo ($i+1) . ': Media ' . $media_cache[$i][1] . ' imported.<br/>';
				}
			}
		}

		/* URLS */

		if($num_urls>0)
		{	
			for($i=0; $i < $num_urls; $i++) 
			{
				if ($i==0) { echo '<h2>URL Import</h2>'; }
				
				$url_existing=2;
				$url_existing=mathilda_is_url_existing($url_cache[$i][5], $url_cache[$i][4]);
				
				if($url_existing==1) {
					echo ($i+1) . ': Media ' . $media_cache[$i][1] . ' already exists.<br/>';
				}
				else {
					mathilda_add_urls($url_cache[$i][0],$url_cache[$i][1],$url_cache[$i][2],$url_cache[$i][3],$url_cache[$i][4],$url_cache[$i][5]);
					echo ($i+1) . ': URL ' . $url_cache[$i][1] . ' imported.<br/>';
				}
			}
		}

} // End File Loop

/* 
Update Meta 
*/

$json_import=1;
$latest_tweet=mathilda_latest_tweet();
$number_of_tweets=mathilda_tweets_count();
update_option('mathilda_import', $json_import);
update_option('mathilda_latest_tweet', $latest_tweet);
update_option('mathilda_tweets_count', $number_of_tweets);
update_option('mathilda_highest_imported_tweet', $highest_imported_tweet);

/*
HTML Close 
*/

echo '<p><strong>Import done!</strong></p>';

}

?>