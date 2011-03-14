<?php

$cname = $ini_array['WCA_cid'];

$fp = "assets/cache/".$cname."/competitors.ser";
if(file_exists($fp)){
	$handle = file($fp);
	$competitor_array = unserialize($handle[0]);
	$uids = array_keys($competitor_array);
	$uploaded = 1;
} else {
	$uploaded = 0;
}

?>

<script type="text/javascript">
window.onload=init;
function init(){
	var i = getHash();
	getCompetitor(i);	
}
<?php require_once("assets/scripts/AJAX.inc.php"); ?>
</script>

<form>
<select name="i" onchange="javascript:getCompetitor(this.value);" size="17"><optgroup label="Select One">
<?php
if($uploaded){
	foreach($uids as $u){
		echo "<option value='",urlencode($u),"' class='",$class,"'>",htmlentities($u),"</option>";
	}
}
?>
</optgroup></select>
</form>

<noscript>
	<p style="text-align: center;">Please make sure you have javascript enabled for full functionality.</p>
</noscript>
