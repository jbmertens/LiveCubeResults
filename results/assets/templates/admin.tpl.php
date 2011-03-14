<?php

$basepath = "/home/koii/www/cm/beta/results/";
$ini_array = parse_ini_file($basepath."assets/vars.ini"); $success=0;

?>

<?php	if(!file_exists($basepath.".htaccess")){ ?>
	<div style="padding:5px; width: 300px; border:2px solid #FF0000; margin: 5px;">
		WARNING!  The .htaccess file was not found! Please make sure your filesystem is protected.
	</div>
<?php } ?>

<div id="file_upload" style="width: 350px; float: left;  border-left: 1px solid #DDD; padding: 10px;">

<form enctype="multipart/form-data" action="" method="POST">
	<h2>Spreadsheet Upload</h2>

<div style="padding:5px; width: 300px; border:2px solid #9090FF; margin: 5px;">
<?php
	$flag = 1;
	if(!is_writable($basepath."assets/cache")){
		echo "Error: Cannot write to cache!<br />";
		$flag = 0;
	}
	if(file_exists($basepath.$ini_array['spreadsheet_filepath']) && !$basepath.is_writable($ini_array['spreadsheet_filepath'])){
		echo "Error: Cannot write to spreadsheet!<br />";
		$flag = 0;
	}
	if(!is_writable($basepath.$ini_array['spreadsheet_backup_filepath'])){
		echo "Error: Cannot write to backup folder!<br />";
		$flag = 0;
	}
	if(file_exists($basepath."assets/cache/".$ini_array['competition_name']) && !$basepath.is_writable("assets/cache/".$ini_array['competition_name'])){
		echo "Error: cannot write to competition folder in cache!";
		$flag = 0;
	}
	if($flag){
		echo "Filesystem is writable.";
	}
?>
</div>

<?php

if(isset($_REQUEST['fileupload'])){
	if($_REQUEST['pass']==$ini_array['upload_password']){
		$target_path = $basepath.$ini_array['spreadsheet_filepath']; 
		if(file_exists($target_path)){ unlink($target_path); }
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)&&copy($target_path, $basepath.$ini_array['spreadsheet_backup_filepath'].$ini_array['competition_name']."_".date("Ymd_Hi")."_".$_SERVER['REMOTE_ADDR'].".xls")) {
			$success=1;
			echo "<div style=\"width: 400px; height:500px; border: 2px solid #EEEEEE; overflow:scroll;\">"; include $basepath."assets/scripts/cache.inc.php"; echo "</div>";
			echo "<div style=\"width: 300px; border: 2px solid #EEEEEE; padding:5px;margin:5px;\">The file ".  basename($_FILES['uploadedfile']['name'])." has been uploaded</div>";
		} else{
			echo "<div style=\"width: 300px; border: 2px solid #EEEEEE; padding:5px;margin:5px;\">There was an error uploading the file, please try again!</div>";
		}
	}else{
		echo "<div style=\"width: 300px; border: 2px solid #EEEEEE; padding:5px;margin:5px;\">Password Error.</div>";
	}
}

?>

	<input type="hidden" name="fileupload" value="true" />

	<div style="padding:5px; width: 300px; border:2px solid #E8E847; margin: 5px;">
		Choose a workbook to upload: <input name="uploadedfile" type="file" /><br />
	</div>

	<p>&nbsp;</p>
	<h2>Scoreboard Settings</h2>

	<input type="hidden" name="scoreboardupdate" value="true" />
	<input type="hidden" name="fileupload" value="true" />

	<div style="padding:5px; width: 300px; border:2px solid #90FF90; margin: 5px;">
		Display:<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="all" onclick="javascript:displaymenu('events');"> All Events Results<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="all" onclick="javascript:displaymenu('event_winners');"> Event Winners<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="single" onclick="javascript:displaymenu('country');"> Winners by Country<br />
		<INPUT TYPE="RADIO" NAME="display" VALUE="single" onclick="javascript:displaymenu('persons');"> Individual Results<br />
		<script type="text/javascript">
			// window.onload=displaymenu('');
			function displaymenu(mi){
				document.getElementById('events').style.display="none";
				document.getElementById('event_winners').style.display="none";
				document.getElementById('country').style.display="none";
				document.getElementById('persons').style.display="none";
				if(mi!=''){document.getElementById(mi).style.display="";}
			}
		</script>
		<div id="country" style="display: none; width: 100%;">
			Select Event Number
			Select # of competitors to display by country
			Order by best, avg, or worst
		</div>
		<div id="events" style="display: none; width: 100%;">
			Select single event to display, to display all events, or to display all completed events
		</div>
		<div id="event_winners" style="display: none; width: 100%;">
			Select Number of Winners to display in event (default top 3)
			Select Event number to display, or display all events
		</div>
		<div id="persons" style="display: none; width: 100%;">
			Select person to display or select to scroll through all persons
		</div>
		<a onclick="" style="cursor:pointer; text-decoration: underline;">View sample scoreboard text</a>
	</div>

	<div style="padding:5px; width: 300px; border:2px solid #FF5050; margin: 5px;">
		<INPUT TYPE="CHECKBOX" NAME="banner"> Display Banner<br />
		Banner Text:<br />
		<textarea rows="4" cols="30" name="bannertext">
			
		</textarea>
	</div>

	<div style="padding:5px; width: 300px; border:2px solid #CCCCCC; margin: 5px;">
		Scoreboard Styling:<br />
		<INPUT TYPE="RADIO" NAME="styling" VALUE="light" /> Dark text on light background<br />
		<INPUT TYPE="RADIO" NAME="styling" VALUE="dark" /> Light text on dark background<br />
	</div>

	<p>&nbsp;</p>
	<input type="submit" value="Submit Changes" />
</form>

</div>

<br style="clear: both;" />
<p>Live Results v2.2d</p>
