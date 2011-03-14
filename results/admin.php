<?php 
	include "assets/config/config.inc.php";
	conf_initialize();
	scoreboard_initialize();
	include "assets/scripts/admin.inc.php";

	$cname = $ini_array['WCA_cid'];
	$fp = "assets/cache/".$cname."/events.ser";
	if(file_exists($fp)){
		$filedata = file($fp);
		$eventdata = unserialize($filedata[0]);
		$uploaded = 1;
	} else {
		$uploaded = 0;
		$eventdata = Array(1 => Array("n" => "Error", "title" => "No Workbook Uploaded!"));
	}
?>
<html>
<head><title>Results Administration</title></head>
<body style="background: #DDD;<?=$bodystyles?>">

<?php if(!$uploaded) { ?>
	<div style="width: 880px; margin: 10px auto; text-align: center; background: #FEE; padding: 0px; border: 1px solid #F00;">
		<h2 style="color: #900;">Waring: no workbook has been uploaded yet!</h2>
	</div>	
<?php } ?>

<div id="wrapper" style="width: 800px; margin: 0 auto; background: #fff; padding: 10px 40px; border: 1px solid #CCC;">

<h1>Live Results Management</h1>

<form enctype="multipart/form-data" action="admin.php" method="POST">

<div id="left_col" style="width: 350px; float: left; clear: left; border-left: 1px solid #DDD; padding: 10px;">
<h2>Messages</h2>
<?=$messages;?>

<h2>Spreadsheet Upload</h2>
	<div style="padding:5px; width: 300px; border:2px solid #F89047; margin: 5px;">
		Choose a workbook to upload: <input name="uploadedfile" type="file" /><br />
	</div>

<h2>General Site Settings</h2>
	<div style="padding:5px; width: 300px; border:2px solid #2222BB; margin: 5px;">
		Full competition name: <input name="g11" type="text" size="18" value="<?=$g[1][1];?>" /><br />
		Competition Details: <input name="g12" type="text" size="18" value="<?=$g[1][2];?>" /><br />
		Competition homepage: <input name="g13" type="text" size="10" value="<?=$g[1][3];?>" /><br />
		WCA Competition ID: <input name="g14" type="text" size="10" value="<?=$g[1][4];?>" /><br />
		Logo URL: <input name="g15" type="text" size="20" value="<?=$g[1][5];?>" /><br />
		Favicon URL: <input name="g16" type="text" size="20" value="<?=$g[1][6];?>" /><br />
		Competition Schedule URL: <input name="g17" type="text" size="6" value="<?=$g[1][7];?>" />
	</div>
	<div style="padding:5px; width: 300px; border:2px solid #009900; margin: 5px;">
		Spreadsheet Filepath: <input name="g21" type="text" size="6" value="<?=$g[2][1];?>" /><br />
		Spreadsheet Backup Filepath: <input name="g22" type="text" size="6" value="<?=$g[2][2];?>" />
	</div>

</div>

