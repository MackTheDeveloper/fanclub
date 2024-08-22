/** add  blog cateogry form validation */
$("#addBlogForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "title": {
            required: true,
        },
        "blog_category_id": {
            required: true,
        },
        "image": {
            required: (checkRecord == 1) ? true : false,
            extension: "jpg,jpeg,png"
        },
        "cover_image": {
            required: (checkRecord == 1) ? true : false,
            extension: "jpg,jpeg,png"
        },
        "short_description": {
            required: true,
        },
    },
    messages: {
        "title": {
            required: "Please enter title"
        },
        "blog_category_id": {
            required: "Please select category"
        },
        "image": {
            required: "Please select image"
        },
        "cover_image": {
            required: "Please select cover image"
        },
        "short_description": {
            required: "Please enter short description"
        },
    },
    errorPlacement: function (error, element)
    {
        //error.insertAfter(element)
        error.appendTo(element.parent().parent().parent());
        //$(element.parent().parent()).css('margin-bottom', '0');
    },
    submitHandler: function(form)
    {
        form.submit();
    }
});

/* $(".ckeditor").each(function(){
    $(this).rules("add", {
        required:true,
        messages:{required:'Please write blog cateogry body'}
    });
}); */



/** blog cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete blog cateogry */
    $('#Tdatatable').on('click', 'tbody .blogs_delete', function () {
        var blogs_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#blogsDeleteModel').on('show.bs.modal', function(e){
            $('#blogs_id').val(blogs_id);
            $('#message_delete').text(message);
        });
        $('#blogsDeleteModel').modal('show');
    })

    $(document).on('click','#deleteBlogs', function(){
        var blogs_id = $('#blogs_id').val();
        $.ajax({
            url: origin + '/../delete/' + blogs_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                blogs_id: blogs_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#blogsDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#blogsDeleteModel').modal('hide')
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        });
    })

    $('#Tdatatable').on('click', '.active-inactive-link', function () {
        var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        toggleButton.trigger('click');
    });
    /** toggle active switch and show confirmation */
    $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
        var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var blogs_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#blogsIsActiveModel').on('show.bs.modal', function(e){
            $('#blogs_id').val(blogs_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#blogsIsActiveModel').modal('show');
    });


    /** Activate or deactivate blog cateogry */
    $(document).on('click','#blogsIsActive', function(){
        var blogs_id = $('#blogs_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "blogs_id": blogs_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#blogsIsActiveModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        })
    });
})

$(document).on('click', '#filter_blogs', function () {
    var status = $('#status').val();
    var categoryId = $('#blog_category_id').val();
    DatatableInitiate(status, categoryId);
});

function DatatableInitiate(status = '', categoryId = '') {
    $('#Tdatatable').DataTable(
        {
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            /* 'stateSave': true,
            stateSaveParams: function (settings, data) {
                delete data.order;
            }, */
            "columnDefs": [{
                "targets": [-1],
                "orderable": false
            },
            {
                targets: [0],
                className: "hide_column"
            },
            {
                targets: [1,3],
                className: "text-left"
            },
            {
                targets: [2,4,5,6],
                className: "text-center", orderable: false, searchable: false
            }],
            "order": [[0, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: function (d) {
                    d.status = status;
                    d.categoryId = categoryId;
                },
                error: function () {  // error handling
                    $(".Tdatatable-error").html("");
                    $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#Tdatatable_processing").css("display", "none");

                }
            },
            // "bStateSave": true,
            // "fnStateSave": function (oSettings, oData) {
            //     localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
            // },
            // "fnStateLoad": function (oSettings) {
            //     return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
            // }
        });
}
