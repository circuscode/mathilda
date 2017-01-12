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
    echo '<p>Hello, I am Mathilda and you have posted the following.</p>';
    echo '<p>' . mathilda_tweets_count() . " Tweets<br/>";
    echo mathilda_images_count() . " Images<br/>";
    echo mathilda_mentions_count() . " Mentions<br/>";
    echo mathilda_hashtags_count() . " Hashtags<br/>";
    echo mathilda_urls_count() . " Links</p>";
}

?>