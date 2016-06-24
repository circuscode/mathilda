<?php

/* 
Mathilda Options 
*/

function mathilda_options_menu() {
add_options_page('Mathilda', 'Mathilda', 'manage_options', 'mathilda-options', "mathilda_options_content");
}

add_action( 'admin_menu', 'mathilda_options_menu');

/*
Options Page
*/

function mathilda_options_content() {
	
	$mathilda_slug_changed=get_option('mathilda_slug_is_changed');
	if($mathilda_slug_changed==1)
	{flush_rewrite_rules();}
	
	echo '
	<div class="wrap">
	<h1>Options › Mathilda</h1>
	<p class="mathilda_settings">All Settings<br/>&nbsp;</p>
	
	<form method="post" action="options.php">';
	
	do_settings_sections( 'mathilda-options' );
	settings_fields( 'mathilda_settings' );
	submit_button();

	echo '</form></div><div class="clear"></div>';
}

/*
Fields
#*/

function mathilda_options_display_oauth_access_token()
{
	echo '<input class="regular-text" type="text" name="mathilda_oauth_access_token" id="mathilda_oauth_access_token" value="'. get_option('mathilda_oauth_access_token') .'"/>';
}

function mathilda_options_display_oauth_access_token_secret()
{
	echo '<input class="regular-text" type="text" name="mathilda_oauth_access_token_secret" id="mathilda_oauth_access_token_secret" value="'. get_option('mathilda_oauth_access_token_secret') .'"/>';
}

function mathilda_options_display_consumer_key()
{
	echo '<input class="regular-text" type="text" name="mathilda_consumer_key" id="mathilda_consumer_key" value="'. get_option('mathilda_consumer_key') .'"/>';
}

function mathilda_options_display_consumer_secret()
{
	echo '<input class="regular-text" type="text" name="mathilda_consumer_secret" id="mathilda_consumer_secret" value="'. get_option('mathilda_consumer_secret') .'"/>';
}

function mathilda_options_display_twitter_user()
{
	echo '<input type="text" name="mathilda_twitter_user" id="mathilda_twitter_user" value="'. get_option('mathilda_twitter_user') .'"/>';
}

function mathilda_options_display_slug()
{
	echo '<input type="text" name="mathilda_slug" id="mathilda_slug" value="'. get_option('mathilda_slug') .'"/>';
}

function mathilda_options_display_tweets_on_page()
{
	echo '<input type="text" name="mathilda_tweets_on_page" id="mathilda_tweets_on_page" value="'. get_option('mathilda_tweets_on_page') .'"/>';
}

function mathilda_options_display_show_replies()
{
	 
	echo '<input type="checkbox" name="mathilda_replies" value="1" ' .  checked(1, get_option('mathilda_replies'), false) . '/>'; 
}	

/* 
Sections
*/

function mathilda_options_display_twitterapi_description()
{ echo '<p>Following data is required to authenticate with the Twitter API</p>'; }

function mathilda_options_display_plugin_description()
{ echo '<p>Basic Settings</p>'; }

function mathilda_options_display_userinterface_description()
{ echo '<p>FrondEnd Settings</p>'; }

/* 
Definitions
*/

// Twitter API Settings

function mathilda_options_twitterapi_display()
{
	
	add_settings_section("twitterapi_settings_section", "Twitter API", "mathilda_options_display_twitterapi_description", "mathilda-options");
	
	add_settings_field("mathilda_oauth_access_token", "OAUTH Access Token", "mathilda_options_display_oauth_access_token", "mathilda-options", "twitterapi_settings_section");
	add_settings_field("mathilda_oauth_access_secret", "OAUTH Access Token Secret", "mathilda_options_display_oauth_access_token_secret", "mathilda-options", "twitterapi_settings_section");
	add_settings_field("mathilda_consumer_key", "Consumer Key", "mathilda_options_display_consumer_key", "mathilda-options", "twitterapi_settings_section");
	add_settings_field("mathilda_consumer_secret", "Consumer Secret", "mathilda_options_display_consumer_secret", "mathilda-options", "twitterapi_settings_section");
	
	register_setting("mathilda_settings", "mathilda_oauth_access_token");
	register_setting("mathilda_settings", "mathilda_oauth_access_token_secret");
	register_setting("mathilda_settings", "mathilda_consumer_key");
	register_setting("mathilda_settings", "mathilda_consumer_secret");

}

// Plugin Basic Settings 

function mathilda_options_plugin_display()
{
	
	add_settings_section("plugin_settings_section", "Plugin", "mathilda_options_display_plugin_description", "mathilda-options");
	
	add_settings_field("mathilda_twitter_user", "Twitter Account", "mathilda_options_display_twitter_user", "mathilda-options", "plugin_settings_section");
	add_settings_field("mathilda_slug", "Slug", "mathilda_options_display_slug", "mathilda-options", "plugin_settings_section");
	
	register_setting("mathilda_settings", "mathilda_twitter_user", "mathilda_format_twitterlogin");
	register_setting("mathilda_settings", "mathilda_slug", "mathilda_validate_slug");

}

// User Interface Settings 

function mathilda_options_userinterface_display()
{
	
	add_settings_section("userinterface_settings_section", "User Interface", "mathilda_options_display_userinterface_description", "mathilda-options");
	
	add_settings_field("mathilda_tweets_on_page", "Tweets on Page", "mathilda_options_display_tweets_on_page", "mathilda-options", "userinterface_settings_section");
	add_settings_field("mathilda_replies", "Show Replies?", "mathilda_options_display_show_replies", "mathilda-options", "userinterface_settings_section");
	
	register_setting("mathilda_settings", "mathilda_tweets_on_page", "mathilda_validate_tweetsonpage");
	register_setting("mathilda_settings", "mathilda_replies");

}

/*
Actions
*/

add_action("admin_init", "mathilda_options_twitterapi_display");
add_action("admin_init", "mathilda_options_plugin_display");
add_action("admin_init", "mathilda_options_userinterface_display");

?>