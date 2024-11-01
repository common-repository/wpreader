<?php
/*
 	- WARNING: THIS IS A VERY UNSTABLE PLUGIN/THEME!
	- ONLY AND ONLY INSTALL IT ON A LOCAL DEV MACHINE FIRST
	AND TEST IF IT SUITS YOU.
	- ALSO, ONLY INSTALL THIS ON TOP OF A COMPLETELY FRESH WORDPRESS INSTALL.
	- NEVER INSTALL IT ON TOP OF A USED WORDPRESS INSTALLATION.
	- YOU HAVE BEEN WARNED!
*/


// here you can define your configuration:

define('WPR_CACHE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'wpr-cache') ; // cache directory, make it writeable.
define('WPR_FEEDS_LIST', dirname(__FILE__).DIRECTORY_SEPARATOR.'feeds.txt') ; // list of feeds to fetch
define('WPR_LIBS_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'wpr-libs'.DIRECTORY_SEPARATOR) ; // directory where libraries are 
define('WPR_INC_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'wpr-inc'.DIRECTORY_SEPARATOR) ; // directory where include files are
define('WPR_FEEDS_LIST_SEP','<--->') ; // seperator tags in your feed list file 
define('WPR_CACHE_DURATION', 900) ; // cache duration in seconds.
define('WPR_ITEM_QUANTITY', 99) ; // fetch this many items from each feed
define('WPR_ALLOWED_HTML_TAGS','<a><img><ol><ul><li><br><b><p>') ;// allowed html tags in items;

require_once(WPR_LIBS_DIR.'wpr.class.php');


/* For updating your feeds, fetch :

http://my-server/myblog/?op=up

You have to make a cron job or something like that to automate this!
When you visit your blog 'normally', your feeds won't be refreshed! 
For security reason and preventing any John Doe updating your feeds, the
operation is only allowed from IP address 127.0.0.1. 
You can change it in the begining of index.php file, where you see:

if ($_GET["op"] == 'up' && preg_match('/127\.0\.0\.1/',$_SERVER["REMOTE_ADDR"])){

change 127.0.0.1 to the IP address you are going to use for fetching your feeds.

*/
?>