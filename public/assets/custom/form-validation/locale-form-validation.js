$(document).ready(function() {

    $("#localeForm").validate( {
        rules: {
            code: "required",
            title: "required",   
            locale_textarea: {
                required: true,
                minlength: 5,
                maxlength: 30,
                lettersonly: true
            },         
        },
        messages: {
            code: "Code is required",
            title: "Title is required", 
            locale_textarea: {
                required: "Please fill locale details",
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

