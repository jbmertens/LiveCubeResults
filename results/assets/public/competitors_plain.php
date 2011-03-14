<?php
if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$i = $_GET['i'];

$handle = file($basepath."../cache/".$cname."/competitors.ser");
$competitor_array = unserialize($handle[0]);
if($i=="all"){
	// list all competitors
	echo "<table class='events' cellspacing='0'><tr><th>Name</th><th>WCA</th><th>Nationality</th></tr>";
	$r=0;
	foreach($competitor_array as $competitor){
		if($r%2){echo "<tr class='striped'>";}else{echo "<tr>";}
		echo "<td>",htmlentities($competitor['n']),"</td><td>";
		if($competitor['wca']!=""){echo "<a href='http://www.worldcubeassociation.org/results/p.php?i=",$competitor['wca'],"'><img src='http://www.worldcubeassociation.org/favicon.ico' /></a>";}
		echo "</td><td>",$competitor['nat'],"</td>";
		$r++;
	}
	echo "</table>";
}elseif(!isset($competitor_array[$i])){
	echo "<pclass=\"noresults\">Error: competitor not found.</p>";
}else{
	echo "<table class='individual' cellspacing='0'><tr>";
	echo "<tr><th>Event</th><th>Position</th><th style=\"text-align: right;\">Average</th><th style=\"text-align: right;\">Best</th><th>Worst</th><th colspan='100'>Details</th></tr>";
	$r=0;
	foreach($competitor_array[$i]['e'] as $e){
		$file = file($basepath."../cache/".$cname."/".$e['s'].".event");
		$data = unserialize($file[0]);
		if(!is_array($data)){echo("Error.");}else{
			$headers = $data['headers'];
			echo "<tr>";
			$outputarr = Null;

			foreach($headers['n'] as $n){
				$h = $headers['d'][$n];
				$c = $data['rows'][$e['r']][$n];
				// no css!  if($c['s']!=""){$c['d']="<span style='".$c['s']."'>".$c['d']."</span>";}
	
				if($h == "Position"){
					$outputarr["pos"] = $c['d'];
				}elseif($h == "Place"){
					$outputarr["pos"] = $c['d'];
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
	
			// output
			if($r%2){$striped=" class='striped'";}else{$striped="";}
			echo "<tr",$striped,"><td>",$data['info']['title'],"</td>";
			echo "<td>",$outputarr["pos"],"</td>";
			echo "<td class=\"avg\">",$outputarr["avg"],"</td>";
			echo "<td class=\"best\">",$outputarr["best"],"</td>";
			echo "<td class=\"worst\">",$outputarr["worst"],"</td>";
			echo "<td class=\"details\">",$outputarr["details"],"</td>";
			echo "</tr>";
			$r++;
		}
	}
	echo "</table>";
}

?>