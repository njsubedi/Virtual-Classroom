function validate(a){
	var returnvalue = true;
	
	if(!a.username.value){
		a.username.style.borderColor = "#FF0000";
		returnvalue = false;
	}	
	if(!a.password.value){
		a.password.style.borderColor = "#FF0000";
		returnvalue = false;
	}
	return returnvalue;
}