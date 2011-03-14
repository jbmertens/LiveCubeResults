<?php

$disable = 0;

$success=0;
$messages = "";
$bodystyles = "";

// Check for submission and password
$access = 0;
if($_REQUEST['pass']==$ini_array['upload_password'] && $_REQUEST['action'] && !$disable){
	$access = 1;
}elseif($_REQUEST['action']){
	$messages .= "<div style=\"width: 300px; border: 2px solid #EE0000; background: #FFEEEE; padding:5px;margin:5px;\">Password Error.</div>";
}

// Check for .htaccess
if(!file_exists(".htaccess")){
	$messages .= '<div style="padding:5px; width: 300px; border:3px solid #FF0000; background: #FFDDDD; margin: 5px;">';
		$messages .= 'WARNING!  The .htaccess file was not found - please make sure your filesystem is protected!';
	$messages .= '</div>';
}


// Check for filesystem writability
$messages .= '<ul style="padding:5px 5px 5px 25px; width: 280px; border:2px solid #9090FF; margin: 5px;">';
$flag = 1;
if(!is_writable("assets/cache")){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to cache!  Please make sure the path ~assets/cache is writable.</li>";
	$flag = 0;
}
if(file_exists($ini_array['spreadsheet_filepath']) && !is_writable($ini_array['spreadsheet_filepath'])){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to spreadsheet file!  Please make sure ~".$ini_array['spreadsheet_filepath']." is writable.</li>";
	$flag = 0;
}
if(!is_writable($ini_array['spreadsheet_backup_filepath'])){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to backup folder!  Pleae make sure ~".$ini_array['spreadsheet_backup_filepath']." is writable.</li>";
	$flag = 0;
}
if(file_exists("assets/cache/".$ini_array['WCA_cid']) && !is_writable("assets/cache/".$ini_array['WCA_cid'])){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to competition folder in cache!  Please make sure ~assets/cache/".$ini_array['WCA_cid']." is writable.</li>";
	$flag = 0;
}
if(!is_writable("assets/config/scoreboard/".$scoreboard_ini['this_info_filename'])){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to scoreboard settings file!  Please make sure ~assets/config/scoreboard/".$scoreboard_ini['this_info_filename']." is writable.</li>";
	$flag = 0;
}
if(!is_writable("assets/config/".$ini_array['this_info_filename'])){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to settings file!  Please make sure ~assets/config/".$ini_array['this_info_filename']." is writable.</li>";
	$flag = 0;
}
if(!is_writable("assets/public/lastupdate.txt")){
	$messages .= "<li style='color: red; background: #FFEEEE;'>Cannot write to update alert file!  Please make sure ~assets/public/lastupdate.txt is writable.</li>";
	$flag = 0;
}
if($flag){
	$messages .= "<li style='color: #00AA00; background: #EEFFEE;'>Filesystem is ok.</li>";
}
$messages .= '</ul>';


// Check for file upload
if($_FILES['uploadedfile']['tmp_name'] != "" && $access){
	$target_path = $ini_array['spreadsheet_filepath']; 
	if(file_exists($target_path)){ unlink($target_path); }
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path) && copy($target_path, $ini_array['spreadsheet_backup_filepath'].$ini_array['competition_name']."_".date("Ymd_Hi")."_".$_SERVER['REMOTE_ADDR'].".xls")) {
		$success=1;
		$bodystyles .= "padding-top:210px;";
		echo "<div style=\"width: 100%; height:200px; overflow:scroll; position: absolute; top: 0px; right: 0px; background: #eee; border-bottom: 1px solid #777;\">";
			include "assets/scripts/cache.inc.php";
		echo "</div>";
		$messages .= "<div style=\"width: 300px; border: 2px solid #EEEEEE; padding:5px;margin:5px;\">The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded</div>";
	} else {
		$messages .= "<div style=\"width: 300px; border: 2px solid #EEEEEE; padding:5px;margin:5px;\">There was an error uploading the file, please try again!</div>";
	}
}

function strip_quotes($in) {
	return str_replace("\"", "", $in);
}
function checkit($val) {
	if($val == "on"){ return 1; }
	echo $val;
	return 0;
}

