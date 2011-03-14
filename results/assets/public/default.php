<?php
if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$enum = $_GET['q'];

$fp = "../cache/".$cname."/events.ser";
if(file_exists($fp)){
	$fs = fopen($fp, "r"); $data = "";
	if($fs){ while (!feof($fs)) { $data .= fread($fs, 8192); } }
	$events = unserialize($data);
	$uploaded = 1;
} else {
	$uploaded = 0;
}

if(!isset($events[$enum])){ $enum=0; }

if(!$uploaded) {
	echo "<p class='noresults'>No results have been uploaded yet</p>";
} elseif($enum==0) {
	// list events
	echo "<table cellspacing='0' class='events'><tr><th>Event</th><th>Format</th><th>Status</th></tr>";
	$r=0;
	foreach($events as $event){
		if($r%2){echo "<tr class='striped'>";}else{echo "<tr>";}

		if($event['status']['t']==0 || $event['status']['c']==0){$class = "nostart";
		}elseif($event['status']['c']/$event['status']['t']<1){$class = "partway";
		}else{$class = "done";}

		echo "<td><a href='index.php#",$event['n'],"' onclick='javascript:getEvent(\"".htmlentities($event['n'])."\");'>",$event['title'],"</a></td>";
		echo "<td>",strtr($event['format'],array("Format: " => "")),"</td><td><span class='",$class,"'>";
		if($event['status']['t']==0 || $event['status']['c']==0){echo "not begun";
		}elseif($event['status']['c']/$event['status']['t']<1){echo "in progress";
		}else{echo "complete";}
		echo "</span></td></tr>";
		$r++;
	}
	echo "</table>";
} else {
	// output data
	$fs = fopen("../cache/".$cname."/".$enum.".event", "r"); $data = "";
	if($fs){
		while (!feof($fs)) { $data .= fread($fs, 8192); }
		$data = unserialize($data);
	}
	if(!is_array($data)){echo("Error.");}
	elseif(count($data['rows'])==0)
	{
	     echo "<p style='font-size:1.4em;'><a href=\"index.php#",$enum,"\">",$events[$enum]['title'],"</a> - ",$events[$enum]['format'],"</p>";
	     echo "<p class=\"noresults\">No results for this round yet.</p>";
	}
	else{
	     
		echo "<p style='font-size:1.4em;'><a href=\"#",$enum,"\">",$events[$enum]['title'],"</a> - ",$events[$enum]['format'],"</p>";
		echo "<table cellspacing='0'><tr>";
		foreach($data['headers']['n'] as $hnum){
			if ($hnum != 4){
				echo "<th>",$data['headers']['d'][$hnum],"</th>";
			}
		}
		echo "</tr>";
		$r=0;
		
		$name_col_num = array_search('Name', $data['headers']['d']);
          if(!$name_col_num)
               $name_col_num = -1;
               
		$avg_col_num = array_search('Average', $data['headers']['d']);
          if(!$avg_col_num)
               $avg_col_num = -1;
               
		$best_col_num = array_search('Best', $data['headers']['d']);
          if(!$best_col_num)
               $best_col_num = -1;
		
		foreach($data['rows'] as $row){
			if($r%2){echo "<tr class='striped'>";}else{echo "<tr>";}
			foreach($data['headers']['n'] as $c){
				if($c!=4){
					if($row[$c]['s'] != ""){echo "<td style='",$row[$c]['s'],"'>";}else{echo "<td>";}
					if($c==2){
						echo "<a href='competitors.php#",urlencode($row[2]['d']),"'>",htmlentities($row[2]['d']),"</a>";
					}elseif($c==$best_col_num){
						echo '<span class="best">'.$row[$c]['d'].'</span>';
					}elseif($c==$avg_col_num){
						echo '<span class="avg">'.$row[$c]['d'].'</span>';
					}else{
						echo $row[$c]['d'];
					}
					echo "</td>";
				}
			}
			echo "</tr>";
			$r++;
		}
		echo "</table>";
	}
}

if($uploaded) {
?>
<p style="float: left; text-align: left;">Results last synched: <?=date("Y-m-d, h:i:sa (T)", filemtime("../cache/".$cname)) ?></p>
<p style="float: right; text-align: right;">Page loaded <?=date("Y-m-d, h:i:sa (T)") ?><br />Refreshes every <?=$ini_array['refresh_time'];?> minutes.</p>
<?php } ?>