<?php
if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$wnum = $_GET['n']*1;
$enum = $_GET['e']*1;

if($wnum == 0){$wnum = 1;}
if($enum == 0){$enum = 3;}

$handle = file($basepath."../cache/".$cname."/events.ser");
$event_array = unserialize($handle[0]);

$event = $event_array[$enum];
if($event != ""){
	$file = file($basepath."../cache/".$cname."/".$event['n'].".event");
	$edata = unserialize($file[0]);
	
	echo "<h2>",$event['title']," - Winners by Country</h2>";
	if(!is_array($edata)){echo("Error.");}else{
		$headers = $edata['headers'];
		$rows = $edata['rows'];

		$r=1;
		$countries;
		foreach($rows as $row){
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

			if(!isset($countries[$outputarr["nat"]]['best']) || $outputarr["best"] < $countries[$outputarr["nat"]]['best']){
				$countries[$outputarr["nat"]]['best'] = $outputarr["best"];
			}
			if(!isset($countries[$outputarr["nat"]]['avg']) || $outputarr["avg"] < $countries[$outputarr["nat"]]['avg']){
				$countries[$outputarr["nat"]]['avg'] = $outputarr["avg"];
			}
			$countries[$outputarr["nat"]]['nat'] = $outputarr["nat"];

			$countries[$outputarr["nat"]]['d'][$outputarr["pos"]]['p'] = $outputarr["pos"];
			$countries[$outputarr["nat"]]['d'][$outputarr["pos"]]['n'] = $outputarr["name"];
			$countries[$outputarr["nat"]]['d'][$outputarr["pos"]]['a'] = $outputarr["avg"];
			$countries[$outputarr["nat"]]['d'][$outputarr["pos"]]['b'] = $outputarr["best"];
			$countries[$outputarr["nat"]]['d'][$outputarr["pos"]]['d'] = $outputarr["details"];
			$outputarr = NULL;
		}
	}

	if($_GET['s']=="best"){
		uasort($countries, "bestsort");
	}elseif($_GET['s']=="average"){
		uasort($countries, "avgsort");			
	}else{
		ksort($countries); // sort alpha by nation
	}

	if($wnum == 1){
		echo "<table>";
		echo "<th>Country</th><th>Name</th><th>Position</th><th>Best</th><th>Average</th><th>Details</th>";
		foreach($countries as $country){
			$nation = $country['d'];
	
			$r = 1;
			$prevposition = 0;
			foreach($nation as $competitor){
				if($prevposition == $competitor["p"]){ $r--; } // if tied
				if($r <= $wnum){
					$prevposition = $competitor["p"];
					echo "<tr>";
					echo "<td>",$country["nat"],"</td>";
					echo "<td>",$competitor["n"],"</td>";
					echo "<td>",$competitor["p"],"</td>";
					echo "<td>",$competitor["b"],"</td>";
					echo "<td>",$competitor["a"],"</td>";
					echo "<td>",$competitor["d"],"</td>";
				}
				$r++;
			}
			echo "</table>";
		}
	}else{
		foreach($countries as $country){
			$nation = $country['d'];
	
			echo "<h2>",$country['nat'],"</h2>";
			echo "<table>";
			echo "<th>Name</th><th>Position</th><th>Best</th><th>Average</th><th>Details</th>";
			$r = 1;
			$prevposition = 0;
			foreach($nation as $competitor){
				if($prevposition == $competitor["p"]){ $r--; } // if tied
				if($r <= $wnum){
					$prevposition = $competitor["p"];
					echo "<tr>";
					echo "<td>",$competitor["n"],"</td>";
					echo "<td>",$competitor["p"],"</td>";
					echo "<td>",$competitor["b"],"</td>";
					echo "<td>",$competitor["a"],"</td>";
					echo "<td>",$competitor["d"],"</td>";
				}
				$r++;
			}
			echo "</table>";
		}		
	}
}else{
	echo "<p>Error: event not found.</p>";
}

function bestsort($a, $b){
	if ($a["best"] == $b["best"]) {
		return 0;
	}
	return ($a["best"] < $b["best"]) ? -1 : 1;
}
function avgsort($a, $b){
	if ($a["avg"] == $b["avg"]) {
		return 0;
	}
	return ($a["best"] < $b["avg"]) ? -1 : 1;
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