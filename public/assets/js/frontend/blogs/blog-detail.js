$("#addCommentToBlog").validate({
  ignore: [], // ignore NOTHING
  rules: {
    "name": {
      required: true,
    },
    "email": {
      email: true,
      required: true,
    },
    "comment": {
      required: true,
    },
  },
  messages: {
    "name": {
      required: "Please enter name"
    },
    "email": {
      required: "Please enter email"
    },
    "comment": {
      required: "Please enter comment"
    },
  },
  errorPlacement: function (error, element) {
    error.insertAfter(element)
    //error.appendTo(element.parent().parent().parent());
    //$(element.parent().parent()).css('margin-bottom', '0');
  },
  submitHandler: function (form) {
    var form = $("#addCommentToBlog");
    var formData = form.serialize();
    var origin = window.location.href;
    $.ajax({
      url: origin + '/../addCommentToBlog',
      type: 'POST',
      data: formData,
      success: function (data) {
      }
    });
  }
});