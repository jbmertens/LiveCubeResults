<?php
	include "assets/config/config.inc.php";
	conf_initialize();
	scoreboard_initialize();

	$cname = $ini_array['WCA_cid'];
	$display = $scoreboard_ini['current'];

	$filedata = file("assets/cache/".$cname."/events.ser");
	$eventdata = unserialize($filedata[0]);
	$num_events = count($eventdata);

	$q = $_GET['q']*1;
	if($display == "winners"){
		$_GET['e'] = $scoreboard_ini['tope'];
		$_GET['n'] = $scoreboard_ini['top'];
	}elseif($display == "country"){
		$_GET['n'] = $scoreboard_ini['winn'];
		$_GET['e'] = $scoreboard_ini['wine'];
	}elseif($display == "individual"){
		$_GET['i'] = $scoreboard_ini['ind'];
	}elseif($display == "complete"){
		$q++;
		while($eventdata[$q]['status']['c'] == 0){
			$q++;
			if($q > $num_events){ $q=0; break; }
		}
	}elseif($display == "custom"){
		$events = explode(",", $scoreboard_ini['cenum']);
		foreach($events as $num => $enum){
			if($enum == $q){
				$next = $num+1;
			}
		}
		if(!isset($next) || $next >= count($events)){ $next = 0; }
		$q = $events[$next];
	}else{ /* if display = all */
		$q++;
		if($q > $num_events){ $q=1; }
	}

	$u = $_GET['u'];
	if($u==1){ // if just updated
		if($display == "complete"){
			$_GET['q'] = 0; $q = 0;
			$q++;
			while($eventdata[$q]['status']['c'] == 0){
				$q++;
				if($q > $num_events){ $q=0; break; }
			}
		}elseif($display == "custom"){
			$_GET['q'] = $q;
			$events = explode(",", $scoreboard_ini['cenum']);
			foreach($events as $num => $enum){
				if($enum == $q){
					$next = $num+1;
				}
			}
			if(!isset($next) || $next >= count($events)){ $next = 0; }
			$q = $events[$next];
		}else{ /* if display = all */
			$_GET['q'] = 0; $q = 0;
			$q++;
			if($q > $num_events){ $q=1; }
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Live Results - <?=$ini_array['competition_name_full']; ?></title>
<link rel="icon" href="<?=$ini_array['favicon_url']; ?>">
<link rel="stylesheet" type="text/css" href="assets/css/results.css" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<?php if($scoreboard_ini['styling'] == "dark"){?>
	<link rel="stylesheet" type="text/css" href="assets/css/colors_dark.css" />
<?php } elseif($scoreboard_ini['styling'] == "light") { ?>
	<link rel="stylesheet" type="text/css" href="assets/css/colors.css" />
<?php }else { ?>
	<link rel="stylesheet" type="text/css" href="<?=$scoreboard_ini['custom_css'];?>" />
<?php } ?>
<script type="text/javascript">
window.onload=startscroll;
var scrolldelay;
var updatecheck;
var scrollpos = 200;
var inc = <?=$scoreboard_ini['scroll_inc'];?>;
var lastupdate = "<?=time();?>";
var response = 0;
function startscroll(){
	checkNext();
	<?php if($scoreboard_ini['scroll']){ ?>
		scrolldelay = setTimeout('pageScroll()',10000); // begin scrolling after 10 seconds
	<?php } ?>
}
function pageScroll() {
	window.scrollBy(0,inc); // horizontal and vertical scroll increments
	scrollpos += inc;
	if(scrollpos >= document.getElementById('results').offsetHeight*1){
		scrolldelay = setTimeout('loadNext(0)',10000); // loads next event after 10 seconds pause at the bottom
	}else{
		scrolldelay = setTimeout('pageScroll()',<?=$scoreboard_ini['speed'];?>); // scrolls every x milliseconds
	}
}
function loadNext(u) {
	window.scrollTo(0,0); // reset page to top in case it is reloading
	window.location.href = "scoreboard.php?q=<?=$q;?>&u="+u;
}
function checkNext(){
	loadFileLite("assets/public/lastupdate.txt?r="+Math.random());
	response *= 1;
	if(response >= lastupdate){
		loadNext(1);
	}
	updatecheck = setTimeout('checkNext()',2*1000); // check for updates constantly every 2 seconds
}
<?php include("assets/scripts/AJAX_lite.inc.php"); ?>
</script>
</head>
<?php
	if($scoreboard_ini['styling'] == "dark"){
		$color = array(1  => '#000022', 2  => '#002200', 3  => '#330000');
	} else {
		$color = array(1  => '#000066', 2  => '#006600', 3  => '#660000');
	}
	srand ((double) microtime() * 1000000);
	$randnum = rand(1,3);
?>
<body style="background: <? echo "$color[$randnum]"; ?>;">

	<div id="top_banner" class="scoreboard_head" <?php if(!$scoreboard_ini['showbanner']){ ?>style="display: none;"<?php } ?>>
		<div id="right" style="width: 15%; display: block; float: right;">
			<!-- scoreboard right block -->
			<u>current time</u><br/>
			<?=date("h:i A (T)");?>
		</div>
		<div id="left" style="width: 15%; display: block; float: left;">
			<!-- scoreboard left block -->
		</div>

		<div id="middle" style="width: 70%; margin: 0 auto;">
			<!-- scoreboard center -->
			<marquee style="font-size: 27px;">
				<?=stripslashes($scoreboard_ini['scrolltext']);?>
			</marquee>
			<p style="font-size: 27px;">
				<?=stripslashes($scoreboard_ini['plaintext']);?>
			</p>
		</div>
	</div>

	<div class="container projector" id="results" <?php if(!$scoreboard_ini['showbanner']){ ?>style="margin-top: 10px;"<?php } else { ?>style="margin-top: 140px;"<?php } ?>>
		<div>
			<div id="maindisplay" class="larger">
				<?php
					$basepath = "assets/cache/";
					if($display == "complete" || $display == "all" || $display = "custom"){
						include("assets/public/projector.php");
					}elseif($display == "winners"){
						if($scoreboard_ini['tope']){
							include("assets/public/winners_single.php");
						}else{
							include("assets/public/winners.php");
						}
					}elseif($display == "country"){
						include("assets/public/winners_country.php");
					}elseif($display == "individual"){
						echo "<h2 style='text-align: center;'>Results for ",$scoreboard_ini['ind'],"</h2>";
						include("assets/public/competitors_plain.php");
					}else{
						echo "<p>Fatal Error.</p>";
					}
				?>
			</div>
		</div>	
	</div>

</body>

</html>