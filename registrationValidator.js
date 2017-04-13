$(document).ready( function(){
	var validator = $("#register-form").bootstrapValidator({
		fields : {
				// Firstname validator
				firstname: {
					validators : {			
						notEmpty : { 
							message : "Please enter firstname"	
						},
						regexp: {
							regexp: /^[A-Za-z\s.'-]+$/,
							message: "Alphabetical characters, hyphens and spaces"
						}
					}
				},
				
				// Lastname validator
				lastname: {
					validators : {						
						notEmpty : { 
							message : "Please enter lastname"
						},
						regexp: {
							regexp: /^[A-Za-z\s.'-]+$/,
							message: "Alphabetical characters, hyphens and spaces"
						}
					}
				},
				
				// id validator
				id : {			
					validators : {					
						notEmpty : {
							message : "Please enter a valid ID"
						},						
						regexp: {
							regexp: /^[0-9]+$/,
							message: "Numbers only"
						},
						stringLength : {
							min : 7,
							max : 8,
							message : "Invalid length"
						}
					}	
				},
				
				// Subject validator this wont work first auto selected?
				subject : {
					validators : {			
						notEmpty : { 
							message : "A subject must be selected"	
						}
					}
				},
				
				// email validator
				email : {
					validators : {					
						notEmpty : {
							message : "Please enter an email address"
						},					
						stringLength : {
							min : 6,
							max : 50,
							message : "Email must be between 6 and 50 characters long"
						},									
						emailAddress : {
							message : "Invalid email address"
						},
						regexp : {
							regexp : /^[A-Za-z0-9._%+-]+@studentmail.ul.ie$/, 
							message : "Only studentmail.ul.ie email addresses are accepted"
						}
					}			
				},
				// confirm_email validator
				confirm_email : {
					validators : {				
							notEmpty : {
							message : "Please confirm email"
						},	
						identical : {
							field : "email",
							message : "The email and its confirmation are not the same"
						}
					}				
				},	
				
				// password validator
				password : {
					validators : {
						notEmpty : {
							message : "Password is required"
						},					  
						stringLength : {
							min : 6,
							message : "Password must be at least 6 characters long"
						},					
						different : {
							field : "email",
							message : "Email and password must be different"
						},
					}
				},
			
				// confirm_password validator
				confirm_password : {				
					validators : {					
						notEmpty : {
							message : "Please confirm password"
						},
						identical : {						
							field : "password",
							message : "Password and confirm password must match"	
						}						
					}	
				},						
				onfocusout: function(element) {
					this.element(element);
				}
			}	
	});
});