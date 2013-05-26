var request;
var reply;
var act;
var postNum;
var commentFrom;
var user;
var commentOn;
var commentButton;

function comment(postId, Act){
postNum = postId;
act = Act;
commentFrom = "commentField_"+postId;
toSend = encodeURIComponent(document.getElementById(commentFrom).value);

//alert("sending:"+toSend); //return;
user = document.getElementById('hiddenUsername').value;
commentButton = "commentBtn_"+postId;
commentOn = "commentsFor_"+postId;

// if refreshing the comments.. better we remove it from the page
// and then reload new comments from the server
if(act=='refresh'){
	document.getElementById(commentOn).innerHTML='Waiting for new comments...';
}
else if(act=='comment'){
	if(toSend=='') return;
	document.getElementById(commentFrom).disabled='disabled';
}
//	alert("action: "+act);

	request = createRequest();
	if(!request) {alert("Could not connect. Please retry!"); return;}
	
	if(request.readyState == 4 || request.readyState == 0){
	
		request.open("GET", "comment.php?toDo="+act+"&postId="+postId+"&content="+toSend+"&nName="+user, true);	
		request.onreadystatechange = processcommentResponse;
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

function processcommentResponse(){
	if(request.readyState == 4){
		if(request.status == 200){
		//	alert("Received: "+request.responseText);
			if(request.responseText){
			// whene the server sends the response, if user has requested
			// to refresh the comments, we replace the comments with new ones
			// but if only comment is added, we append it.. see!
			
				if(act=='comment')
					document.getElementById(commentOn).innerHTML += request.responseText;
				
				else if(act=='refresh')
					document.getElementById(commentOn).innerHTML = request.responseText;
				
				document.getElementById(commentFrom).value="";
				document.getElementById(commentFrom).disabled=false;
				document.getElementById("commentArea_"+postNum).style.display="none";
				
				// add up the comments
				if(act=='comment')
				document.getElementById("commentsCount_"+postNum).innerHTML = parseInt(document.getElementById("commentsCount_"+postNum).innerHTML) + 1;
				
			}
			else 
			{
				document.getElementById(commentOn).innerHTML = "No comments found. No. of comments will be added after a new comment is added on this post. Check back later!";
				//setTimeout("countNew(commentOn)", 10000);
			}
			//document.getElementById(commentOn).style.display = 'none';
		}
	}
}
function countNew(comOn){
	countReq = createRequest();
}
