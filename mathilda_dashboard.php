<?php

// Security
if (!defined('ABSPATH')) { exit; }

/*
Mathilda Dashboard
*/

/* Mathilda @ Auf einen Blick */
 
function mathilda_glance_counter() {
	
	$mathilda_tweets_count=get_option('mathilda_tweets_count');
	$text='<li class="post-count"><a href="tools.php?page=mathilda-tools-menu">';
	$text.=$mathilda_tweets_count . ' Tweets</a</a></li>';
	echo $text;

}

add_filter( 'dashboard_glance_items', 'mathilda_glance_counter');

/*
Mathilda Dashboard Widget
*/

// Register the Mathilda Dashboard Widget
function register_mathilda_dashboard_widget_reporting() {
	wp_add_dashboard_widget(
		'mathilda_dashboard_widget_reporting',
		'Mathilda Report',
		'mathilda_dashboard_widget_reporting_display'
	);

}
add_action( 'wp_dashboard_setup', 'register_mathilda_dashboard_widget_reporting' );

// Mathilda Dashboard User Interface
function mathilda_dashboard_widget_reporting_display() {

	if(mathilda_select_count()==0) {
		echo '<p>Hello, I am Mathilda and I will show you some tweet statistics here, after you have loaded your tweets with me.</p>';
	} else {
		echo '<p>Hello, I am Mathilda and you have posted the following.</p>';
		echo '<table>';
		echo '<tr><td>' . mathilda_tweets_count() . '&nbsp;&nbsp;&nbsp;</td><td>Tweets</td></tr>';
		echo '<tr><td>' . mathilda_retweets_count() . '</td><td>Retweets</td></tr>';
		echo '<tr><td>' . mathilda_replies_count() . '</td><td>Replies</td></tr>';
		echo '<tr><td>' . mathilda_quotes_count() . '</td><td>Quotes</td></tr>';
		echo '<tr><td>' . mathilda_images_count() . '</td><td>Images</td></tr>';
		echo '<tr><td>' . mathilda_mentions_count() . '&nbsp;&nbsp;&nbsp;</td><td>Mentions</td></tr>';
		echo '<tr><td>' . mathilda_hashtags_count() . '&nbsp;&nbsp;&nbsp;</td><td>Hashtags</td></tr>';
		echo '<tr><td>' . mathilda_urls_count() . '</td><td>Links</td></tr>';
		echo '</table>';
	}
}

// mathilda_update_notice
// The function brings a message, if activites are required
// Input: none
// Output: none

function mathilda_update_notice(){
    global $pagenow;
    if ( $pagenow == 'index.php' OR $pagenow == 'tools.php' OR $pagenow == 'options-general.php' OR $pagenow == 'plugins.php') {
		if(get_option('mathilda_plugin_version')==7) {
			if(mathilda_update_seven_aftercheck()==false) {
				echo '<div class="notice notice-warning is-dismissible"><p>';
         		echo 'Mathilda Update Notice: Please reset Mathilda and reload your data from twitter! <a href="'.admin_url().'tools.php?page=mathilda-tools-menu">Yes, I do it now!</a>';
         		echo '</p></div>';
			}
		}
    }
}

add_action('admin_notices', 'mathilda_update_notice');

/*
WordPress Multisite Notice
*/

// Shows Message Box on Child Sites within WordPress Multisite Network
// Input: None
// Output: None

function mathilda_multisite_notice(){
    
	if ( is_multisite() ) { 
		if (get_current_blog_id()>1) {

			global $pagenow;

			if ( $pagenow == 'index.php' OR $pagenow == 'tools.php' OR $pagenow == 'options-general.php' OR $pagenow == 'plugins.php') {

				echo '<div class="notice notice-warning is-dismissible"><p>';
				echo '<strong>Mathilda Plugin Notice:</strong><br/>Mathilda does not support child sites within WordPress Networks. Please deactivate the plugin on this instance! <a href="'.get_admin_url( get_current_blog_id() ) .'plugins.php?">Understood, I do it now!</a>';
				echo '</p></div>';	
					
			}
		}
	} 
    
}

add_action('admin_notices', 'mathilda_multisite_notice');

?>