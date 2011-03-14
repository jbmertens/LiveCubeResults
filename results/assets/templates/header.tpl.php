<?php
	include "assets/config/config.inc.php";
	conf_initialize();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>

<title>Live Results - <?=$ini_array['competition_name_full']; ?></title>
<link rel="icon" href="<?=$ini_array['favicon_url']; ?>">
<link rel="stylesheet" type="text/css" href="assets/css/results.css" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="assets/css/colors.css" />

</head>
<?php
	$color = array(
		1  => '#000066',
		2  => '#006600',
		3  => '#660000',
	);
	srand ((double) microtime() * 1000000);
	$randnum = rand(1,3);
?>
<body style="background: <? echo "$color[$randnum]"; ?>;">