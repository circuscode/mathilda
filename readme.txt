=== Mathilda ===
Contributors: unmus
Tags: twitter, tweets, social network, content, reclaim, blog
Requires at least: 4.5
Tested up to: 4.7.2
Stable tag: 0.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://www.unmus.de/

Mathilda takes your tweets from Twitter, saves them in the WordPress database and displays them on the blog.

== Description ==

Mathilda is trying to give you back some control of your tweets. The plugin takes your tweets from twitter and saves them into the WordPress database. The tweets can be displayed on the blog chronologically (but do not have to). Indeed, Twitter is also blogging, micro-blogging so to speak. 

= Functions =

* Copy your tweets back
* Copy your tweeted images back
* Display your tweets on the blog
* Shows basic tweet statistics
* Import your complete tweet history
* Export your tweets as CSV file
* Plugin Healthy Check
* Languague: English, German (only FrontEnd)

= Live Demo =

[Here!](https://www.unmus.de/tweets/)

= Related Links =

[Plugin Page (German)](https://www.unmus.de/wordpress-plugin-mathilda/)
[Source Code @ GitHub](https://github.com/circuscode/mathilda)

== Installation ==

1. Upload plugin folder to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Follow the configuration manual > [See other Notes](https://de.wordpress.org/plugins/mathilda/other_notes/)

Of course the installation through wordpress plugin menu is more convienient as the procedure above.

== Frequently Asked Questions ==

= Which Tweets are displayed in the blog? =

Mathilda focuses on the core functionality of Twitter meaning only Text-Tweets, Images, Hashtags, Mentions and Links are displayed. Image Series, Surveys, Quotes, Videos and GEO-Locations are not supported. 

= Why does Mathilda not support Retweets? =

Retweets are not your data. That is why we do not show them.

= Will the tweets be loaded automaticly? =

Yes. But the first load must be done manually with the cron (Tools/Tweets). Otherwise the autoload will not activated.

= How often will the tweets be loaded? =

As Standard your tweets will be fetched from Twitter every 15 minutes. But you can define the period different in the plugin settings.

= What is the difference between Cron and Import? =

The cron takes your tweets online through the Twitter API and saves them into the WordPress Database regularly. The Import takes your tweets from the files of your Twitter Archieve and saves them into the WordPress Database. For this, you must download your archieve from Twitter before.

= What do I have to do first, run the cron or import my tweets? =

All the same. It works both. Mathilda is flexible. But the autoload will only be activated with an initial cron.

= How can I import my tweet history? =

Twitter API provides access to your 3200 latest tweets. If you have not written more, the import is not required. To import your tweets, you must download your tweet archive from Twitter (Profile/Settings/Your Twitter Data). Your archive contains the folder "data/js/tweets". Upload the containing files to "www.yourblog.com/wp-content/uploads/mathilda-import/". Now you can run the import (Tools/Tweets).

= How does Mathilda handle the canonical URL? =

Mathilda does not modify the existing canonical url handling in WordPress. If you want to change the canonical url you must handle that with a SEO plugin.

= What have to be considered with the usage of caching plugins? =

The length of the cron period in combination with the configuration of caching determines how early a tweet will be displayed within the blog. If a tweet should be displayed as early as possible, the caching must be deactivated for the page with tweets. 

= Why are some quotes truncated? =

Mathilda does not support the latest changes on the Twitter API from mid of 2016, which allow Quotes in full length of a tweet (140 characters). These new type of quote will be delivered by Twitter with the "Extended API Mode". Mathilda works with the classic API mode, which does not contain the new tweet format. 

= Why require some updates a reset of the plugin and reload of the data?  =

The plugin remains is an early stage of development. It is still a long way to the first stable 1.0 version. To avoid data inconsistency and process errors some updates require a reload of the data. Sometime Mathilda will have an maturity level, which does not require such resets anymore.

== Screenshots ==

1. Mathilda Settings
2. Mathilda Tools
3. Tweets @ User Interface

== Changelog ==

= 0.6 "Poison Ivy" =
* February 2017
* Feature: YouTube Embedding
* Feature: Plugin Reset
* Enhancement: Fetches Meta Data from Twitter API to enable better Processing
* Enhancement: Reporting on Retweets, Replies and Quotes
* Enhancement: Update Notices
* Enhancement: Data Consistency Verification in Plugin Healthy Check 
* Enhancement: Check on allow_url_fopen in Plugin Healthy Check 
* Enhancement: Additional Processing Information in Plugin Healty Check
* Enhancement: Include or Exclude Quotes from Mathilda Loop
* Enhancement: Expert Settings
* Bugfix: Display Images in original Width
* Bugfix: Hide Bottom Navigation, if Navigation is not required

= 0.5 =
* January 2017
* Feature: Dashboard Widget with statistic Information about Tweet Data
* Feature: Hyperlink Rendering Option (Shortlink or Reallink)
* Feature: Support of Line Breaks within Tweets
* Feature: Option to deactivate Mathilda CSS
* Bugfix: Initial Cron has not started in some cases
* Bugfix: Display correct Time if local TimeZone is defined with UTC-Offset
* Bugfix: CSS Class for Image Alignment
* Improvement: Twitter API Access optimized
* Security: Call of Mathilda Functions without WordPress is not possible anymore

= 0.4.2 =
* July 2016
* Bugfix: Fallback, if local TimeZone is not defined in the WordPress settings

= 0.4.1 =
* July 2016
* Bugfix: Handling of blanks if the tweet contains a URL and does not end with it

= 0.4 =
* July 2016
* Feature: Custom Mathilda Cron Period
* Feature: Additional Bottom Navigation Type
* Enhancement: W3C Validated HTML Code
* Enhancement: W3C Validated CSS Code
* Enhancement: Latest Plugin Code now available on [GitHub](https://github.com/circuscode/mathilda)
* Bugfix: Display Tweets on pages with blank in the name
* Bugfix: Hide Navigation if only a few tweets available
* Bugfix: Flush Rewrite Rules, only if Slug is changed

= 0.3 =
* June 2016
* Feature: Replies are supported
* Feature: Update process for upcoming plugin updates
* Feature: Localization German @ FrontEnd
* Feature: Mathilda Handbook is integrated
* Enhancement: Support of TimeZones and WordPress Local Date
* Enhancement: Advise before inital load and import
* Enhancement: Preventing Timeouts during Import/Cron Runtime
* Enhancement: Autoflush Rewrite Rules if Slug was changed
* Enhancement: Result Page for Plugin Healthy Check
* Enhancement: Healthy Check outputs PHP Max Execution Time
* Enhancement: Better Mathilda Development UI
* Enhancement: More Developer Settings
* Bugfix: File Counting during Import
* Bugfix: HTML Correction Import/Cron Output
* Bugfix: Finish Message added for Cron
* Bugfix: Healthy Check verifys Import Folder
* Bugfix: Mention links to Twitter Profile

= 0.2 =
* Late May 2016
* Replacement of WebCron with WP-Cron
* Import Script is changed from external script to WordPress Function
* First Version published at WordPress Plugin Directory

= 0.1 =
* May 2016
* Initial Release

== Upgrade Notice ==

= 0.6 =
This version brings YouTube Embedds, better Reporting, improved Processing and bugfixes.

= 0.5 =
This version brings tweet reporting, more options, more security and some bugfixes.

= 0.4 =
This version supports custom defined cron periods, brings further navigation options and includes Bugfixes.

= 0.3 =
This version supports replies, makes import and cron more stable and includes many bugfixes.

= 0.2 =
This version does not require a webcron anymore.

== Configuration ==

1. [Register](http://dev.unmus.de/wp-content/uploads/Mathilda-Twitter-App-Registration.pdf) your Mathilda-Instance as Twitter-Application for API Access [apps.twitter.com](https://apps.twitter.com)
2. Activate the plugin in WordPress
3. Maintain OAUTH Access Token, OAUTH Access Token Secret, Consumer Key, Consumer Secret and your Twitter Account in the settings
4. Run the initial Mathilda Cron (Tools/Tweets) - this will take several minutes
5. Create a WordPress page (page slug must match the mathilda slug) 

= How to setup Mathilda? =
[Screencast Video](https://www.unmus.de/wordpress-plugin-mathilda/) (German)

= CSS classes =

You know the game! Mathilda can not assure that it looks fine on your theme. That is why all mathilda UI elements can be addressed with individual CSS selectors. Please use your debugger to find the right classes. 

= Data & Files =

Mathilda creates 4 folders within wp-content/uploads.

* mathilda-twitterapi = Archive of the Twitter API data
* mathilda-images = Tweet Image Folder
* mathilda-export = Export Directory 
* mathilda-import = Import Directory 