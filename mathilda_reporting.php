<?php

// Security
if (!defined('ABSPATH')) { exit; }

/*
Mathilda Reporting
*/

/* 
Number of Tweets 
*/

function mathilda_tweets_count() {
	global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_tweets';
	return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

/* 
Number of Images 
*/

function mathilda_images_count() {
	global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_media';
	return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

/* 
Number of Mentions 
*/

function mathilda_mentions_count() {
	global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_mentions';
	return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

/* 
Number of Hashtags 
*/

function mathilda_hashtags_count() {
	global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_hashtags';
	return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

/* 
Number of Links 
*/

function mathilda_urls_count() {
	global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_urls';
	return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

?>