/** Ajax - datatable for cms_page listing */
var submitMode = $('input[name="submitMode"]').val(); //edit or create
var footerType = $('input[name="type"]:checked').val(); // cms
if(footerType){
    getDataByType(footerType,submitMode);
}



$(document).ready(function () {
    var origin = window.location.href;
    var table = $('#footer_listing').DataTable({
        language: {
            searchPlaceholder: "Search by Name, Type..."
        },
        aaSorting: [[2, 'asc']],
        processing: true,
        serverSide: true,
        "scrollX": true,
        ajax: {
            "url": origin,
            "type": "GET"
        },
        'columnDefs': [
            {
                "targets": [0,1,2],
                orderable: true, searchable: false
            },
            {
                "targets": [3,4],
                orderable: false, searchable: false
            },
            {
                "targets": [0,1],
                "className": "text-left"
            },
            {
                "targets": [2,3,4],
                "className": "text-center"
            },
        ],
        "createdRow": function( row, data, dataIndex ) {
            if(data.is_active == 0)
            $(row).addClass( 'row_inactive' );
        },
        columns: [
            {
                data: 'id',
                name: 'action',  orderable: false, // searchable: true
                className: 'opacity1 text-center',
                render: function (data, type, row) {
                    var output = "";
                    // output += '<a class="cms_page_delete text-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    output += '<div class="d-inline-block dropdown">'
                        + '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                        + '<span class="btn-icon-wrapper pr-2 opacity-7">'
                        + '<i class="fa fa-cog fa-w-20"></i>'
                        + '</span>'
                        + '</button>'
                        + '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                        + '<ul class="nav flex-column">'
                        + '<li class="nav-item">'
                        + '<a class="nav-link active-inactive-link" data-id="'+(row.is_active).toString()+'">Mark as ' + ((row.is_active == 1) ? 'Inactive' : 'Active') + '</a>'
                        + '</li>'
                        + '<li class="nav-item">'
                        + '<a class="nav-link " href="' + origin + '/../edit/' + row.id + '" >Edit</a>'
                        + '</li>'
                        + '<li class="nav-item">'
                        + '<a class="nav-link cms_page_delete" >Delete</a>'
                        + '</li>'
                        + '</ul>'
                        + '</div>'
                        + '</div>';
                    return output;
                },
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'type',
                name: 'type',
                // className: 'text-center',
                order: 'asc',
                render: function (data, type, full, meta) {
                    if (data == 'cms'){ return 'Cms'; }
                    else if (data == 'genre') { return 'Genre'; }
                    else if (data == 'language') { return 'Language'; }
                    else if (data == 'category') { return 'Category'; }
                    else if (data == 'artist') { return 'Artist';}
                }
            },
            {
                data: 'sort_order',
                name: 'sort_order',
                className: 'text-center'
            },
            {
                data: 'is_active',
                name: 'is_active',
                visible:false,
                render: function (data, type, full, meta) {
                    var output = "";
                    if (full.is_active == 1) {
                        output += '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-toggle="button" aria-pressed="true" autocomplete="off">' +
                            '<div class="handle"></div>' +
                            '</button>'
                    } else {
                        output += '<button type="button" class="btn btn-sm btn-toggle  toggle-is-active-switch" data-toggle="button" aria-pressed="false" autocomplete="off">' +
                            '<div class="handle"></div>' +
                            '</button>'
                    }
                    return output;
                },
            },
           
        ],
    });
    $('#footer_listing thead th').each(function () {
        var title = $(this).text();
        $(this).html(title+' <input type="text" class="col-search-input" placeholder="Search ' + title + '" />');
    });
    
    table.columns().every(function () {
        var table = this;
        $('input', this.header()).on('keyup change', function () {
            if (table.search() !== this.value) {
                   table.search(this.value).draw();
            }
        });
    });
    /** Delete cms_page */
    $('#footer_listing').on('click', 'tbody .cms_page_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();
        var cms_page_id = data_row.id;
        var message = "Are you sure ?";
        console.log(message);
        $('#cmsPageDeleteModel').on('show.bs.modal', function (e) {
            $('#cms_page_id').val(cms_page_id);
            $('#message_delete').text(message);
        });
        $('#cmsPageDeleteModel').modal('show');
    })

    $(document).on('click', '#deleteCmsPage', function () {
        var cms_page_id = $('#cms_page_id').val();
        $.ajax({
            url: origin + '/../delete/' + cms_page_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                cms_page_id: cms_page_id,
            },
            success: function (response) {
                if (response.status == 'true') {
                    $('#cmsPageDeleteModel').modal('hide')
                    table.ajax.reload();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                } else {
                    $('#cmsPageDeleteModel').modal('hide')
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                }
                setTimeout(function () {
                    toastr.clear();
                }, 5000);
            }
        });
    })

    // $('#footer_listing').on('click', '.active-inactive-link', function () {
    //     var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
    //     toggleButton.trigger('click');

    // });
    /** toggle active switch and show confirmation */

        $('#footer_listing').on('click', '.active-inactive-link', function () {
        var is_active = $(this).attr("data-id");
        var data_row = table.row($(this).closest('tr')).data();
        var cms_page_id = data_row.id;
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        $('#cmsPageIsActiveModel').on('show.bs.modal', function (e) {
            $('#cms_page_id').val(cms_page_id);
            $('#is_active').val(is_active);
            $('#message').text(message);
        });
        $('#cmsPageIsActiveModel').modal('show');
    });

    /** Activate or deactivate cms_page */
    $(document).on('click', '#cmsPageIsActive', function () {
        var cms_page_id = $('#cms_page_id').val();
        var is_active = $('#is_active').val() == 1 ? 0 : 1;
        $.ajax({
            url: origin + '/../cmsPageActiveInactive',
            method: "POST",
            data: {
                "_token": $('#token').val(),
                "is_active": is_active,
                "cms_page_id": cms_page_id
            },
            success: function (response) {
                if (response.status == 'true') {
                    $('#cmsPageIsActiveModel').modal('hide')
                    table.ajax.reload();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                setTimeout(function () {
                    toastr.clear();
                }, 5000);
            }
        })
    });

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with _
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-+/g, '-')           // Replace - with single _
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    $('#name').bind('keyup', function () {
        var title = $(this).val();
        var slug = slugify(title);
        $('#slug').val(slug);
    });



});

$(document).on('change','input[name="type"]',function(){
    var value = $(this).val();

    getDataByType(value,submitMode);
});

function getDataByType(value,actionType='create'){
    $.ajax({
        type:'POST',
        url:(actionType=='create')?'updateData':'../updateData',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {type: value},
        dataType : 'json',
        success:function(result)
        {
            $('#dropdown').empty();
            $.each(result.data,function(key,value)
            {
                if(jQuery.inArray(value.id.toString(), result.selected) !== -1){
                    $("#dropdown").append('<option selected="selected" value="'+value.id+'">'+value.name+'</option>');
                }
                else
                {
                    $("#dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                }
            });
        }
    });
}




/** Add cms_page form validation */
$("#addCmsPageForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "slug": {
            required: true
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "slug": {
            required: "Please enter slug"
        },
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.parent().parent().parent());
        $(element.parent().parent()).css('margin-bottom', '0');
    },
    submitHandler: function (form) {
        // validate_count();
        form.submit();

    }

});


/** Update cms_page form validation */
$("#updateCmsPageForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "slug": {
            required: true
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "slug": {
            required: "Please enter slug"
        },
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.parent().parent().parent());
        $(element.parent().parent()).css('margin-bottom', '0');
    },
    submitHandler: function (form) {
        // validate_count();
        form.submit();
    }

});
