<?php

/*
Plugin Name:  Mathilda
Plugin URI:   https://www.unmus.de/wordpress-plugin-mathilda/
Description:  Mathilda brings back control of your tweets. 
Version:	  0.4
Author:       Marco Hitschler
Author URI:   https://www.unmus.de/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
Text Domain:  mathilda
*/

/*
Basic Setup
*/

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
require_once('mathilda_settings.php');
require_once('mathilda_database.php');
require_once('mathilda_painting.php'); 
require_once('mathilda_tools.php');
require_once('mathilda_filter.php');
require_once('mathilda_export.php');
require_once('mathilda_developer.php');
require_once('mathilda_validate.php');
require_once('mathilda_utilities.php');
require_once('mathilda_twitterapi.php');
require_once('mathilda_cron.php');
require_once('mathilda_import.php');
require_once('mathilda_scripting.php');

/* 
Activate Plugin
*/

function mathilda_activate () {
		
	if (! get_option('mathilda_activated') ) {
	
	/* Initialize Settings */
	
	add_option('mathilda_oauth_access_token',"");
	add_option('mathilda_oauth_access_token_secret', ""); 
	add_option('mathilda_consumer_key', ""); 
	add_option('mathilda_consumer_secret', ""); 
	add_option('mathilda_twitter_user', ""); 
	add_option('mathilda_num_tweets_fetch_call', "200"); 
	add_option('mathilda_num_fetches', "17"); 
	add_option('mathilda_retweets', "0"); 
	add_option('mathilda_replies', "0"); 
	add_option('mathilda_initial_load', "0"); 
	add_option('mathilda_latest_tweet', ''); 
	add_option('mathilda_tweets_on_page', "300"); 
	add_option('mathilda_slug', "tweets"); 
	add_option('mathilda_tweets_count', "0"); 
	add_option('mathilda_activated', "1"); 
	add_option('mathilda_database_version', "1");
	add_option('mathilda_plugin_version', "2");
	add_option('mathilda_import', "0");
	add_option('mathilda_slug_is_changed', "0");
	add_option('mathilda_cron_period', "900");
	add_option('mathilda_highest_imported_tweet', '');
	add_option('mathilda_navigation', 'Standard');
	
	/* Create Mathilda Tables */
	
	mathilda_tables_create();
	
	/* Create Directories */
	
	$upload_dir = wp_upload_dir();
	
	$twitter_apidata_dirname="mathilda-twitterapidata";
	$twitter_apidata_dirwithpath = $upload_dir['basedir'].'/'.$twitter_apidata_dirname;
	if ( ! file_exists( $twitter_apidata_dirwithpath ) ) {
		wp_mkdir_p( $twitter_apidata_dirwithpath );
		}
		
	$mathilda_images_dirname="mathilda-images";
	$mathilda_images_dirwithpath = $upload_dir['basedir'].'/'.$mathilda_images_dirname;
	if ( ! file_exists( $mathilda_images_dirwithpath ) ) {
		wp_mkdir_p( $mathilda_images_dirwithpath );
		}
		
	$twitter_import_directory="mathilda-import";
	$twitter_import_path = $upload_dir['basedir'].'/'.$twitter_import_directory;
	if ( ! file_exists( $twitter_import_path ) ) {
		wp_mkdir_p( $twitter_import_path );
		}
		
	$twitter_export_directory="mathilda-export";
	$twitter_export_path = $upload_dir['basedir'].'/'.$twitter_export_directory;
	if ( ! file_exists( $twitter_export_path ) ) {
		wp_mkdir_p( $twitter_export_path );
		}

	/* Rewrite Rules Refresh */

	flush_rewrite_rules();

	} 
	
}

register_activation_hook( __FILE__ , 'mathilda_activate' );

/* 
Deactivate
*/

function mathilda_deactivate () {
	
	flush_rewrite_rules();
	
	$timestamp = wp_next_scheduled( 'mathilda_cron_hook' );
   	wp_unschedule_event($timestamp, 'mathilda_cron_hook' );
	
}

register_deactivation_hook( __FILE__ , 'mathilda_deactivate' );

/* 
Delete
*/

