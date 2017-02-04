<?php 

/* 
Security
*/

if (!defined('ABSPATH')) { exit; }

/*
Mathilda Update Related Functions
*/

// mathilda_update
// Description: Runs the update procedure
// Input: none
// Output: none

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

	/* Update Process Version 0.4.1 */
    if($mathilda_previous_version==3) {
	update_option('mathilda_plugin_version', "4");
	}

	/* Update Process Version 0.4.2 */
    if($mathilda_previous_version==4) {
	update_option('mathilda_plugin_version', "5");
	}

	/* Update Process Version 0.5 */
    if($mathilda_previous_version==5) {
	update_option('mathilda_plugin_version', "6");
	add_option('mathilda_hyperlink_rendering', 'Longlink');
	add_option('mathilda_css', '0');
	}

	/* Update Process Version 0.6 */
    if($mathilda_previous_version==6) {
	update_option('mathilda_plugin_version', "7");
	add_option('mathilda_select_amount',"0");
	add_option('mathilda_quotes', "1");

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "mathilda_tweets";
	$sql = "CREATE TABLE $table_name (
	mathilda_tweet_id bigint NOT NULL AUTO_INCREMENT,
	mathilda_tweet_date varchar(14) NOT NULL,
	mathilda_tweet_content varchar(300) NOT NULL,
	mathilda_tweet_twitterid varchar(20) NOT NULL,
	mathilda_tweet_hashtags varchar(5) NOT NULL,
	mathilda_tweet_mentions varchar(5) NOT NULL,
	mathilda_tweet_media varchar(5) NOT NULL,
	mathilda_tweet_urls varchar(5) NOT NULL,
	mathilda_tweet_truncate varchar(5) NOT NULL,
	mathilda_tweet_reply varchar(5) NOT NULL,
	mathilda_tweet_retweet varchar(5) NOT NULL,
	mathilda_tweet_quote varchar(5) NOT NULL,
	PRIMARY KEY (mathilda_tweet_id),
 	UNIQUE KEY id (mathilda_tweet_twitterid)
	) $charset_collate;";
	dbDelta( $sql );

	$notnull="ND";

	$wpdb->query($wpdb->prepare("UPDATE {$table_name} SET mathilda_tweet_truncate = %s", $notnull));
	$wpdb->query($wpdb->prepare("UPDATE {$table_name} SET mathilda_tweet_reply = %s", $notnull));
	$wpdb->query($wpdb->prepare("UPDATE {$table_name} SET mathilda_tweet_retweet = %s", $notnull));
	$wpdb->query($wpdb->prepare("UPDATE {$table_name} SET mathilda_tweet_quote = %s", $notnull));

	}

}
add_action( 'plugins_loaded', 'mathilda_update' );

// mathilda_update_seven_aftercheck
// Description: Checks, if activities are required after the update to version 7
// Input: none
// Output: true (No Activities required) or false (Activites are required)

function mathilda_update_seven_aftercheck () {
    global $wpdb;
	$table_name=$wpdb->prefix . 'mathilda_tweets';
	$nd=$wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE mathilda_tweet_quote='ND'" );
    if($nd>0) {
        return false;
    } else {
        return true;
    }
}

?>