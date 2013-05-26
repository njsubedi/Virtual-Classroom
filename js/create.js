var request;
function checkUnique()
{
	document.getElementById("isUnique").innerHTML = "Checking...";
	request = createRequest();
	if(request.readyState == 4 || request.readyState == 0){
		var query  = encodeURIComponent(document.getElementById("uText").value);
		request.open("GET", "isU.php?u="+query, true);	
		request.onreadystatechange = processResponse;	
		request.send(null);
	}
}

function createRequest(){
	var requestobject;
	try{
		requestobject =  new XMLHttpRequest();
	}
	catch (e){
		try{
			requestobject = new ActiveXObject(Microsoft.XMLHTTP);
		}
		catch (e){
			$err="Failed to check";
		}
	}
	if(!requestobject){
		$err="Failed to check";
	}
	else 
		return requestobject;
}

function processResponse(){
	if(request.readyState == 4){
		if(request.status == 200){
			document.getElementById("isUnique").innerHTML = request.responseText;
			if(request.responseText=="")
				document.getElementById("isUnique").innerHTML ="<img src='../icons/no.gif' alt='You must fill up this field' />&nbsp;You must fill this field";
		}
	}
}
function colord(){
	a = document.getElementById("uText");
	if(a.value=="")
		a.style.backgroundColor='#ffff99';
	else
		a.style.backgroundColor='#ffffff';
}
