<?php
if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$wnum = $_GET['n']*1;


if($wnum == 0){$wnum = 3;}

$handle = file($basepath."../cache/".$cname."/events.ser");
$event_array = unserialize($handle[0]);

foreach($event_array as $event){
	$file = file($basepath."../cache/".$cname."/".$event['n'].".event");
	$edata = unserialize($file[0]);
	
	echo "<h2>",$event['title'],"</h2>";
	echo "<table>";
	echo "<tr><th>#</th><th>Name</th><th>Country</th><th style=\"text-align: right;\">Average</th><th style=\"text-align: right;\">Best</th><th>Worst</th><th colspan='100'>Details</th></tr>";
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
				echo "<tr",$striped,"><td class='pos'>",$outputarr["pos"],"</td>";
				echo "<td class='name'>",$outputarr["name"],"</td>";
				echo "<td class='nat'>",$outputarr["nat"],"</td>";
				echo "<td class='avg'>",$outputarr["avg"],"</td>";
				echo "<td class='best'>",$outputarr["best"],"</td>";
				echo "<td class='worst'>",$outputarr["worst"],"</td>";
				echo "<td class='details'>",$outputarr["details"],"</td>";
				echo "</tr>";
				$r++;
			}
		}
	}
	echo "</table>";
}

?>