<?php 

/* 
Security
*/

if (!defined('ABSPATH')) { exit; }

/*
Mathilda Helper Functions
*/

/*
Mathilda Permalink Check
*/

function mathilda_is_tweet_page() {

      $is_mathilda_page=false;
	$mathilda_slug=get_option('mathilda_slug');

	if(! mathilda_is_pretty_permalink_enabled() ) {
		$is_mathilda_slug_equal_page_title= strcasecmp ( $mathilda_slug , get_the_title() );
		if( $is_mathilda_slug_equal_page_title == 0 ) { $is_mathilda_page=true; }
	}

	if ( is_page( get_option('mathilda_slug') ) ) { $is_mathilda_page=true; }

      return $is_mathilda_page;
}

/*
Mathilda Permalink Check
*/

function mathilda_is_pretty_permalink_enabled() {
        if ( get_option('permalink_structure') ) { 
                return true; 
                } 
                else { 
                return false; 
                }
}

/*
Mathilda Healthy Check
*/

function mathilda_healthy_check() {
       
        $upload_dir = wp_upload_dir();
        $upload_dir_url=$upload_dir['basedir'];
  
        $output='<p>';
        
        if (is_writable($upload_dir_url.'/mathilda-export')) {
              $output.='Directory mathilda-export is writable.';
        } else {
              $output.='Error: Directory mathilda-export is not writable.';
        }
        
        $output.='<br/>';
        
        if (is_writable($upload_dir_url.'/mathilda-images')) {
              $output.='Directory mathilda-images is writable.';
        } else {
              $output.='Error: Directory mathilda-images is not writable.';
        }
        
        $output.='<br/>';
        
        if (is_writable($upload_dir_url.'/mathilda-twitterapidata')) {
              $output.='Directory mathilda-twitterapidata is writable.';
        } else {
              $output.='Error: Directory mathilda-twitterapidata is not writable.';
        }
        
        $output.='<br/>';
        
        if (is_writable($upload_dir_url.'/mathilda-import')) {
              $output.='Directory mathilda-import is writable.';
        } else {
              $output.='Error: Directory mathilda-import is not writable.';
        }
        
        $output.='<br/>';
        
        $this_environment_php_execution_time=ini_get('max_execution_time');
        $output.='Max PHP script execution time: '.$this_environment_php_execution_time.' seconds.';
        
        $output.='</p><p><strong>';
        
        if(strpos ( $output , 'not' )) {
            $output.='Mathilda is not doing well! :-(<br/>Please investigate the issues above.';
        } else {
            $output.='Mathilda is doing well! :-)';
       }
        
       $output.='</strong></p>';
       return $output;
}

?>