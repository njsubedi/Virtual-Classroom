// i must comment on this file,, else i will be confused later
// this contains a function 'Like' that takest the parameters
// one is the id of the post to be Liked, other is the action 
// to be done.. ie Like or Dislike
////////////////////////////////////
// request is request object;
// LikeId is the id of the post to be Liked
// LikeItem is the id of the number of Likes
// user is the name of current user


var request;
var LikeId;
var LikeItem;
var user;
var newAct;

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

function Like(postId, act){
LikeId = postId;
if(act=='Like') newAct='Dislike';
else if(act == 'Dislike') newAct='Like';

	LikeItem = "Likes_" + postId;
	user = document.getElementById("hiddenUsername").value;
	LikeButton = "LikeBtn_" + postId;
//	alert("The Like button will be: "+LikeButton);

//	alert("Found the id of post to Like: post_"+postId);
	document.getElementById(LikeButton).innerHTML = "Updating...";
	
	request = createRequest();
	if(!request) {alert("Could not connect. Please retry!"); return;}
	
	if(request.readyState == 4 || request.readyState == 0){
		var LikeThis  = encodeURIComponent(document.getElementById(LikeItem).innerHTML);
//		alert("Number of Likes already: " + LikeThis);
		var sender  = encodeURIComponent(document.getElementById("userInfo").value);
//		alert("You are going to Like/Dislike post_" + postId + " as user "+ user);
		
//		alert("Action to do: "+act);
		request.open("GET", "Like.php?toDo="+act+"&nPost="+postId+"&nName="+user, true);	
		request.onreadystatechange = processLikeResponse;
		request.send(null);
	}
}

function processLikeResponse(){
	if(request.readyState == 4){
		if(request.status == 200){
		
//		alert ("Processing response...");
//		alert ("This will update the post: "+ LikeItem);
//		alert ("Response from server is: " + request.responseText);
				document.getElementById(LikeItem).innerHTML = request.responseText;
			
//	alert("The button id to be updated is: "+LikeButton);
//	alert("it will be updated as "+newAct);
		
			document.getElementById(LikeButton).innerHTML = "<a onclick=\"Like('"+ LikeId +"' , '"+ newAct +"')\" title=\""+ newAct +"this post\" href=\"javascript:\">"+ newAct +"</a>";
		}
	}
}
