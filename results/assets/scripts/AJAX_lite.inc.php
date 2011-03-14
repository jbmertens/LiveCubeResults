function loadFileLite(url){
	xmlhttp=null;
	if (window.XMLHttpRequest){
		// code for Firefox, Opera, IE7, etc.
		xmlhttp=new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (xmlhttp!=null){
		xmlhttp.onreadystatechange = state_ChangeLite;
		xmlhttp.open("GET",url);
		xmlhttp.send(null);
	}else{
		alert("Your browser does not support XMLHTTP.");
	}
}

function state_ChangeLite(){
	if (xmlhttp.readyState==4){// 4 = "loaded"
		if (xmlhttp.status==200){// 200 = "OK"
			response = xmlhttp.responseText;
		}
	}
}