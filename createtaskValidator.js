$(document).ready( function(){
	var validator = $("#createtask-form").bootstrapValidator({
		fields : {
				// title validator
				title : {
					validators : {					
						notEmpty : {
							message : "Please enter title"
						}
					}
				},
				
				// description validator
				description : {
					validators : {					
						notEmpty : {
							message : "Please enter description"
						}
					}
				},

				// type validator
				type : {
					validators : {					
						notEmpty : {
							message : "Please enter type"
						}
					}
				},

				// tag1 validator
				tag1 : {
					validators : {					
						notEmpty : {
							message : "Please enter tag 1"
						}
					}
				},

				// tag2 validator
				tag2 : {
					validators : {					
						notEmpty : {
							message : "Please enter tag 2"
						}
					}
				},		

				// tag3 validator
				tag3 : {
					validators : {					
						notEmpty : {
							message : "Please enter tag 3"
						}
					}
				},		

				// tag4 validator
				tag4 : {
					validators : {					
						notEmpty : {
							message : "Please enter tag 4"
						}
					}
				},		
				
				// pages validator
				pages : {			
					validators : {					
						notEmpty : {
							message : "Please enter no of pages"
						},						
						regexp: {
							regexp : /^[0-9]+$/,
							message : "Numbers only"
						}
					}
				},
				
				// words validator
				words : {			
					validators : {					
						notEmpty : {
							message : "Please enter no of words"
						},						
						regexp : {
							regexp : /^[0-9]+$/,
							message : "Numbers only"
						}
					}	
				},	
				
				// format validator
				format : {
					validators : {					
						notEmpty : {
							message : "Please enter format"
						}
					}
				},	
				
				// deadline_claiming
				deadline_claiming : {
					validators : {						
						notEmpty : { 
							message : "Please enter deadline claiming"
						},
						regexp : {
							regexp : /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/,
							message : "format YYYY-MM-DD HH:MM:SS"
						}
					}
				}, 
				
				// deadline_completion
				deadline_completion : {
					validators : {						
						notEmpty : { 
							message : "Please enter deadline completion"
						},
						regexp : {
							regexp : /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/,
							message : "format YYYY-MM-DD HH:MM:SS"
						}
					}
				},		
				
				onfocusout : function(element) {
					this.element(element);
				}
			}	
	});
});