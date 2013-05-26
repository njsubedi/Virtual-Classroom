// this is to post new posts in vcrooms

var request;
function update(){
//	alert ("hello worlds");
	
	request = createRequest();
	if(!request) document.getElementById("newpost").innerHTML = "Sorry! Could not update now. Reload page to retry!";
	
	if(request.readyState == 4 || request.readyState == 0){
		var query  = encodeURIComponent(document.getElementById("topost").value);
		var sender  = encodeURIComponent(document.getElementById("userInfo").value);
		var lastTime = encodeURIComponent(document.getElementById("hiddenTime").value);
//		alert("We are sending" + query);
//		alert("time sending: " + lastTime);
		
		
		document.getElementById("topost").disabled="disabled";
		
		request.open("GET", "vcposter.php?nPost="+query+"&nName="+sender+"&timeStamp="+lastTime, true);			
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
//		alert("now received: "+ request.responseText);
			document.getElementById("allPosts").innerHTML = request.responseText + document.getElementById("allPosts").innerHTML;
			document.getElementById("topost").disabled=false;
			document.getElementById("topost").value = "";			
			document.getElementById("topost").focus();
		}
	}
}

 function sizeBox(a) {
	
		var ta = a;
	
		var maxrows = 30;
	
		var lh = ta.clientHeight / ta.rows;
	
		while (ta.scrollHeight > ta.clientHeight && !window.opera && ta.rows < maxrows) {
	
			ta.style.overflow = 'hidden';
	
			ta.rows += 1;
		}
	
		if (ta.scrollHeight > ta.clientHeight) ta.style.overflow = 'auto';
	}
			