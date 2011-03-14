<?php

if(!isset($ini_array)){
	include "../config/config.inc.php";
	conf_initialize("../../");
}
$cname = $ini_array['WCA_cid'];
$i = $_GET['i'];

$fp = "../cache/".$cname."/competitors.ser";
$uploaded = 0;
if(file_exists($fp)){
	$handle = file($fp);
	$competitor_array = unserialize($handle[0]);
	$uploaded = 1;
}
if(!$uploaded) {
	echo "<p class='noresults'>No results have been uploaded yet.</p>";
} elseif($i=="all") {
	// list all competitors
	echo "<table class='events' cellspacing='0'><tr><th>Name</th><th>WCA</th><th>Nationality</th></tr>";
	$r=0;
	foreach($competitor_array as $competitor){
		if($r%2){echo "<tr class='striped'>";}else{echo "<tr>";}
		echo "<td><a href='competitors.php#",urlencode($competitor['n']),"' onclick='javascript:getCompetitor(\"".htmlentities($competitor['n'])."\");'>",htmlentities($competitor['n']),"</a></td><td>";
		if($competitor['wca']!=""){echo "<a href='http://www.worldcubeassociation.org/results/p.php?i=",$competitor['wca'],"'><img src='http://www.worldcubeassociation.org/favicon.ico' /></a>";}
		echo "</td><td>",$competitor['nat'],"</td>";
		$r++;
	}
	echo "</table>";
}elseif(!isset($competitor_array[$i])){
	echo "<pclass=\"noresults\">Error: competitor not found.</p>";
}else{
	echo "<a href='assets/public/competitors_plain.php?i=".urlencode($i)."' style='float: right; display: block;'><img src='assets/img/printer_icon.gif' style='text-align:bottom;'> Printable View</a></p>";
	echo "<p class='cname'><a href='competitors.php#",urlencode($i),"'>",htmlentities($i),"</a>";
	if($competitor_array[$i]['wca']!=""){
		echo " - <a href='http://www.worldcubeassociation.org/results/p.php?i=",$competitor_array[$i]['wca'],"'>View WCA profile <img src='http://www.worldcubeassociation.org/favicon.ico' style='vertical-align: middle;' /></a>";
	}
	echo "</p>";

	echo "<table class='individual' cellspacing='0'><tr>";
	echo "<tr><th>Event</th><th>Position</th><th style=\"text-align: right;\">Average</th><th style=\"text-align: right;\">Best</th><th>Worst</th><th colspan='100'>Details</th></tr>";
	$r=0;
	foreach($competitor_array[$i]['e'] as $e){
		$file = file("../cache/".$cname."/".$e['s'].".event");
		$data = unserialize($file[0]);
		if(!is_array($data)){echo("Error.");}else{
			$headers = $data['headers'];
			echo "<tr>";
			$outputarr = Null;

			foreach($headers['n'] as $n){
				$h = $headers['d'][$n];
				$c = $data['rows'][$e['r']][$n];
				if($c['s']!=""){$c['d']="<span style='".$c['s']."'>".$c['d']."</span>";}
	
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
			echo "<tr",$striped,"><td><a href='index.php#",$e['s'],"'>",$data['info']['title'],"</a></td>";
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

if($uploaded){
?>
<p style="float: left; text-align: left;">Results last synched: <?=date("Y-m-d, h:i:sa (T)", filemtime("../cache/".$cname)) ?></p>
<p style="float: right; text-align: right;">Page loaded <?=date("Y-m-d, h:i:sa (T)") ?><br />Refreshes every <?=$ini_array['refresh_time'];?> minutes.</p>
<?php } ?>
