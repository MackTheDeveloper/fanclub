$(document).ready(function() {

    $("#addNewUser").validate( {
        rules: {
            first_name: "required",
            last_name: "required",            
            phone: {
                required: true,
                number: true,
                minlength : 10,
                maxlength : 10,
            },            
            email: {
                required: true,
                email: true,
            },            
            select_role: "required",            
            password: {
                required: true,
                minlength : 6
            },
            confirm_password : {   
                required: true,             
                equalTo : "#password"
            }                        
        },
        messages: {
            first_name: "Please enter first name",
            last_name: "Please enter last name",            
            phone: {
                required: "Please enter phone number",
                number: "Phone number must be in digit",
                minlength: "Phone number must be valid upto 10 digit",
                maxlength: "Phone number must be valid upto 10 digit"
            }, 
            email:{
                required: 'Please enter email address',
                email: 'Please enter a valid email address',
            },           
            select_role: "Please select any one value from dropdown",            
            password:{
                required: "Please enter password",
                minlength: "Password must be at least 6 digit"
            },
            confirm_password: {
                required: "Please enter confirm password",
                equalTo: "Confirm password is not same as password"
            }
        },
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
    } );

});