$r = NULL;
// Update variables
if($access){

	/* write scoreboard settings */
	if(!($fs = fopen("assets/config/scoreboard/".$scoreboard_ini['this_info_filename'], "w+"))){
		$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error writing scoreboard configuration settings.</div>";
	}else{
		$out = "";
		$out .= "[results]\n";
		$out .= "current = \"".strip_quotes($_REQUEST['display'])."\"\n";
		$out .= "top = ".($_REQUEST['1v1']*1)."\n";
		$out .= "tope = ".($_REQUEST['1v2']*1)."\n";
		$out .= "winn = ".($_REQUEST['1v5']*1)."\n";
		$out .= "wine = ".($_REQUEST['1v3']*1)."\n";
		$out .= "cenum = \"".strip_quotes($_REQUEST['1vc'])."\"\n";
		$out .= "ind = \"".strip_quotes($_REQUEST['1v4'])."\"\n\n";
		$out .= "[scrolling]\n";
		$out .= "scroll = ".checkit($_REQUEST['scroll'])."\n";
		$out .= "scroll_inc = ".($_REQUEST['scroll_inc']*1)."\n";
		$out .= "speed = ".($_REQUEST['scroll_speed']*1)."\n\n";
		$out .= "[banner]\n";
		$out .= "showbanner = ".checkit($_REQUEST['showbanner'])."\n";
		$out .= "scrolltext = \"".strip_quotes($_REQUEST['bannerscrolltext'])."\"\n";
		$out .= "plaintext = \"".strip_quotes($_REQUEST['bannerstilltext'])."\"\n\n";
		$out .= "[appearance]\n";
		$out .= "styling = \"".strip_quotes($_REQUEST['styling'])."\"\n";
		$out .= "custom_css = \"".strip_quotes($_REQUEST['csspath'])."\"\n\n";
		if(!fwrite($fs, $out)){
			$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error writing scoreboard configuration settings.</div>";
		}else{
			$messages .= "<div style=\"width: 300px; border: 2px solid #CCCCCC; padding:5px;margin:5px; Color:#009900; background: #EEFFEE; \">Scoreboard settings updated successfully.</div>";
		}
		fclose($fs);
	}

	/* write general settings */
	if(!($fs = fopen("assets/config/".$ini_array['this_info_filename'], "w+"))){
		$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error writing general configuration settings.</div>";
	}else{
		$out = "[competition_settings]\n";
		$out .= "upload_password  = \"".$ini_array['upload_password']."\" ; should be changed!\n";
		$out .= "competition_name_full = \"".strip_quotes($_REQUEST['g11'])."\"\n";
		$out .= "competition_details = \"".strip_quotes($_REQUEST['g12'])."\"\n";
		$out .= "competition_website = \"".strip_quotes($_REQUEST['g13'])."\"\n";
		$out .= "WCA_cid = \"".strip_quotes($_REQUEST['g14'])."\"\n";
		$out .= "logo = \"".strip_quotes($_REQUEST['g15'])."\"\n";
		$out .= "favicon_url = \"".strip_quotes($_REQUEST['g16'])."\"\n";
		$out .= "schedule_link = \"".strip_quotes($_REQUEST['g17'])."\"\n";
		$out .= "refresh_time = \"".$ini_array['refresh_time']."\"\n\n";
		$out .= "[spreadsheet_settings]\n";
		$out .= "spreadsheet_filepath = \"".strip_quotes($_REQUEST['g21'])."\"\n";
		$out .= "spreadsheet_backup_filepath = \"".strip_quotes($_REQUEST['g22'])."\"\n";
		if(!fwrite($fs, $out)){
			$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error writing scoreboard configuration settings.</div>";
		}else{
			$messages .= "<div style=\"width: 300px; border: 2px solid #CCCCCC; padding:5px;margin:5px; Color:#009900; background: #EEFFEE; \">General settings updated successfully.</div>";
		}
		fclose($fs);
	}

	/* mark update */
	if(!($fs = fopen("assets/public/lastupdate.txt", "w+"))){
		$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error pushing updates.</div>";
	}else{
		if(!fwrite($fs, time())){
			$messages .= "<div style=\"width: 300px; border: 2px solid #990000; padding:5px;margin:5px;\">Error pushing updates.</div>";
		}
		fclose($fs);
	}
}

// set scoreboard variables for output
scoreboard_initialize();
$r[1]['a'] = "";
$r[1]['b'] = "";
$r[1]['b2'] = "";
$r[1]['c'] = "";
$r[1]['d'] = "";
$r[1]['e'] = "";
if($scoreboard_ini['current'] == "all"){
	$r[1]['a'] = "checked='checked'";
}elseif($scoreboard_ini['current'] == "complete"){
	$r[1]['b'] = "checked='checked'";
}elseif($scoreboard_ini['current'] == "custom"){
	$r[1]['b2'] = "checked='checked'";
}elseif($scoreboard_ini['current'] == "winners"){
	$r[1]['c'] = "checked='checked'";
}elseif($scoreboard_ini['current'] == "country"){
	$r[1]['d'] = "checked='checked'";
}elseif($scoreboard_ini['current'] == "individual"){
	$r[1]['e'] = "checked='checked'";
}
$r[1]['v1'] = "value='".$scoreboard_ini['top']."'";
$r[1]['v2'] = "value='".$scoreboard_ini['tope']."'";
$r[1]['v3'] = "value='".$scoreboard_ini['wine']."'";
$r[1]['v4'] = "value='".$scoreboard_ini['ind']."'";
$r[1]['v5'] = "value='".$scoreboard_ini['winn']."'";
$r[1]['vc'] = "value='".$scoreboard_ini['cenum']."'";

$r[2]['a'] = "";
if($scoreboard_ini['scroll']){
	$r[2]['a'] = "checked='checked'";
}
$r[2]['v1'] = "value='".$scoreboard_ini['speed']."'";
$r[2]['v2'] = "value='".$scoreboard_ini['scroll_inc']."'";
	
$r[3]['a'] = "";
if($scoreboard_ini['showbanner']){
	$r[3]['a'] = "checked='checked'";
}
$r[3]['t1'] = stripslashes($scoreboard_ini['scrolltext']);
$r[3]['t2'] = stripslashes($scoreboard_ini['plaintext']);
	
	
$r[4]['a'] = "";
$r[4]['b'] = "";
$r[4]['c'] = "";
if($scoreboard_ini['styling'] == "light"){
	$r[4]['a'] = "checked='checked'";
}elseif($scoreboard_ini['styling'] == "dark"){
	$r[4]['b'] = "checked='checked'";
}elseif($scoreboard_ini['styling'] == "custom"){
	$r[4]['c'] = "checked='checked'";
}
$r[4]['v1'] = "value='".$scoreboard_ini['custom_css']."'";

// set general variables for output

$g[1][1] = $ini_array['competition_name_full'];
$g[1][2] = $ini_array['competition_details'];
$g[1][3] = $ini_array['competition_website'];
$g[1][4] = $ini_array['WCA_cid'];
$g[1][5] = $ini_array['logo'];
$g[1][6] = $ini_array['favicon_url'];
$g[1][7] = $ini_array['schedule_link'];

$g[2][1] = $ini_array['spreadsheet_filepath'];
$g[2][2] = $ini_array['spreadsheet_backup_filepath'];

?>