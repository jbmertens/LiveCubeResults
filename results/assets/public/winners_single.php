<?php
if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$wnum = $_GET['n']*1;
$enum = $_GET['e']*1;

if($wnum == 0){$wnum = 3;}
if($enum == 0){$enum = 3;}

$handle = file($basepath."../cache/".$cname."/events.ser");
$event_array = unserialize($handle[0]);

$event = $event_array[$enum];
if($event != ""){
	$file = file($basepath."../cache/".$cname."/".$event['n'].".event");
	$edata = unserialize($file[0]);
	
	echo "<h2>",$event['title']," - Winners</h2>";
	echo "<table>";
	if(!is_array($edata)){echo("Error.");}else{
		$headers = $edata['headers'];
		$rows = $edata['rows'];

		$r=1;
		foreach($rows as $row){
			if($row[1]['d']*1 > 0 && $row[1]['d']*1 <= $wnum){
				$outputarr = NULL;
				foreach($headers['n'] as $n){
					$h = $headers['d'][$n];
					$c = $row[$n];

					if($h == "Place"){
						$outputarr["pos"] = $c['d'];
					}elseif($h == "Position"){
						$outputarr["pos"] = $c['d'];
					}elseif($h == "Name"){
						$outputarr["name"] = $c['d'];
					}elseif($h == "Nationality"){
						$outputarr["nat"] = $c['d'];
					}elseif($h == "Country"){
						$outputarr["nat"] = $c['d'];
					}elseif($h == "Rank"){
						$outputarr["pos"] = $c['d'];
					}elseif($h == "Average"){
						$outputarr["avg"] = $c['d'];
					}elseif($h == "Best"){
						$outputarr["best"] = $c['d'];
					}elseif($h == "Worst"){
						$outputarr["worst"] = $c['d'];
					}elseif($h!="WCA id"&&$h!="Country"&&$h!="Name"){
						$outputarr["details"] .= "&nbsp;".$c['d'];
					}
				}
				if($r%2){$striped=" class='striped'";}else{$striped="";}
				echo "<tr",$striped,"><td class='pos'><strong>",$outputarr["name"],"</strong> (",$outputarr["nat"],") achieved <strong>",placestr($outputarr["pos"]),"</strong> place in <strong>",$event['title'],"</strong> with ";
				if(isset($outputarr["avg"])){
					echo "a best average of <strong>",$outputarr["avg"],"</strong> ";
				}elseif(isset($outputarr["best"])){
					echo "a best single solve of <strong>",$outputarr["best"],"</strong> ";
				}else{
					echo " the a result of: <strong>",$outputarr["details"],"</strong>";
				}
				echo "</td></tr>";
				$r++;
			}
		}
	}
	echo "</table>";
}else{
	echo "<p>Error: event not found.</p>";
}

function placestr($place){
	$place=$place*1;
	if($place==0){
		return "a winning";
	}elseif(substr($place, -1) == "1"){
		if(substr($place,-2)=="11"){ return $place."th"; }
		return $place."st";
	}elseif(substr($place, -1) == "2"){
		if(substr($place,-2)=="12"){ return $place."th"; }
		return $place."nd";
	}elseif(substr($place, -1) == "3"){
		if(substr($place,-2)=="13"){ return $place."th"; }
		return $place."rd";
	}else{
		return $place."th";
	}
}

?>