function mathilda_delete () {

		if ( get_option('mathilda_activated') ) {

		/* Delete Options */

		delete_option('mathilda_oauth_access_token');
		delete_option('mathilda_oauth_access_token_secret');
		delete_option('mathilda_consumer_key');
		delete_option('mathilda_consumer_secret');
		delete_option('mathilda_twitter_user');
		delete_option('mathilda_num_tweets_fetch_call');
		delete_option('mathilda_num_fetches');
		delete_option('mathilda_retweets');
		delete_option('mathilda_replies');
		delete_option('mathilda_initial_load');
		delete_option('mathilda_latest_tweet');
		delete_option('mathilda_tweets_on_page');
		delete_option('mathilda_slug');
		delete_option('mathilda_tweets_count');
		delete_option('mathilda_activated');
		delete_option('mathilda_database_version');
		delete_option('mathilda_plugin_version');
		delete_option('mathilda_tweets_count');
		delete_option('mathilda_import');
		delete_option('mathilda_slug_is_changed');
		delete_option('mathilda_cron_period');
		delete_option('mathilda_highest_imported_tweet');
		delete_option('mathilda_navigation');
		
		/* Delete Tables */
		
		mathilda_tables_delete();
		
		// Directories will not removed
		
	}
	
}

register_uninstall_hook( __FILE__ , 'mathilda_delete' );

/* 
Reset
*/

function mathilda_reset () {
	
	update_option('mathilda_num_tweets_fetch_call', "200"); 
	update_option('mathilda_num_fetches', "17"); 
	update_option('mathilda_retweets', "0"); 
	update_option('mathilda_replies', "0");
	update_option('mathilda_initial_load', '0'); 
	update_option('mathilda_latest_tweet', '');
	update_option('mathilda_tweets_on_page', "300"); 
	update_option('mathilda_slug', "tweets"); 
	update_option('mathilda_activated', "1"); 
	update_option('mathilda_import', '0');
	update_option('mathilda_cron_period', "900");
	update_option('mathilda_navigation', "Standard");
	
	mathilda_fresh_tables();
	
	$message='All data has been deleted.<br/>Default settings have been restored.<br/>But Images and Log Files were not removed.';
	return $message;
	
}

/* 
Update
*/

function mathilda_update () {
	
    $mathilda_previous_version = get_option('mathilda_plugin_version');
	
	/* Update Process Version 0.3 */ 
    if($mathilda_previous_version==1) {
	add_option('mathilda_slug_is_changed', "0");
	update_option('mathilda_plugin_version', "2");	   
	}

	/* Update Process Version 0.4 */
	if($mathilda_previous_version==2) {
	add_option('mathilda_cron_period', "900");
	update_option('mathilda_plugin_version', "3");
	$timestamp = wp_next_scheduled( 'mathilda_cron_hook' );
   	wp_unschedule_event($timestamp, 'mathilda_cron_hook' );
	$mathilda_replies_flag=get_option('mathilda_replies');
	if( $mathilda_replies_flag == FALSE ) {update_option('mathilda_replies','0');}	 
	add_option('mathilda_navigation', 'Numbering');  
	}
	
}

add_action( 'plugins_loaded', 'mathilda_update' );

/* 
Template 
*/

function mathilda_template($content) {
	
	// Run Mathilda
	if ( mathilda_is_tweet_page() ) {
		
		// Prepare
		$alliswell=mathilda_run_yes_or_no();
		$mathilda_pages_amount=mathilda_pages();
		$mathilda_show_page=mathilda_which_page();
		$mathilda_content_html='';
		
		// Template
		require_once('mathilda_template.php');
		return $content . $mathilda_content_html;
		
	} else {
		
		// Content without Tweets 	
		return $content;
		
	} 
	
} 
add_filter ('the_content', 'mathilda_template');

/* 
CSS @ Mathilda
*/

function mathilda_css() {
			if ( mathilda_is_tweet_page() )
			{
			$add_css='<link rel="stylesheet" id="mathilda-css" href="'. plugins_url() .'/mathilda/mathilda_tweets.css" type="text/css" media="all">';
			echo $add_css;
			}
}

add_action('wp_head','mathilda_css');

function mathilda_admin_css() {
			$add_css='<link rel="stylesheet" id="mathilda-css" href="'. plugins_url() .'/mathilda/mathilda_options.css" type="text/css" media="all">';
			echo $add_css;
}
add_action( 'admin_head', 'mathilda_admin_css' );

