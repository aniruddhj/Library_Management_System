		
function validate()
	{
		var username= document.getElementById("username").value;
		var password= document.getElementById("password").value;
		
		if(username === "admin" && password === "admin")
			{
				window.location="Page1.html";
			}			
                        
			else
                        {
                            alert("Enter a valid username or password");
			
                        }
	}



