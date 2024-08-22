$.validator.addMethod("username", function(value, element) {
    return this.optional(element) || /^[a-z0-9]+$/i.test(value);
}, "Username must contain only letters, numbers, or dashes.");
$.validator.addMethod("notEqual", function(value, element, param) {
    return this.optional(element) || value != $(param).val();
}, "Please specify a different (non-default) value");

$.validator.addMethod("onlyNchecked", function(value, element, param) {
    var totalChecked = $('input[name="'+element.name+'"]:checked').length;
    return (totalChecked==2)?true:false;
    // return false;
}, "minimum or maximum 2 checkbox are need to checked");

$("#updateProfileForm").validate( {
    ignore: [],
    rules: {
        firstname: "required",
        lastname: "required",
        "design_preferences[]": {
            required:true,
            onlyNchecked:true,
        },
        // design_preferences_1: { notEqual: "#design_preferences_2" },
        // design_preferences_2: { notEqual: "#design_preferences_1" },
        phone: {
            required:true,
            minlength:10,
            maxlength:10
        },
        handle:{
            required:true,
            username:true,
        },
        // area: "required",
        city: "required",
        // current_home: "required",
        // future_stay: "required",
        
    },
    messages:{
        firstname: "First name Number is required",
        lastname: "Last name Number is required",
        phone: {
            required:"Phone Number is required"
        },
        // design_preferences_1: "Both Design Preference can not be same",
        // design_preferences_2: "Both Design Preference can not be same",
        handle:{
            required:"Handle is required",
            username:"Handle must contain only letters or numbers",
        },
        area: "Area is required",
        city: "City is required",
        current_home: "Current home is required",
        future_stay: "Future Stay",
    },
    errorPlacement: function ( error, element ) {
        if ( element.prop( "type" ) === "checkbox" ) {
            // console.log('element',element);
            // console.log('error',error[0]);
            element.closest('.input-label').find('.checkbox_error').html(error[0])
        } else {
            error.insertAfter( element );
        }
    },
});

    // $("#addPost").validate({
//   ignore: [], // ignore NOTHING
//   rules: {
//     "image": {
//       required: true,
//     },
//     "category_id": {
//       required: true,
//     },
//     "caption_text": {
//       required: true,
//     },
//   },
//   messages: {
//     "image": {
//       required: "Please select image"
//     },
//     "category_id": {
//       required: "Please select category"
//     },
//     "caption_text": {
//       required: "Please enter caption"
//     },
//   },
//   errorPlacement: function (error, element) {
//     if (element.attr("name") == "image") {
//       var pos = $('.btn-file');
//       error.insertAfter(pos);
//     } else {
//       error.insertAfter(element);
//     }
//   },
//   submitHandler: function (form) {
//     form.submit();
//   }
// });

var imageArray = [];
var urlArray = [];
var csrf = $('meta[name="csrf-token"]').attr('content');

$("#file-1").fileinput({
    theme: 'fas',
    showUpload: false,
    //maxFileCount: maxFileCount,
    showCaption: false,
    browseClass: "fill-btn",
    removeClass: "add-post-remove",
    removeLabel : "",
    removeIcon: '<div class="close-imgs"></div>',
    // fileType: "any",
    allowedFileTypes: ['image','video'],
    allowedFileExtensions: ['jpg', 'gif', 'png','MP4'],
    // previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreview: imageArray,
    initialPreviewConfig: urlArray,
    // initialPreviewConfig: [
    //     {url: "1/delete"},
    //     {url: "1/delete"}
    // ],
    deleteExtraData: {
        "_token": csrf
    }
});

var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;
$("body").on("change", ".image", function (e) {

    var ext = $(this).val().substring($(this).val().lastIndexOf('.') + 1).toLowerCase();
    if (ext != 'png' && ext != 'jpg') {
        $(this).val('');
        alert('Please select valid file (png,jpg)');
        return false;
    }

    var files = e.target.files;
    var done = function (url) {
        image.src = url;
        $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
        file = files[0];
        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
});
$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        //autoCropArea: 0,
        responsive : true,
        dragMode: 'none',
        strict: true,
        guides: false,
        rounded: true,
        highlight: true,
        viewMode: 3,
        preview: '.preview',
        movable: false,
        resizable: false,
        cropBoxResizable : true,
        data: {
            width: 400,
            height: 400,
        },
        dragCrop: false,
    });
}).on('hidden.bs.modal', function () {
    cropper.destroy();
    cropper = null;
});
function getRoundedCanvas(sourceCanvas) {
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var width = sourceCanvas.width;
    var height = sourceCanvas.height;

    canvas.width = width;
    canvas.height = height;
    context.imageSmoothingEnabled = true;
    context.drawImage(sourceCanvas, 0, 0, width, height);
    context.globalCompositeOperation = 'destination-in';
    context.beginPath();
    context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
    context.fill();
    return canvas;
}
$("#crop").click(function () {
    canvas = cropper.getCroppedCanvas({
        /* width: 1000,
        height: 1000, */
    });
    canvas.toBlob(function (blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            var base64data = reader.result;
            $('.previewImg').css('background-image', 'url(' + base64data + ')');
            $('.hiddenPreviewImg').val(base64data);
            console.log(base64data);
            $modal.modal('hide');
            /* $.ajax({
                type: "POST",
                dataType: "json",
                url: "crop-image-upload",
                data: {
                    '_token': $('meta[name="_token"]').attr('content'),
                    'image': base64data
                },
                success: function (data) {
                    console.log(data);
                    $modal.modal('hide');
                    alert("Crop image successfully uploaded");
                }
            }); */
        }
    });
})