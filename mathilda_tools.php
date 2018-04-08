<?php

/* 
Mathilda Tools @ Menu
*/

function mathilda_tools_menu() {
    	add_management_page(
            'Tweets',
            'Tweets',
            'manage_options',
            'mathilda-tools-menu',
            'mathilda_tools_controller'
        );
}

add_action('admin_menu', 'mathilda_tools_menu');

/*
Mathilda Tools Controller
*/

function mathilda_tools_controller() {
	
	echo '<div class="wrap">';

	// Application Flags
	$wpcron_scheduled_show=false;
	$mathilda_scripting=false;
	$mathilda_embedding=false;
	$run_healthy_check=false;
	$run_cron=false;
	$run_import=false;
	$run_initial=false;
	$handbook_show=false;
	$mathilda_reset=false;

	// WordPress Crons
	if(isset($_GET['showwpcrons'])) {
		if($_GET['showwpcrons']=='true')
		{
		$wpcron_scheduled_show=true;
		}	
	}
	// Scripting
	if(isset($_GET['scripting'])) {
		if($_GET['scripting']=='true')
		{
		$mathilda_scripting=true;
		}	
	}
	// Embedding
	if(isset($_GET['embedding'])) {
		if($_GET['embedding']=='true')
		{
		$mathilda_embedding=true;
		}	
	}
	// Healthy Check
	if(isset($_GET['healthy'])) {
		if($_GET['healthy']=='true')
		{
		$run_healthy_check=true;
		}	
	}
	// Cron
	if(isset($_GET['cron'])) {
		if($_GET['cron']=='true')
		{
		$run_cron=true;
		}	
	}
	// Initial Cron Confirmed
	if(isset($_GET['initialrun'])) {
		if($_GET['initialrun']=='true')
		{
		$run_initial=true;
		}	
	}
	// Import
	if(isset($_GET['import'])) {
		if($_GET['import']=='true')
		{
		$run_import=true;
		}	
	}
	// Handbook
	if(isset($_GET['handbook'])) {
		if($_GET['handbook']=='true')
		{
		$handbook_show=true;
		}	
	}
	// Reset
	if(isset($_GET['reset'])) {
		if($_GET['reset']=='true')
		{
		$mathilda_reset=true;
		}	
	}
	
	// Application Loader
	if($wpcron_scheduled_show) {
		mathilda_wpcron_show();
	}
	elseif ($mathilda_scripting) {
		mathilda_script_load();
	}
	elseif ($mathilda_embedding) {
		mathilda_script_embed();
	}
	elseif ($run_healthy_check) {
		mathilda_healthy_check_load();
	}
	elseif ($run_cron) {
		mathilda_cron_initial_notice();
	}
	elseif ($run_import) {
		mathilda_import_tool(); 
	}
	elseif ($run_initial) {
		mathilda_cron_script();
	}
	elseif ($handbook_show) {
		mathilda_handbook();
	}
	elseif ($mathilda_reset) {
		mathilda_reset_confirmation();
	}
	else {
		mathilda_tools();
		/* Developer - Yes or No? */
		$mathilda_developer=get_option('mathilda_developer');
		if ($mathilda_developer == 1)
		{
		mathilda_tools_developer();
		}
	}
	
	echo '</div>';
	
}

/*
Tools Page
*/

function mathilda_tools() {
	
	/* Headline */
	echo '<h1 class="mathilda_tools_headline">Tweets</h1>
	<p class="mathilda_tools_description">Mathilda Tools</p>';
	
	/* 
	Response Functions 
	*/
	
	// Export CSV
	if(isset($_GET['exportcsv'])) {
		if($_GET['exportcsv']=='true')
		{
		$result=mathilda_export_csv();
		echo '<div class="updated fade">
		<p><strong>'.$result.'</strong></p> 
		</div>';
		}	
	}

	// Mathilda Reset
	if(isset($_GET['embedreset'])) {
		if($_GET['embedreset']=='true')
		{
		$result=mathilda_reset_embed();
		echo '<div class="updated fade">
		<p><strong>'.$result.'</strong></p> 
		</div>';
		}
	}

	// Embed Reset
	if(isset($_GET['resetisconfirmed'])) {
		if($_GET['resetisconfirmed']=='true')
		{
		$result=mathilda_reset_data();
		echo '<div class="updated fade">
		<p><strong>'.$result.'</strong></p> 
		</div>';
		}
	}
	
	/* Display Tools */
	
	echo '
	<table class="form-table">
	
	<!-- Cron -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Load Tweets</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&cron=true" >Run!</a>
	</td>
	</tr>
	
	<!-- Load Twitter Export -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Import Archive</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&import=true">Load!</a>
	</td>
	</tr>
	
	<!-- Export @ CSV -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Export @ CSV</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&exportcsv=true">Create!</a>
	</td>
	</tr>
	
	<!-- Check Mathilda -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Plugin Healthy Check</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&healthy=true">Do!</a>
	</td>

	<!-- Handbook -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Mathilda Handbook</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&handbook=true">Read!</a>
	</td>

	<!-- Reset Mathilda -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Reset Mathilda</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&reset=true">Yes!</a>
	</td>

	</tr></table>';
	
}

