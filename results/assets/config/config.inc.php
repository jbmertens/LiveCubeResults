<?php

function conf_initialize($basepath = "") {
	global $ini_array;

	$uri = $_SERVER['REQUEST_URI'];
	// get current directory
	$path = explode("/",$uri);
	if(count($path) <= 2){
		$conf_file = "default.info";
	}else{
		// check to see where script is being processed from (for now, just ~/ or ~/assets/public/)
		if($path[count($path)-2] == "public"){
			$conf_file = $path[count($path)-4].".info";
		} else {
			$conf_file = $path[count($path)-2].".info";
		}
		// make sure this file actually exists
		if(!file_exists($basepath."assets/config/".$conf_file)){
			$conf_file = "default.info";
		}
	}
	if(!file_exists($basepath."assets/config/".$conf_file)){die("Fatal error.");}
	$ini_array = parse_ini_file($basepath."assets/config/".$conf_file);

	$ini_array['this_info_filename'] = $conf_file;
}

function scoreboard_initialize($basepath = "") {
	global $scoreboard_ini;

	$uri = $_SERVER['REQUEST_URI'];
	// get current directory
	$path = explode("/",$uri);
	if(count($path) <= 2){
		$conf_file = "default.info";
	}else{
		// check to see where script is being processed from (for now, just ~/ or ~/assets/public/)
		if($path[count($path)-2] == "public"){
			$conf_file = $path[count($path)-4].".info";
		} else {
			$conf_file = $path[count($path)-2].".info";
		}
		// make sure this file actually exists
		if(!file_exists($basepath."assets/config/scoreboard/".$conf_file)){
			$conf_file = "default.info";
		}
	}
	if(!file_exists($basepath."assets/config/scoreboard/".$conf_file)){die("Fatal error.");}
	$scoreboard_ini = parse_ini_file($basepath."assets/config/scoreboard/".$conf_file);

	$scoreboard_ini['this_info_filename'] = $conf_file;
}

?>