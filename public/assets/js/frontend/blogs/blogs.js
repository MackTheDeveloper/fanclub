$(document).ready(function () {
    var origin = window.location.href;
    $('.dropdwn-togle-toplinks li a').click(function () {
        $.blockUI();
        var categoryId = $(this).data('category_id')
        $.ajax({
            url: origin + '/../blogs/getByCategory/' + categoryId,
            method: "POST",
            data: {
                categoryId: categoryId,
                "_token": csrfToken,
            },
            success: function (response) {
                $('.blogsContainer').html(response);
                $.unblockUI();
            }
        }); 
    })
});