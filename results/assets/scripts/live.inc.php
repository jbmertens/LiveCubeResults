<?php
	$cname = $ini_array['WCA_cid'];
	if(!isset($_GET['q'])){$enum=0;}else{$enum=$_GET['q'];}

	$fp = "assets/cache/".$cname."/events.ser";
	if(file_exists($fp)) {
		$fs = fopen($fp, "r"); $data = "";
		if($fs){ while (!feof($fs)) { $data .= fread($fs, 8192); } }
		$events = unserialize($data);
		$uploaded = 1;
	} else {
		$events = NULL;
		$uploaded = 0;
	}
?>

<script type="text/javascript">
window.onload=init;
function init(){
	var q = getHash()*1;
	selectEvent(q);
}

<?php require_once("assets/scripts/AJAX.inc.php"); ?>
</script>

<form>
<?php
	$i=1;
	$matches = array(1=>"round", 2=>"prelim", 3=>"final", 4=>"qual");
	$roundspecifiers = '/\W*((round.*)|(\w+\s+round.*)|(prelim.*)|(final.*)|(qual.*)$)/i';
	$groups;
	if($uploaded){ 
		foreach($events as $event){
			$rdata = preg_split($roundspecifiers, $event['title'], -1, PREG_SPLIT_DELIM_CAPTURE);
			$groups[$rdata[0]][$i]['d'] = $rdata[1];
			$groups[$rdata[0]][$i]['i'] = $i;
			$i++;
		}
	}
?>
<select name="q" id="selection" onchange="javascript:selectEvent(this.value);document.getElementById('selection').focus();" size="<?=count($groups)+1;?>">
<option value="0" class="done">Event List</option>
<?php

	function shorten_event_name($event_name) { //Should be used only in menu selection
		// $trans = array(" Speedsolve" => "", " Blindfolded" => " BLD", " One-Handed" => " OH", " Qualification Round" => " Qualifications", "Rubik's Cube" => "3x3x3", "Rubik's" => "");
		$trans = array("Rubik's Cube" => "3x3x3");
		$event_name = strtr($event_name, $trans);
		return $event_name;
	}

	$i=1;
	$submenus = "";

	if($uploaded){
		$enames = array_keys($groups);
		foreach($enames as $event){
	
			$c=1; $et=0; $ec=0;
			$size = count($groups[$event]);
			if($size<=1){$size=2;}
			$submenus .= "<div id='".$i."' style='display:none;'><h3>".shorten_event_name($event)." Round:</h3><select id='submenu".$i."' onchange=\"javascript:selectEvent(this.value);\" size='".$size."'>";
	
			foreach($groups[$event] as $round){
				$et+=$events[$round['i']]['status']['t']; $ec+=$events[$round['i']]['status']['c'];
				if($events[$round['i']]['status']['t']==0 || $events[$round['i']]['status']['c']==0){$class="nostart";
				}elseif($events[$round['i']]['status']['c']/$events[$round['i']]['status']['t']<1){$class="partway";
				}else{$class="done";}
	
				if($c == 1) { $firstevent = $round['i']; }
	
				if($round['d']==""){$title="Round ".$c;}else{$title=$round['d'];}
				$submenus .= "<option value='".$round['i']."' class='".$class."'>".$title."</option>";
				$c++;
			}
	
			if($et==0 || $ec==0){$class="nostart";
			}elseif($ec/$et<1){$class="partway";
			}else{$class="done";}
	
			echo "<option value='",$firstevent,"' class='",$class,"'>",shorten_event_name($event),"</option>";
	
			$submenus .= "</select></div>";
			$i++;
		}
	}
?>
</select>

<script type="text/javascript">
function showMenu(n){
	for(var i=1; i<<?=count($groups)+1;?>; i++){
	        document.getElementById(i).style.display = "none";
	}
	if(n!=0){
		document.getElementById(n).style.display = '';
	}
}
function selectEvent(q){
	var associations = new Array();
	associations[0] = new Array(0,0);
	<?php
	if($uploaded){ 
		$i = 1;
		foreach($enames as $event){
			$c = 0;
			foreach($groups[$event] as $round){
				echo "associations[",$round['i'],"] = new Array(",$i,",",$c,");\n";
				$c++;
			}
			$i++;
		}
	}
	?>
	showMenu(associations[q][0]);
	if(q != 0){
		document.getElementById("submenu"+associations[q][0]).selectedIndex = associations[q][1];
	}
	getEvent(q);
}
</script>

<div id="submenu"><?=$submenus;?></div>
</form>

<noscript>
<div style="text-align: center;">Please make sure you have javascript enabled for full functionality.</div>
</noscript>
