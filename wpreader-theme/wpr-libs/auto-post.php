<?php

Class AUTO_BLOG {
	function post_single_item($item,$blog_name,$blog_url){
		global $wpdb;
		$post_author = 1;
		$post_date = $item->get_date() ? $item->get_date('Y-m-d H:i:s') : date('Y-m-d H:i:s') ;
		$post_date_gmt = get_gmt_from_date($post_date);
		$post_content = $item->get_content() ? $wpdb->escape(AUTO_BLOG::clean_content(trim($item->get_content()), $blog_name))  : '' ;
		$post_excerpt = $wpdb->escape($blog_name.'<--->'.$blog_url) ;
		$post_title = $wpdb->escape(trim(strip_tags($item->get_title())))  ;
		//$post_category =  AUTO_BLOG::set_cat($blog_name) ;
		$post_status = 'publish';
		$post_type = 'post';
		$comment_status = $ping_status = 'closed';
		$post_name = $wpdb->escape(trim($item->get_permalink()));
		$guid = md5($post_name) ;
		$post_modified = $post_date ;
		$post_modified_gmt = $post_date_gmt ;
		$post_parent = $menu_order = $post_category  = 0 ;
		$to_ping = $pinged = $post_content_filtered = $post_time_type = $post_password = '' ;
		
		$sql = "SELECT ID FROM $wpdb->posts WHERE guid = '" . $guid . "'";
		if($wpdb->query($sql) === 0){
			$wpdb->query(
			"INSERT IGNORE INTO $wpdb->posts
			(post_author, post_date, post_date_gmt, 
			post_content, post_content_filtered, post_title, 
			post_excerpt,  post_status, post_type, comment_status, 
			ping_status, post_password, post_name, to_ping, pinged, 
			post_modified, post_modified_gmt, post_parent, guid, menu_order, post_mime_type)
			VALUES
			('$post_author', '$post_date', '$post_date_gmt', 
			'$post_content', '$post_content_filtered', '$post_title', 
			'$post_excerpt', '$post_status', '$post_type', '$comment_status', 
			'$ping_status', '$post_password', '$post_name', '$to_ping', '$pinged', 
			'$post_date', '$post_date_gmt', '$post_parent', '$guid', '$menu_order', '')");
			$post_id = $wpdb->insert_id;
			//AUTO_BLOG::set_post2cat($post_id,$post_category);
		}
	}

	function init(){
		set_time_limit(60*10);
		require_once('simplepie.inc');
		$lines = file(WPR_FEEDS_LIST) ;
		$feed = new SimplePie();
		//var_dump($feed);
		foreach ($lines as $line_num => $line) {
			if (!preg_match('/^#/',$line)){
				$line = split(WPR_FEEDS_LIST_SEP,$line) ;
				$feed->set_feed_url(trim($line[0]));
				$feed->set_cache_duration(WPR_CACHE_DURATION);
				$feed->set_cache_location(WPR_CACHE_DIR) ;
				$feed->init();
				$feed->handle_content_type();

				if ($feed->data) {
					for ($x = 0 , $max = $feed->get_item_quantity(WPR_ITEM_QUANTITY) ; $x < $max; $x++) {
						//$item = $feed->get_item($x);
						AUTO_BLOG::post_single_item($feed->get_item($x),trim($line[1]),trim($line[2]));
					}
				}
			}
		}
	}
	
	function clean_content($content, $blog_name){
		/* 
			This is the place to clean the content of items you are fetching!
			Removing ads, etc. if you like.
			It depends on the blog name you defined in feeds.txt file.
			Find the regexp you have to use and put it in switch case.
    	*/
		// strip html tags, based on your option in wpr-config.php:
		$content = strip_tags($content,WPR_ALLOWED_HTML_TAGS);
		//$content = trim(preg_replace('/\s\s+/', ' ', $content));
		
		switch ($blog_name) {
			/*
			// example:
			case "example blog_name" : return preg_replace('/my regexp goes here/','',$content)
			;
			break ;
			*/	
			/*
			by default return the original item:		 
			*/
			default: return $content
			;
			break;
		}
	}
	
	function set_cat($cat){
		global $wpdb;
		$cat = apply_filters('pre_category_nicename', trim($wpdb->escape($cat)));
		$results = $wpdb->get_results("SELECT cat_ID, cat_name FROM $wpdb->categories WHERE cat_name = '$cat'");
		if(!$results || count($results) < 1){
			$wpdb->query("INSERT IGNORE INTO $wpdb->categories SET cat_name='$cat', category_nicename='" . sanitize_title($cat) . "', category_count = 1");
			return $wpdb->insert_id;
			} else {
			return $results[0]->cat_ID;
		}
	}
	
	function set_post2cat($post_id,$cat_id){
		global $wpdb;
		$results = $wpdb->get_results("SELECT post_id FROM $wpdb->post2cat WHERE post_id = $post_id AND category_id = $cat_id");
		if(!$results || count($results) < 1){
			$wpdb->query("INSERT IGNORE INTO $wpdb->post2cat SET post_id = $post_id, category_id = $cat_id");
			$wpdb->query("UPDATE $wpdb->categories SET category_count = category_count+1 WHERE cat_ID = '$cat_id'");
		}	
	}
	
}

?>