function mathilda_class( $classes ) {

	if ( mathilda_is_tweet_page() ) {
        $classes[] = 'mathilda-is-here';
        return $classes;
    }
	else {
		$classes[] = 'mathilda-is-not-here';
        return $classes;
	}
}
add_filter( 'body_class', 'mathilda_class' );

/* 
Query Vars @ Mathilda 
*/

function mathilda_urlvars( $qvars )
{
$qvars[] = 'mathilda';
return $qvars;
}

add_filter('query_vars', 'mathilda_urlvars' );

/*
Run Mathilda - Yes or No?
*/

function mathilda_run_yes_or_no() {
	
	if( ( get_option('mathilda_initial_load') == 0 ) AND ( get_option('mathilda_import') == 0) ) {
		return false;
	}
	else {
		return true;
	}
	
}

/*
Mathilda Which Page 
*/	

function mathilda_which_page() {
	
	$mathilda_show_page=get_query_var( 'mathilda');
	if ($mathilda_show_page=="") {$mathilda_show_page="1/";}
	$mathilda_show_page=intval ($mathilda_show_page);
	return $mathilda_show_page;

}

/* 
Mathilda Loop
*/

function mathilda_loop($mathilda_show_page) {
	
	$loop_output=''; 
	$me=get_option('mathilda_twitter_user');
	$tweets_on_page=get_option('mathilda_tweets_on_page');
	
	/* Build Tweet Cache */
	$tweet_cache=array();
	$tweet_cache=mathilda_read_tweets($tweets_on_page, $mathilda_show_page);
	$num_tweets=count($tweet_cache);
	
	/* Apply Filter */
	$tweet_filter_cache=array();
	$tweet_filter_cache=mathilda_tweet_filter($tweet_cache, $num_tweets);
	
	/* Display Tweets */
	for($i=0; $i < $num_tweets; $i++) 
	{
		if(isset($tweet_filter_cache[$i][0]))
		{
			$loop_output.=mathilda_tweet_paint($tweet_filter_cache[$i][0],
								      $tweet_filter_cache[$i][1],
								 	  $tweet_filter_cache[$i][2],
								 	  $me,
								 	  $tweet_filter_cache[$i][5],
								 	  $tweet_filter_cache[$i][4],
								      $tweet_filter_cache[$i][6],
								 	  $tweet_filter_cache[$i][3]
								      );
		}
	}
	
	return $loop_output;
	
}

/* 
Rewrite Rules
*/

function mathilda_insert_rewrite( $rules ) {
	
$slug = get_option( 'mathilda_slug' );
$newrules = array();
$newrules['('.$slug.')/(.+)$'] = 'index.php?pagename=$matches[1]&mathilda=$matches[2]';
return $newrules + $rules;

}
add_filter( 'rewrite_rules_array','mathilda_insert_rewrite' );

/* 
The unbelievable shortcode 
*/

function mathilda_shortcode() {
	
	// Code Code Code
	
}

add_shortcode('mathilda','mathilda_shortcode');

/* Mathilda @ Auf einen Blick */
 
function mathilda_glance_counter() {
	
	$mathilda_tweets_count=get_option('mathilda_tweets_count');
	$text='<li class="post-count"><a href="tools.php?page=mathilda-tools-menu">';
	$text.=$mathilda_tweets_count . ' Tweets</a</a></li>';
	echo $text;

}

add_filter( 'dashboard_glance_items', 'mathilda_glance_counter');

/* Mathilda Cron Interval */

function mathilda_cron_interval( $schedules ) {

	$period=get_option('mathilda_cron_period');

    $schedules['mathilda_duration'] = array(
        'interval' => $period,
        'display'  => esc_html__( 'Mathilda Custom Duration' ),
    );
 
    return $schedules;
}

add_filter( 'cron_schedules', 'mathilda_cron_interval' );

/* Mathilda WP-Cron */

function mathilda_cron_execute() {
	
	$initial_load = get_option('mathilda_initial_load');
	if($initial_load==1) {
	mathilda_cron_script();
	}

}

add_action( 'mathilda_cron_hook', 'mathilda_cron_execute' );

/* Schedule Mathilda Cron */

if( !wp_next_scheduled( 'mathilda_cron_hook' ) ) {
	wp_schedule_event( time(), 'mathilda_duration', 'mathilda_cron_hook' );
}	

?>