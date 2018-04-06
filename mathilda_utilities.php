<?php 

/* 
Security
*/

if (!defined('ABSPATH')) { exit; }

/*
Mathilda Helper Functions
*/

/*
Mathilda YouTube Check
*/

// The function checks if an URL is linking to YouTube
// Input: URL
// Output: true or false

function mathilda_is_youtube($url) {

    $mathilda_youtube_url_long="youtube";
    $mathilda_youtube_url_short="youtu.be";
    $mathilda_is_youtube_url;

    if(stripos($url, $mathilda_youtube_url_long)!==false) {
      $mathilda_is_youtube_url=TRUE;
    }
    elseif(stripos($url, $mathilda_youtube_url_short)!==false) {
      $mathilda_is_youtube_url=TRUE;
    }
    else {
      $mathilda_is_youtube_url=FALSE;
    }

    return $mathilda_is_youtube_url;
}

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
Mathilda Import Directory

* Function runs the path of the import directory
* Input: none
* Output: String

*/

function mathilda_get_import_directory() {
      $upload_dir = wp_upload_dir();
      $twitter_import_directory="mathilda-import";
      $twitter_import_path = $upload_dir['basedir'].'/'.$twitter_import_directory;
      return $twitter_import_path;
}

/*
Mathilda Image Directory

* Function runs the path of the image directory
* Input: none
* Output: String

*/

function mathilda_get_image_directory() {
      $upload_dir = wp_upload_dir();
      $mathilda_images_directory="mathilda-images";
      $mathilda_images_path = $upload_dir['basedir'].'/'.$mathilda_images_directory .'/';
      return $mathilda_images_path;
}

/*
Mathilda Import Status

* Function returns the Import Status in Dezimal
* Input: none
* Output: Number

*/

function mathilda_get_import_status() {
      $mathilda_import_numberoffiles=get_option('mathilda_import_numberoffiles');
      $mathilda_import_open=get_option('mathilda_import_open');
      $mathilda_import_done=$mathilda_import_numberoffiles-$mathilda_import_open;
      $mathilda_import_status=(100/$mathilda_import_numberoffiles)*$mathilda_import_done;
      return $mathilda_import_status;
}

/*
Mathilda Import Done files

* Function returns the amount of done files
* Input: none
* Output: Number

*/

function mathilda_get_import_files_done() {
      $mathilda_import_numberoffiles=get_option('mathilda_import_numberoffiles');
      $mathilda_import_open=get_option('mathilda_import_open');
      $mathilda_import_done=$mathilda_import_numberoffiles-$mathilda_import_open;
      return $mathilda_import_done;
}

/* 

Mathilda Healthy Check

* Function runs several consistency checks
* Input: none
* Output: Result as HTML Markup

*/

function mathilda_healthy_check() {
       
        $upload_dir = wp_upload_dir();
        $upload_dir_url=$upload_dir['basedir'];
        
        $healthy_check_result=array(7);

        $output='<p><strong>Checkpoints</strong></p>';
        $output.='<p>';
        
        // 1. Check
        if (is_writable($upload_dir_url.'/mathilda-export')) {
              $output.='Directory mathilda-export is writable.';
              $healthy_check_result[0]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> Directory mathilda-export is not writable.';
              $healthy_check_result[0]=0;
        }
        
        $output.='<br/>';
        
        // 2. Check
        if (is_writable($upload_dir_url.'/mathilda-images')) {
              $output.='Directory mathilda-images is writable.';
              $healthy_check_result[1]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> Directory mathilda-images is not writable.';
              $healthy_check_result[1]=0;
        }
        
        $output.='<br/>';
        
        // 3. Check
        if (is_writable($upload_dir_url.'/mathilda-twitterapidata')) {
              $output.='Directory mathilda-twitterapidata is writable.';
              $healthy_check_result[2]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> Directory mathilda-twitterapidata is not writable.';
              $healthy_check_result[2]=0;
        }
        
        $output.='<br/>';
        
        // 4. Check
        if (is_writable($upload_dir_url.'/mathilda-import')) {
              $output.='Directory mathilda-import is writable.';
              $healthy_check_result[3]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> Directory mathilda-import is not writable.';
              $healthy_check_result[3]=0;
        }
        
        $output.='<br/>';

        // 5. Check
        if (mathilda_update_seven_aftercheck()==true)  {
              $output.='Data are consistent.';
              $healthy_check_result[4]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> Data are inconsistent. Please reset Mathilda and reload the data again.';
              $healthy_check_result[4]=0;
        }

        $output.='<br/>';

        // 6. Check
        if (ini_get('allow_url_fopen'))  {
              $output.='PHP option allow_url_fopen is set to TRUE.';
              $healthy_check_result[5]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-error">&nbsp;Error:&nbsp;</span> PHP option allow_url_fopen is set to FALSE (0) on the webhosting environment. Please change to TRUE (1).';
              $healthy_check_result[5]=0;
        }

        $output.='<br/>';

        // 7. Check
        if (mathilda_update_eight_aftercheck()==true)  {
              $output.='None tweets are truncated.';
              $healthy_check_result[6]=1;
        } else {
              $output.='<span class="mathilda-healtycheck-warning">&nbsp;Warning:&nbsp;</span> Some tweets are truncated. Please reset Mathilda and reload the data again.';
              $healthy_check_result[6]=0;
        }

        $output.='</p>';
        $output.='<p><strong>Additional Information</strong></p>';
        $output.='<p>';
        
        // 1. Info
        $this_environment_php_execution_time=ini_get('max_execution_time');
        $output.='Max PHP script execution time: '.$this_environment_php_execution_time.' seconds.';
        
        $output.='<br/>';

        // 2. Info
        $initial_load_done=get_option('mathilda_initial_load');
        if($initial_load_done==0) {
            $output.='Initial Load is pending.';
        } else {
            $output.='Initial Load was executed.';
        }
        
        $output.='<br/>';

        // 3. Info
        $import_done=get_option('mathilda_import');
        if($import_done==0) {
            $output.='Import was not executed.';
        } else {
            $output.='Import was executed.';
        }
        
        $output.='<br/>';

        // 4. Info
        $tweets_loaded=mathilda_tweets_count();
        if($tweets_loaded==0) {
            $output.='No Tweets are loaded.';
        } else {
            $output.='Tweets are loaded.';
        }

        $output.='</p>';
        $output.='<p><strong>Result</strong></p>';
        $output.='<p>';

        // Result
        $result = array_search(0, $healthy_check_result);
        
        if($result) {
            $output.='Mathilda is not doing well! :-(<br/>Please investigate the issues above.';
        } else {
            $output.='Mathilda is doing well! :-)';
       }
        
       // Helpful Links
       $output.='</p>';
       $output.='<p><strong>Helpful Resources</strong></p>';
       $output.='<p>';

       $output.='<a href="https://wordpress.org/plugins/mathilda/faq/" target="_blank">Mathilda FAQ</a><br/>';
       $output.='<a href="https://wordpress.org/support/plugin/mathilda" target="_blank">Mathilda Support Forum</a>';
       $output.='</p>';

       return $output;
}

?>