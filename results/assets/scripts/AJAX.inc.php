var interval;
var xmlhttp;

function getCompetitor(id){
	if(id == ""){ id = "all"; }
	setHash(id);
	loadFile('assets/public/competitors.php?i='+id);
}

function getEvent(q){
	q = q*1;
	setHash(q);
	loadFile('assets/public/default.php?q='+q);
}

function loadFile(url){
	interval=window.clearInterval(interval);
	document.getElementById('wcaimage').innerHTML = "<a href=\"<?php echo $ini_array['WCA_website'];?>\"><img src=\"assets/img/WCA_anim.gif\" width=\"64\" alt=\"WCA Logo\"/><\/a>";
	xmlhttp=null;
	if (window.XMLHttpRequest){
		// code for Firefox, Opera, IE7, etc.
		xmlhttp=new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (xmlhttp!=null){
		xmlhttp.onreadystatechange=state_Change;
		xmlhttp.open("GET",url);
		xmlhttp.send(null);
	}else{
		alert("Your browser does not support XMLHTTP.");
	}
	interval=setInterval("loadFile('"+url+"')",(<?=$ini_array['refresh_time'];?>*60*1000));
}

function state_Change(){
	if (xmlhttp.readyState==4){// 4 = "loaded"
		if (xmlhttp.status==200){// 200 = "OK"
			//document.getElementById("selection").disabled=false;
			document.getElementById('maindisplay').innerHTML=xmlhttp.responseText;
			document.getElementById('wcaimage').innerHTML = "<a href=\"<?php echo $ini_array['WCA_website'];?>\"><img src=\"assets/img/WCA_logo.png\" width=\"64\" alt=\"WCA Logo\"/><\/a>";
		}else{
			alert("Problem retrieving data: " + xmlhttp.statusText);
		}
	}
}

function getHash(){
	return window.location.hash.substring(1);
}
function setHash(target){
	window.location.hash = "#"+target;
}