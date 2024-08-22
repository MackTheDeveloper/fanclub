$(document).ready(function(){
    $("#footerTab li>a:first").addClass("active").show(); //Activate first tab on load
    $(".tab_content:first").addClass("active").show();
});

// Set tab active on click
$('#footerTab li>a').click(function(e) {
    $($('#footerTab li>a').parent()).addClass("active").not(this.parentNode).removeClass("active");   
    e.preventDefault();
});

/** add footer details form validation */
$("#addFooterDetailsForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "contact_number[]": {
            required: true,
        }
    },
    messages: {
        "contact_number[]": {
            maxlength: 15
        }
    },
    errorPlacement: function (error, element) 
    {
        error.insertAfter(element)
    },
    submitHandler: function(form) 
    {    
        form.submit();
    }
});

$(".footer_about").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please write contents here'}
    });
});

$(".contact_email").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please enter contact email'}
    });
});

$(".contact_number").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please enter contact number'}
    });
});

/** update footer details form validation */
$("#updateFooterDetailsForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "contact_number[]": {
            required: true,
        }
    },
    messages: {
        "contact_number[]": {
            maxlength: 15
        }
    },
    errorPlacement: function (error, element) 
    {
        error.insertAfter(element)
    },
    submitHandler: function(form) 
    {    
        form.submit();
    }
});

$(".update_footer_about").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please write contents here'}
    });
});

$(".update_contact_email").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please enter contact email'}
    });
});

$(".update_contact_number").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please enter contact number'}
    });
});