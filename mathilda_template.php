<?php

/*
 *
 * Mathilda Template
 *
 */

// HTML Start
$mathilda_content_html.='<div id="mathilda-area">';

// Run Mathilda

if($alliswell==true) {
	
	// Mathilda Loop	
	$mathilda_content_html.= mathilda_loop($mathilda_show_page);
	
	/* Mathilda Bottom Navigation */
	$mathilda_content_html.= mathilda_create_menu($mathilda_pages_amount, $mathilda_show_page );
	
}
else {
	
	// Oh No!
	$mathilda_content_html.='<p>This is Mathilda! Please run import or cron!</p>';

}

// HTML End
$mathilda_content_html.='</div>';

?>