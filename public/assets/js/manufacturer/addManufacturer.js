$( document ).ready(function() {

    formValidations.generalValidation();
    appendHtml.languageDropdown(language);
});


var formValidations = {
    //general form validations
    generalValidation : function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='addManufacturer']").validate({
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
    languageDropdown : function (language) {
        var defaultLanguage = document.getElementById('defaultLanguage');
        $.each(language, function (index,item) {
            var text = item['languageName'];
            var value = item['globalLanguageId'];
            var o = new Option(text, value);
            defaultLanguage.append(o);
        })
    }
}
