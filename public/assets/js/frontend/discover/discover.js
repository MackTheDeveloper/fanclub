$(document).ready(function () {
    var origin = window.location.href;
    $('.load-more-discover').click(function () {
        page += 1;
        $.blockUI();
        $.ajax({
            url: origin + '/../discover/loadMore',
            method: "POST",
            data: {
                page: page,
                search: $('#search_discover').val(),
                "_token": csrfToken,
            },
            success: function (response) {
                if(response){
                    $('.discover-container').append(response);

                }else{
                    $('.load-more-discover').hide();
                }
                $.unblockUI();
            }
        }); 
    })

    $('.search_discover_btn').click(function (e) {
        e.preventDefault();
        page = 1;
        $.blockUI();
        $.ajax({
            url: origin + '/../discover/loadMore',
            method: "POST",
            data: {
                search: $('#search_discover').val(),
                "_token": csrfToken,
            },
            success: function (response) {
                $('.discover-container').html(response);
                $.unblockUI();
            }
        });
    })

    /* var search = document.getElementById("search_discover");
    search.addEventListener("keyup", function () {
        page = 1;
        $.blockUI();
        $.ajax({
            url: origin + '/../discover/loadMore',
            method: "POST",
            data: {
                search: $(this).val(),
                "_token": csrfToken,
            },
            success: function (response) {
                $('.discover-container').html(response);
                $.unblockUI();
            }
        });
    }) */
});

