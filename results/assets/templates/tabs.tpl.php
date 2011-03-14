<?php

function print_single_tab($tab_url, $tab_text, $tab_name, $hover_text, $current_tab) {
	$active_code="";
	if($current_tab==$tab_name)
		$active_code="class=\"active\"";
	echo "<li><span title=\"".$hover_text."\"><a href=\"".$tab_url."\" ".$active_code.">".$tab_text."</a></span></li>\n";
}

function print_tabs($current_tab){
	global $ini_array;
	echo "<div id=\"tabs\" style=\"background: transparent url('",$ini_array['logo'],"') repeat center center;\"><ul>";
		print_single_tab("index.php", "Events", "index", "Browse Results by Event", $current_tab);
		print_single_tab("competitors.php", "Competitors", "competitors", "Browse Results by Competitor", $current_tab);
		print_single_tab($ini_array['schedule_link'], "Schedule<img src=\"assets/img/external_link.png\">", "schedule", "Competition Schedule", $current_tab);
	echo "</ul></div>";
}
?>