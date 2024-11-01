<?php
require_once('wpr-config.php');
header("Content-Type: text/html; charset=utf-8");

if (!empty($_GET["op"]) && $_GET["op"] == 'up' && preg_match('/127\.0\.0\.1/',$_SERVER["REMOTE_ADDR"])){
	require_once(WPR_LIBS_DIR.'auto-post.php');
	AUTO_BLOG::init() ;
	die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<script
	type="text/javascript" src="<?php bloginfo('template_url'); ?>/wpr.js"></script>
<title><?php bloginfo('name'); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

</head>

<body>
<div id="header"><?php include_once(WPR_INC_DIR.'wpr-header.php'); ?></div>
<div id="middle"><?php include_once(WPR_INC_DIR.'wpr-middle.php'); ?></div>
<div id="footer"><?php include_once(WPR_INC_DIR.'wpr-footer.php'); ?></div>

</body>
</html>