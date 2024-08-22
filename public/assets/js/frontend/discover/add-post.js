$("#addPost").validate({
  ignore: [], // ignore NOTHING
  rules: {
    "image": {
      required: true,
    },
    "category_id": {
      required: true,
    },
    "caption_text": {
      required: true,
    },
  },
  messages: {
    "image": {
      required: "Please select image"
    },
    "category_id": {
      required: "Please select category"
    },
    "caption_text": {
      required: "Please enter caption"
    },
  },
  errorPlacement: function (error, element) {
    if (element.attr("name") == "image") {
      var pos = $('.file-loading1');
      error.insertAfter(pos);
    } else {
      error.insertAfter(element);
    }
  },
  submitHandler: function (form) {
    form.submit();
  }
});

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
  if (ext != 'png' && ext != 'jpg' && ext != 'jpeg') {
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
    //aspectRatio: 1,
    //autoCropArea: 0,
    responsive : true,
    dragMode: 'none',
    strict: false,
    guides: false,
    highlight: true,
    viewMode: 3,
    preview: '.preview',
    movable: false,
    resizable: false,
    cropBoxResizable : false,
    data: {
      width: 392,
      height: 391,
    },
    dragCrop: false,
  });
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  cropper = null;
});
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
      $('.previewImgDiv').show('slow');
      $('.previewImg').attr('src', base64data);
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