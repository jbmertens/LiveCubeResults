<?php include "assets/templates/header.tpl.php"; ?>

<div class="container" id="results">

	<div class="column narrow" id="menu">
		<?php
			require_once "assets/templates/tabs.tpl.php";
			print_tabs("index");
			require_once "assets/scripts/live.inc.php";
		?>
	</div>
	<div class="column wide">
		<?php require_once "assets/templates/title.tpl.php"; ?>
		<div id="maindisplay"></div>
	</div>	
</div>

<?php include "assets/templates/footer.tpl.php"; ?>