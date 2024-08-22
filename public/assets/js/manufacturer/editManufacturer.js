$( document ).ready(function() {
    init.handler();
    formValidations.generalValidation();
    appendHtml.appendNonDefaultLanguage(nonDefaultLanguage);
    var ident = 2;
    console.log(manufacturer);
    $('#status option[text="' + manufacturer['status'] +'"]').prop("selected", true);
    $('#defaultLanguage option[value="' + manufacturer['languageId'] +'"]').prop("selected", true);

    if ($('#defaultLanguage').val() == defaultLanguageId) {
        $('.commonElement').addClass('d-none');
    }
    
});

var init = {

    handler : function() {
        $('body').on('change','#defaultLanguage', function() {
            if($(this).val() == defaultLanguageId){
                $('.commonElement').removeClass('d-none');
            }else{
                $('.commonElement').addClass('d-none');
            }
        })
    }
};

var formValidations = {
    //general form validations
    generalValidation : function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='editManufacturer']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                brandName: "required",
                status: "required",
            },
            // Specify validation error messages
            messages: {
                brandName: "Please Enter Brand Name",
                status: "Please Select Status",
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }
}

var appendHtml = {
    appendNonDefaultLanguage : function (nonDefaultLanguage) {
        var defaultLanguage = document.getElementById('defaultLanguage');

        $.each(nonDefaultLanguage, function (index,item) {
            var value = item['id'];
            var text = item['language']['langEN'];
            var o = new Option(text, value);
            defaultLanguage.append(o);
        });
    }
}