function mathilda_tools_developer() {
	
	echo '
	
	<br/>&nbsp;<h2 class="mathilda_tools_developer_headline">Developer Tools</h2>
	<table class="form-table">
	
	<!-- Run the script -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Run this Script</label>
	</th>
	<td>
	<a class="button" href="'.admin_url(). 'tools.php?page=mathilda-tools-menu&scripting=true">Yes!</a>
	</td>
	</tr>

	<!-- Run Embed -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Embedding</label>
	</th>
	<td>
	<a class="button" href="'.admin_url(). 'tools.php?page=mathilda-tools-menu&embedding=true">Run!</a>
	</td>
	</tr>

	<!-- Embed Reset -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Reset Embeds</label>
	</th>
	<td>
	<a class="button" href="'.admin_url(). 'tools.php?page=mathilda-tools-menu&embedreset=true">Delete!</a>
	</td>
	</tr>
	
	<!-- Show WordPress Crons -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">WordPress Crons</label>
	</th>
	<td>
	<a class="button" href="'.admin_url(). 'tools.php?page=mathilda-tools-menu&showwpcrons=true">Show!</a>
	</td>
	</tr>
	
	</table>';
	
}

/*
Initial Cron
*/

function mathilda_cron_initial_notice() {
	
	$initial_load = get_option('mathilda_initial_load');
	
	if($initial_load==0) {

	$custom_cron_period=get_option('mathilda_cron_period');
	$custom_cron_period=$custom_cron_period/60;
	
	echo '<h1 class="mathilda_tools_headline">Load Tweets</h1>';
	echo '<p class="mathilda_tools_description">';
	echo 'This is the first time you copy your tweets from Twitter to WordPress!<br/>';
	echo 'Initial Load may take several minutes.<br/>';
	echo 'During the process you will see a blank page.<br/>';
	echo 'Please wait until you have the finish message at the bottom.<br/>';
	echo 'After this initial load Mathilda will load your tweets automaticly in the background every '.$custom_cron_period.' minutes.';
	echo '</p>';
	echo '<p>&nbsp;<br/><a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&initialrun=true">Yes, go for it!</a>&nbsp;&nbsp;&nbsp;<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu">Cancel</a></p>';
	}
	else {
		mathilda_cron_script();
	}

}

/*
Mathilda Reset
*/

function mathilda_reset_confirmation() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Reset</h1>';
	echo '<p class="mathilda_tools_description">';
	echo 'This will delete all your data from Twitter in the Mathilda WordPress tables.<br/>';
	echo 'Your custom settings will remain in the plugin options.<br/>';
	echo 'Images and logfiles on the webspace will also not deleted.<br/>';
	echo 'After the reset you can use the inital cron or import to catch the data again.<br/>';
	echo '</p>';
	echo '<p>&nbsp;<br/><a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&resetisconfirmed=true">Yes, go for it!</a>&nbsp;&nbsp;&nbsp;<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu">Cancel</a></p>';

}

/*
Mathilda Reset
*/

function mathilda_confirm_import() {
	
	echo '<h1 class="mathilda_tools_headline">Import Archive</h1>';
	echo '<p class="mathilda_tools_description">';
	echo 'Your tweet history was already imported.<br/>';
	echo 'Do you want to run the import again?<br/>';
	echo '</p>';
	echo '<p>&nbsp;<br/><a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&importisconfirmed=true">Yes, do it again!</a>&nbsp;&nbsp;&nbsp;<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu">Cancel</a></p>';

}

/*
Scheduled Crons
*/

function mathilda_wpcron_show() {
	
	echo '<h1 class="mathilda_tools_headline">WordPress Crons</h1>';
	echo '<p class="mathilda_tools_description">Scheduled scripts<br/>&nbsp;</p>';
	echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
	mathilda_tools_close();

}

/*
Mathilda Scripting
*/

function mathilda_script_load() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Scripting</h1>';
	echo '<p class="mathilda_tools_description">Output<br/>&nbsp;</p>';
	mathilda_scripting();
	mathilda_tools_close();

}

/*
Mathilda Embedding
*/

function mathilda_script_embed() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Embedding</h1>';
	echo '<p class="mathilda_tools_description">Output<br/>&nbsp;</p>';
	mathilda_update_embed();
	mathilda_tools_close();

}

/*
Mathilda Healthy Check
*/

function mathilda_healthy_check_load() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Healthy Check</h1>';
	echo '<p class="mathilda_tools_description">Analysis<br/>&nbsp;</p>';
	$health=mathilda_healthy_check();
	echo $health;
	mathilda_tools_close();

}

/* 
Mathilda Tool Close
*/

function mathilda_tools_close() {
	
	echo '<p>&nbsp;<br/><a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu">Back to Mathilda Tools</a></p>';
	
}

/*
Mathilda Docu
*/

function mathilda_handbook() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Handbook</h1>';
	echo '<p class="mathilda_tools_description">Get it working!<br/>&nbsp;</p>';
	
	/* Initial Config */

	echo '<h2>Initial Configuration</h2><p>
	1. <a href="https://www.unmus.de/wp-content/uploads/Mathilda-Twitter-App-Registration-EN.pdf" target="_blank">Register</a> your Mathilda-Instance for Twitter API Access.<br/>
	2. Maintain OAUTH Access Token, OAUTH Access Token Secret, Consumer Key, Consumer Secret and your Twitter Account in the plugin settings.<br/>
	3. Run the initial Mathilda Cron.<br/>
	4. Create a WordPress page to show your tweets (page slug must match to mathilda slug)</p>';

	/* Remarks */
	echo '<h2>Remarks</h2>
	- Initial load is will copy your latest 200 tweets from Twitter.<br/>
	- After execution of the initial load, Mathilda will load your future tweets automaticly.</p>';

	echo '<h2>How to import your complete twitter history?</h2>
	1. Download your tweet archive from Twitter (Profile/Settings/Your Data).<br/>
	2. Upload all files from the folder data/js/tweets to the folder wp-content/uploads/mathilda-import.</br>
	3. Run the import.</p>';

	mathilda_tools_close();

}

?>