<div id="right_col" style="width: 350px; float: right; border-left: 1px solid #DDD; padding: 10px;">
	<h2>Scoreboard Settings</h2>

	<div style="padding:5px; width: 300px; border:2px solid #90FF90; margin: 5px;">
		Results to Display:<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="all" <?=$r[1]['a'];?>> All events results<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="complete" <?=$r[1]['b'];?>> Completed event results only<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="custom" <?=$r[1]['b2'];?>><a style="font-weight: bold; text-decoration: underline; color: blue; cursor: pointer;" onclick="javascript:custom_help();">?</a> Results from event numbers <input name="1vc" type="text" size="3"  <?=$r[1]['vc'];?> /> <br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="winners" <?=$r[1]['c'];?>><a style="font-weight: bold; text-decoration: underline; color: blue; cursor: pointer;" onclick="javascript:winners_help();">?</a> Top <input name="1v1" type="text" maxlength="3" size="1"  <?=$r[1]['v1'];?> /> people in event # <input name="1v2" type="text" size="1" <?=$r[1]['v2'];?> /><br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="country" <?=$r[1]['d'];?>><a style="font-weight: bold; text-decoration: underline; color: blue; cursor: pointer;" onclick="javascript:country_help();">?</a> Top <input name="1v5" type="text" size="1" <?=$r[1]['v5'];?> /> per country in event # <input name="1v3" type="text" size="1" <?=$r[1]['v3'];?> /><br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="individual" <?=$r[1]['e'];?>><a style="font-weight: bold; text-decoration: underline; color: blue; cursor: pointer;" onclick="javascript:competitor_help();">?</a> Individual results for <input name="1v4" type="text" size="5" <?=$r[1]['v4'];?> /><br />
		<script type="text/javascript">
			function getEvents(){
				var events = "";
				<?php foreach($eventdata as $event){
					echo "events += \"",$event['n']," - ",$event['title'],"\\n\";\n";
				} ?>
				return events;
			}
			function custom_help(){
				var message = "";
				message += "Displays results from a custom list of events.  Enter a comma separated list of events, with NO WHITESPACE.";
				message += "\n\nEvent Codes:";
				message += "\n\n";
				message += getEvents();
				alert(message);
			}
			function winners_help(){
				var message = "";
				message += "Displays the top competitors in a specific event, or events.\n\nEnter 0 to display all events.";
				message += "\n\nEvent Codes:";
				message += "\n\n";
				message += getEvents();
				alert(message);
			}
			function country_help(){
				var message = "";
				message += "Displays the top competitors for each country in a specific event, or events.\n\nEnter 0 to display all events.";
				message += "\n\nEvent Codes:";
				message += "\n\n";
				message += getEvents();
				alert(message);
			}
			function competitor_help(){
				var message = "";
				message += "Insert a competitor's name to display their results (include spaces or foreign characters).";
				alert(message);
			}
		</script>
	</div>
	<div style="padding:5px; width: 300px; border:2px solid #222; margin: 5px;">
		<INPUT TYPE="CHECKBOX" NAME="scroll" value="on" <?=$r[2]['a'];?>> Scroll scoreboard page if necessary<br />
		Scroll at a rate of <input name="scroll_inc" type="text" size="2" maxlength="1" <?=$r[2]['v2'];?> /> pixels per <input name="scroll_speed" type="text" size="1" maxlength="4" <?=$r[2]['v1'];?> /> ms<br />
		<span style="font-size: 12px;">Note that the smoothness per scroll speed will be limited by your displays hardware capabilities.  You likely won't be able to scroll faster than a few pixel increments per second.</span>
	</div>

	<div style="padding:5px; width: 300px; border:2px solid #FF5050; margin: 5px;">
		<INPUT TYPE="CHECKBOX" NAME="showbanner" value="on" <?=$r[3]['a'];?>> Display banner at top of scoreboard
		<p style="font-size: 11px; border-left: 1px solid #000; margin: 0px; padding: 2px 0 2px 3px;">It is recommended you display no more than 3 or 4 lines total on the banner.  You should not use double quotes in the text.</p>
		Scrolling Text (may be html):<br />
		<textarea rows="2" cols="30" name="bannerscrolltext"><?=$r[3]['t1'];?></textarea><br />
		Stationary Text (may be html):<br />
		<textarea rows="2" cols="30" name="bannerstilltext"><?=$r[3]['t2'];?></textarea><br />
	</div>

	<div style="padding:5px; width: 300px; border:2px solid #FF50FF; margin: 5px;">
		Scoreboard Styling:<br />
		<INPUT TYPE="RADIO" NAME="styling" VALUE="light" <?=$r[4]['a'];?> /> Light text on light background<br />
		<INPUT TYPE="RADIO" NAME="styling" VALUE="dark" <?=$r[4]['b'];?> /> Dark text on dark background<br />
		<INPUT TYPE="RADIO" NAME="styling" VALUE="custom" <?=$r[4]['c'];?> /> Custom css path/url: <input name="csspath" type="text" size="10" <?=$r[4]['v1'];?> />
	</div>
	<div style="padding:5px; width: 300px; border:2px solid #CCC; margin: 5px;">
		<a onclick="" style="cursor:pointer; text-decoration: underline; color: #999999;">View scoreboard display with current settings</a><br />
		<span style="font-size: smaller; color: #999999;">(under construction)</span>
	</div>
	</p>
</div>

<div style="width: 350px; float: left; clear: left; border-left: 1px solid #DDD; padding: 10px; margin-top: 15px;">
	<div style="padding:5px; width: 300px; border:2px solid #EEEE50; margin: 5px; float: left;">
		<input type="hidden" name="action" value="submission" />
		Enter Password:<input type="password" name="pass" /><br />
		<input type="submit" value="Submit All Changes" />
		<p>Live Results v2.3</p>
	</div>
</div
</form>

<br style="clear: both;" />
</div>

</body>
</html>