<table>
<tr>
<td class="midleft"><?php echo WPR::list_categories_menu(); ?></td>
<td class="midcontent"><?php 
global $posts_per_page;
if (empty($_GET["paged"])) { 
	$start = 0 ;
}
else {
	$start = intval($_GET["paged"])*($posts_per_page)-($posts_per_page) ;
}

if (!empty($_GET["catn"])){
	echo WPR::pagination(2,$_GET["catn"]);
	echo WPR::do_loop($start,$_GET["catn"]);
	echo WPR::pagination(2,$_GET["catn"]);
} 
else { 
	echo WPR::pagination(2);
	echo WPR::do_loop($start); 
	echo WPR::pagination(2);
}
?></td>
	</tr>
</table>

