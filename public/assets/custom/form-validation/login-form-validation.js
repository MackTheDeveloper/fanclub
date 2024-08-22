$(document).ready(function() {

    $("#loginForm").validate( {
        rules: {
            email: {
                required: true,
                email: true,
            }, 
            password: {
                required: true,
                minlength : 6
            },
        },
        messages: {
            email:{
                required: 'Please enter email address',
                email: 'Please enter a valid email address',
            },
            password:{
                required: "Please enter password",
                minlength: "Password must be at least 6 digit"
            },                                    
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

