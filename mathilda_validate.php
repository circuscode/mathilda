<?php

/* Validate Twitter API Authentification Options */ 

function mathilda_valdidate_twitterauthdata ($oauthdata) {
    
    if( ($oauthdata['oauth_access_token']=='') OR ($oauthdata['oauth_access_token_secret']=='') OR ($oauthdata['consumer_key']=='') OR ($oauthdata['consumer_secret']=='') )
    {
        $returncode=false;
    }
    else {
        $returncode=true;
    }
    return $returncode;
}

/*  
Validate Twitter User
*/

function mathilda_valdidate_twitteruser ($twitteruser) {
    
    if($twitteruser=='')
    {
        $returncode=false;
    }
    else {
        $returncode=true;
    }
    return $returncode;
}

/* Validate Input Slug */

function mathilda_validate_slug ( $slug ) {

     if ($slug == '') {
        add_settings_error( 'mathilda-options', 'invalid-slug', 'Mathilda Slug is required. Field cannot be empty.' );
        $output=get_option( 'mathilda_slug' );
     }
     else {
        $output = $slug;
        update_option('mathilda_slug_is_changed',"1");
     }
     return $output;
} 

/* Validate Input: Tweets on Page */

function mathilda_validate_tweetsonpage ( $tweetsonpage ) {
    
    $output = $tweetsonpage;
    
    if ( $tweetsonpage < 10 ) {
        $output=get_option( 'mathilda_tweets_on_page' );
        add_settings_error( 'mathilda-options', 'invalid-tweetsonpage', 'Less than 10 Tweets are not allowed for Tweets on Page.' );
    }
    if ( $tweetsonpage > 1000 ) {
        $output=get_option( 'mathilda_tweets_on_page' );
        add_settings_error( 'mathilda-options', 'invalid-tweetsonpage', 'More than 1000 Tweets are not allowed for Tweets on Page.' );
    }
  
    return $output;
} 

/* Format Input: Twitter Login */

function mathilda_format_twitterlogin ( $twitterlogin ) {
    
    $output = $twitterlogin; 
    $at_pos=strpos($twitterlogin, "@");
    
    if (!($twitterlogin=='')) {
        if ( !($at_pos === 0) ) {
        $output="@".$twitterlogin;
        }
     }
  
     return $output;
} 

?>