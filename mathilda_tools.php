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
	$run_healthy_check=false;
	$run_cron=false;
	$run_import=false;
	$run_initial=false;
	$handbook_show=false;

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
	// Import
	if(isset($_GET['handbook'])) {
		if($_GET['handbook']=='true')
		{
		$handbook_show=true;
		}	
	}
	
	// Application Loader
	if($wpcron_scheduled_show) {
		mathilda_wpcron_show();
	}
	elseif ($mathilda_scripting) {
		mathilda_script_load();
	}
	elseif ($run_healthy_check) {
		mathilda_healthy_check_load();
	}
	elseif ($run_cron) {
		mathilda_cron_initial_notice();
	}
	elseif ($run_import) {
		mathilda_import_script();
	}
	elseif ($run_initial) {
		mathilda_cron_script();
	}
	elseif ($handbook_show) {
		mathilda_handbook();
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

	// Truncate
	if(isset($_GET['truncate'])) {
		if($_GET['truncate']=='true')
		{
		$result=mathilda_fresh_tables();
		echo '<div class="updated fade">
		<p><strong>'.$result.'</strong></p> 
		</div>';
		}
	}
	
	/* Display Tools */
	
	echo'
	<table class="form-table">
	
	<!-- Cron -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Cron</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&cron=true" target="_blank">Run!</a>
	</td>
	</tr>
	
	<!-- Load Twitter Export -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Import Archive</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&import=true" target="_blank">Load!</a>
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

	</tr></table>';
	
}

function mathilda_tools_developer() {
	
	echo '
	
	<br/>&nbsp;<h2 class="mathilda_tools_developer_headline">Developer Tools</h2>
	<table class="form-table">
	
	<!-- Refresh Tweet Table -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Truncate Tables</label>
	</th>
	<td>
	<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&truncate=true">Fresh!</a>
	</td>
	</tr>
	
	<!-- Run the script -->
	<tr valign="top">
	<th scope="row">
	<label for="cron">Run this Script</label>
	</th>
	<td>
	<a class="button" href="'.admin_url(). 'tools.php?page=mathilda-tools-menu&scripting=true">Yes!</a>
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
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Cron</h1>';
	echo '<p class="mathilda_tools_description">';
	echo 'This is the first time you run the cron!<br/>';
	echo 'Initial Load takes several minutes.<br/>';
	echo 'During the process you will see a blank page.<br/>';
	echo 'Please wait until you have the finish message at the bottom.<br/>';
	echo 'After inital load the cron will run automaticly every 15 minutes in the background.';
	echo '</p>';
	echo '<p>&nbsp;<br/><a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu&initialrun=true">Yes, go for it!</a>&nbsp;&nbsp;&nbsp;<a class="button" href="'.admin_url().'tools.php?page=mathilda-tools-menu">Cancel</a></p>';
	}
	else {
		mathilda_cron_script();
	}

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
Mathilda Healthy Check
*/

function mathilda_healthy_check_load() {
	
	echo '<h1 class="mathilda_tools_headline">Mathilda Healthy Check</h1>';
	echo '<p class="mathilda_tools_description">Result<br/>&nbsp;</p>';
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
	1. Register your Mathilda-Instance as Twitter-Application for API Access - read the <a href="https://www.unmus.de/wp-content/uploads/Mathilda-Twitter-App-Registration-EN.pdf" target="_blank">Step by Step Manual</a>.<br/>
	2. Maintain OAUTH Access Token, OAUTH Access Token Secret, Consumer Key, Consumer Secret and your Twitter Account in the plugin settings.<br/>
	3. Run the initial Mathilda Cron - this will take several minutes.<br/>
	4. Create a WordPress page to show your tweets - the page name must match the mathilda slug.</p>';

	/* Remarks */
	echo '<h2>Remarks</h2>
	- Initial cron is able to copy your latest 3200 tweets from Twitter<br/>
	- If you have written more tweets, you must import your older tweets manually with the import function.<br/>
	- After the execution of the initial cron, Mathilda will run the cron automaticly every 15 minutes.</p>';

	echo '<h2>How to import your complete twitter history?</h2>
	1. Download your tweet archive from Twitter (Profile/Settings/Your Data).<br/>
	2. Upload all archive files from the folder data/js/tweets to the folder wp-content/uploads/mathilda-import.</br>
	3. Run the import - this can take up to 30 minutes depending of the amount of your tweets.</p>';

	mathilda_tools_close();

}

?>