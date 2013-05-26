var ERROR_TARGET = document.getElementById("newErrField");
var NEWPOST_INPUT = document.getElementById("newPost");
var NEWPOST_SERVER = "postprocess.php?method=js&post=";

/* Variables to be shared by every function for DOM */
var request;
var newPost;
var newId;
var newUser;

/* DOM variables end here */

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

/* this function is invoked while clicking on Update button */
function addNewPost(){
//	alert ("hello worlds");

	/* create a new XMLHttp request for Ajax Call */
	request1 = createRequest();
	if(!request1){ /* if we could not create a request element */
		/* create new div element */
			var newErr = document.createElement("div");
		/* set two attributes to that div */
			newErr.setAttribute("class", "errbox");
			newErr.setAttribute("onmouseover", "this.style.visibility='hidden', this.style.display='none';");
		/* create an error message and append it to error div */
			var newErrMsg = document.createTextNode("Sorry! Could not update the post now. Please reload page to retry!");
			newErr.appendChild(newErrMsg);
		/* append the error below the new post input form */
			ERROR_TARGET.appendChild(newErr);
		}
	
	/* if we can send new requests to the server */
	if(request1.readyState == 4 || request1.readyState == 0){
		/* get the value from the input field and store in Global Variable*/
		newPost  = encodeURIComponent(document.getElementById("topost").value);
		
		/* disable the input field to prevent more posts until one is updated */
			NEWPOST_INPUT.disabled="disabled";
		
		/* send the post to server using GET method */
		request1.open("GET", NEWPOST_SERVER + newPost, true);
		/* invoke a function processResponse when readystate changes */			
		request1.onreadystatechange = processResponse;	
		request1.send(null);
	}
	
	return true;
}

/* after the readystate of request changes, check if the response is complete and ok or not */
function processResponse(){
	if(request1.readyState == 4){
		if(request1.status == 200){
			/*if yes, parse the response XML, extract two informations and save as globals */ 
			newId = resNewId;
			newUser = resNewUsr;
			alert("now received: "+ newId);
			
			/* enable and make ready the new wallpost field for other posts */
			NEWPOST_INPUT.disabled=false;
			NEWPOST_INPUT.value = "";			
			NEWPOST_INPUT.focus();
			
			/* invoke a function to show the post in the current page */
			showLastPost();
		}
	}
}
