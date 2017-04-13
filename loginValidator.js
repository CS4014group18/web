$(document).ready( function(){
	var validator = $("#login-form").bootstrapValidator({
		fields : {
				id : {				
					validators : {					
						notEmpty : {
							message: "Please enter a valid ID"
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
			
				password : {
					validators: {
						notEmpty : {
							message: "Password is required"
						}
					}
				},
			
				onfocusout: function(element) {
					this.element(element);
				}			
		}
	});
});