<?php

if(!isset($ini_array)){
	include "assets/config/config.inc.php";
	conf_initialize();
}
$cdir = $ini_array['WCA_cid'];
if($cdir == ""){ die("Fatal error.  Please check the WCA competition id code."); }

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);          
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function handle_css($css){
	$patterns = array(1 => '/text-align:\w+\;/', 2 => '/font-size:\w+\;/', 3 => '/font-family:[^\;]+\;/');	$css = preg_replace($patterns, '', $css);
	return $css;
}

function parsetime($time) {
	if($time=="" || $time*1==0){return $time;}
	$time = round($time*86400, 2);
	$sec = round(fmod(round($time, 2), 60), 2); $time -= $sec;
	$min = $time/60;
	if($sec < 10){$sec = "0".$sec;}
	if(round(fmod($sec, 1), 2) == 0){
		$sec .= ".00";
	}else if(round(fmod($sec+.00001, .1), 2) == 0){ //fmod sucks.
		$sec .= "0";
	}
	$time = $min.":".$sec;
	return $time;
}

function get_sheet_info($sheet){
	$info['title'] = $sheet['cells'][1][1];
	$info['format'] = $sheet['cells'][2][1];
	$info['status']['c'] = 0;
	$info['status']['t'] = 0;

	return $info;
}

if(!file_exists("assets/cache/".$cdir)) {
	mkdir("assets/cache/".$cdir);
} else {
	delete_directory("assets/cache/".$cdir);
	mkdir("assets/cache/".$cdir);
}

require_once 'reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($ini_array['spreadsheet_filepath']);
error_reporting(E_ALL ^ E_NOTICE);
echo "<b>Writing to: ",$ini_array['spreadsheet_filepath'],"<br/></b>";

$competitor_data;
$event_data;

$s = 0;
foreach($data->sheets as $sheet_data){
	// make sure the sheet should be used (indicated by title in A1 cell) and isn't first sheet (registration)
	if($sheet_data['cells'][1][1] != "" && $s!=0){
		$sheet_info = get_sheet_info($sheet_data);
		$sheet_info['n'] = $s;

		unset($processed_sheet);
		unset($sheet_headers);
		unset($sheet_rows);

		echo "<br/><b>New Sheet:".$sheet_info['title']."</b>";
		$r = 1;
		foreach($sheet_data['cells'] as $row){
			if($r==4){
				$c=1;
				foreach($row as $header_cell){
					if($header_cell != ""){
						$sheet_headers['n'][] = $c;
						$sheet_headers['d'][$c] = $header_cell;
						echo "<br/>Column: ".$header_cell;
					}
					$c++;
				}
			}elseif($r>4){
				if($row[1] != "" && $row[2] != ""){
					unset($processed_row);
					foreach($sheet_headers['n'] as $c){
						if($c == 2){
							$uid = $row[2];
							$competitor_data[$uid]['wca'] = $row[4];
							$competitor_data[$uid]['nat'] = $row[3];
							$competitor_data[$uid]['n'] = $row[2];
							$competitor_data[$uid]['e'][$s]['s'] = $s;
							$competitor_data[$uid]['e'][$s]['r'] = $r;
						}
						if(stristr($row[$c], "m:ss.0")){
							$processed_row[$c]['d'] = parsetime($data->raw($r,$c,$s));
						}else{
							$processed_row[$c]['d'] = $row[$c];
						}
						$processed_row[$c]['s'] = handle_css($data->style($r,$c,$s));
					}
					if($processed_row[5]['d'] != ""){
						$sheet_info['status']['c']++;
					}
					$sheet_rows[$r] = $processed_row;
					$sheet_info['status']['t']++;
				}
			}
			$r++;
		}
		echo "<br/>Competitors / data: ",$sheet_info['status']['c']," of ",$sheet_info['status']['t'];

		// amass processed data
		$processed_sheet['info'] = $sheet_info;
		$processed_sheet['headers'] = $sheet_headers;
		$processed_sheet['rows'] = $sheet_rows;
		$processed_sheet['enum'] = ($s-1);

		// output sheet to file
		$filepath = 'assets/cache/'.$cdir."/".$s.".event";
		if(file_exists($filepath)){ unlink($filepath); }
		$output = serialize($processed_sheet);
		$fhandle = fopen($filepath, 'w');
		fwrite($fhandle, $output);

		// store event info
		$event_data[$s] = $sheet_info;
	}
	$s++;
}

// output events file
$filepath = 'assets/cache/'.$cdir."/events.ser";
$output = serialize($event_data);
$fhandle = fopen($filepath, 'w');
fwrite($fhandle, $output);

// output competitors file
$filepath = 'assets/cache/'.$cdir."/competitors.ser";
ksort($competitor_data, SORT_STRING); // alphabetize
$output = serialize($competitor_data);
$fhandle = fopen($filepath, 'w');
fwrite($fhandle, $output);

?>
