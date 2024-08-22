// Forms Multi Select

$( document ).ready(function() {

    setTimeout(function () {

        $(".multiselect-dropdown").select2({
            theme: "bootstrap4",
            placeholder: "Select an option",
            allowClear: true
        });

        $('#example-single').multiselect({
            inheritClass: true
        });

        $('#example-multi').multiselect({
            inheritClass: true
        });

        $('#example-multi-check').multiselect({
            inheritClass: true
        });

    }, 2000);

});





// $('.input,.textarea').val("");



$('.inputs-group input, .inputs-group textarea').focusout(function() {
    var text_val = $(this).val();

    console.log(text_val)

    if (text_val === "") {
      $(this).removeClass('has-value');
    } else {
      $(this).addClass('has-value');
    }
});
$(document).ready(function(){
    if($('.inputs-group input').val() != ''){
      $('.inputs-group input').addClass('has-value');
    }
  })
var checkAutoFill = function(){
    $('input:-webkit-autofill').each(function(){
        $(this).addClass('has-value');
    });
}
setTimeout(function(){ 
    checkAutoFill();
}, 500)







$(document).click(function(){
    
    if ($(event.target).parents().hasClass('header-search')) {
        console.log("header-child")
    }
    else if(event.target.className.includes('header-search')){
        console.log("hader-parent")
    }
    else if(event.target.className.includes("show")){
        console.log("show")
    }
    else if($(event.target).parents().hasClass('search-dropdown')){
        console.log("show-parent")
    }
    else{
        $(".search-dropdown").removeClass("show")
    }
})