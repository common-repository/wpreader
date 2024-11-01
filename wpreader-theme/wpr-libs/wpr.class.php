<?php

Class WPR {

	function do_loop($start=1,$catn='home'){
		global $wpdb, $posts_per_page ;
		if ($catn == 'home'){
			$results = $wpdb->get_results("SELECT * FROM $wpdb->posts ORDER BY post_date DESC LIMIT $start,$posts_per_page",ARRAY_A) ;
		}
		else {
			$catn = $wpdb->escape($catn);
			$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_excerpt LIKE '%$catn%' ORDER BY post_date DESC LIMIT $start,$posts_per_page",ARRAY_A) ;
		}
		if($results || count($results) > 0 ){
			$strtxt = '<ul>';
			foreach ($results as $result){
				$blog_info = split('<--->',$result['post_excerpt']) ;
				$strtxt .= "<li class='storyli'><h1 class='midconttitle'><a onclick='wpr.ui.toggle(\"".$result['guid']."\");return false;' href='".$result['post_name']."'>".$result['post_title']."</a></h1>
				<div class='midcontdate'>Posted by: <a href='".$blog_info[1]."'> 
				".$blog_info[0]."</a> - <a href='".$result['post_name']."'>".WPR::nice_date($result['post_date'])."</a></div> 
				<div style='display:none;' class='midcontstory' id='story-".$result['guid']."'>".$result['post_content']."</div>
				</li>";
			}
			return $strtxt .= '</ul>';
		}

	}

	function nice_date($d){
		// from php.net comments in date() function
		$ts = time() - strtotime(str_replace("-","/",$d));
		if($ts>31536000) $val = round($ts/31536000,0).' year';
		else if($ts>2419200) $val = round($ts/2419200,0).' month';
		else if($ts>604800) $val = round($ts/604800,0).' week';
		else if($ts>86400) $val = round($ts/86400,0).' day';
		else if($ts>3600) $val = round($ts/3600,0).' hour';
		else if($ts>60) $val = round($ts/60,0).' minute';
		else $val = $ts.' second';

		if ($val < 0) $val = 'some minutes' ;
		if($val>1) $val .= 's';
		return ucwords($val).' Ago' ;
	}

	function list_categories_menu(){
		$lines = file(WPR_FEEDS_LIST) ;
		$strtxt =  '<ul><li><a href="?">Today</a></li>';
		foreach ($lines as $line_num => $line) {
			if (!preg_match('/^#/',$line)){
				$line = split(WPR_FEEDS_LIST_SEP,$line) ;
				$strtxt .= '<li><a title="'.$line[1].'" href="?catn='.urlencode($line[1]).'">'.$line[1].'</a></li>';
			}
		}
		return $strtxt .='</ul>';

	}

	function trunc($text, $max_words=85) {
		$text = $text." ";
		$text = substr($text,0,$max_words);
		$text = substr($text,0,strrpos($text,' '));
		if ($text >= $max_words) $text = $text."...";
		return $text;
	}

	/*
	 Digg Style Pagination function.
	 Original author: Victor De la Rocha - http://www.mis-algoritmos.com
	 Downloaded from: http://www.mis-algoritmos.com/2007/03/12/wp-digg-style-pagination-plugin/
	 Adopted for WPReader (May 23, 2007)
	 * */
	function pagination($adjacents=1,$catn='home',$nav = array("Previous","Next")){
		global $request, $posts_per_page, $wpdb, $paged;
		$sqlStr = "SELECT count(*) FROM " ;
		if ($catn == 'home'){
			$sqlStr .= " $wpdb->posts " ;
		}
		else {
			$catn = $wpdb->escape($catn);
			$sqlStr .= " $wpdb->posts WHERE post_excerpt LIKE '%$catn%' " ;
		}
		
		$total_pages = $wpdb->get_var($sqlStr); //total number of rows in data table
		$limit = $posts_per_page;	//how many items to show per page
		if(!empty($paged))$page = $paged; else $page = 1;

		/* Setup vars for query. */
		if($page) {
			$start = ($page - 1) * $limit; 			//first item to display on this page
		}
		else {
			$start = 0;								//if no page var is given, set start to 0
		}
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//anterior page is page - 1
		$siguiente = $page + 1;						//siguiente page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1

		/*
			Now we apply our rules and draw the pagination object.
			We're actually saving the code to a variable in case we want to draw it more than once.
			*/
		ob_start();
		if($lastpage > 1){
			echo "<div class=\"pagination\">";
			//anterior button
			if($page > 1)
			echo "<a href=\"".get_pagenum_link($prev)."\">&#171; $nav[0]</a>";
			else
			echo "<span class=\"disabled\">&#171; $nav[0]</span>";
			//pages
			if ($lastpage < 7 + ($adjacents * 2)){//not enough pages to bother breaking it up
				for ($counter = 1; $counter <= $lastpage; $counter++){
					if ($counter == $page)
					echo "<span class=\"current\">$counter</span>";
					else
					echo "<a href=\"".get_pagenum_link($counter)."\">$counter</a>";
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)){//enough pages to hide some
						//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 2)){
								for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
										if ($counter == $page)
												echo "<span class=\"current\">$counter</span>";
											else
												echo "<a href=\"".get_pagenum_link($counter)."\">$counter</a>";
									}
								echo "...";
								echo "<a href=\"".get_pagenum_link($lpm1)."\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\">$lastpage</a>";
							}
						//in middle; hide some front and some back
						elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
								echo "<a href=\"".get_pagenum_link(1)."\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\">2</a>";
								echo "...";
								for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
									if ($counter == $page)
											echo "<span class=\"current\">$counter</span>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\">$counter</a>";
								echo "...";
								echo "<a href=\"".get_pagenum_link($lpm1)."\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\">$lastpage</a>";
							}
						//close to end; only hide early pages
						else{
								echo "<a href=\"".get_pagenum_link(1)."\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\">2</a>";
								echo "...";
								for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
									if ($counter == $page)
											echo "<span class=\"current\">$counter</span>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\">$counter</a>";
							}
					}
				//siguiente button
				if ($page < $counter - 1)
						echo "<a href=\"".get_pagenum_link($siguiente)."\">$nav[1] &#187;</a>";
					else
						echo "<span class=\"disabled\">$nav[1] &#187;</span>";
				echo "</div>\n";
			}
		echo ob_get_clean();
	}
	
	
}

?>
