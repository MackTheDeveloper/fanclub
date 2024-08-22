/** add  blog cateogry form validation */
$("#addProductForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "title": {
            required: true,
        },
        "category_id[]": {
            required: true,
        },
        "main_image": {
            required: (checkRecord == 1) ? true : false,
            extension: "jpg,jpeg,png"
        },
        "user_id": {
            required: true,
        },
        "price": {
            required: true,
            number : true,
        },
        "description": {
            //required: true,
        },
    },
    messages: {
        "title": {
            required: "Please enter title"
        },
        "category_id": {
            required: "Please select category"
        },
        "main_image": {
            required: "Please select image"
        },
        "user_id": {
            required: "Please select professional"
        },
        "price": {
            required: "Please enter price"
        },
        "description": {
            //required: "Please enter description"
        },
    },
    errorPlacement: function (error, element) {
        //error.insertAfter(element)
        error.appendTo(element.parent().parent().parent());
        //$(element.parent().parent()).css('margin-bottom', '0');
    },
    submitHandler: function (form) {
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
$(document).ready(function () {
    $('#daterange').daterangepicker({
        startDate: moment().startOf('month'),
        autoApply : true,
        /* locale: {
            format: 'DD-MM-Y'
        } */
        //endDate: moment().startOf('month').add(16, 'day'),
    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    var origin = window.location.href;
    startDate = $('#daterange').data('daterangepicker').startDate;
    endDate = $('#daterange').data('daterangepicker').endDate;
    fromDate = startDate.format('DD-MM-YYYY');
    toDate = endDate.format('DD-MM-YYYY');
    DatatableInitiate('', '','','','');

    /** delete blog cateogry */
    $('#Tdatatable').on('click', 'tbody .review_delete', function () {
        var products_id = $(this).data('id');
        var message = "Are you sure to delete this record?";
        console.log(message);
        $('#productsDeleteModel').on('show.bs.modal', function (e) {
            $('#products_id').val(products_id);
            $('#message_delete').text(message);
        });
        $('#productsDeleteModel').modal('show');
    })


    $('#Tdatatable').on('click', 'tbody .review_action', function () {
        var review_id = $(this).data('id');
        var action = $(this).data('action');
        var message = "Are you sure to "+action+" this record?";
        console.log(message);
        $('#reviewStatusModel').on('show.bs.modal', function (e) {
            $('#review_id').val(review_id);
            $('#review_action').val(action);
            $('#message_action').text(message);
        });
        $('#reviewStatusModel').modal('show');
    })

    $(document).on('click', '#deleteProducts', function () {
        var products_id = $('#products_id').val();
        $.ajax({
            url: origin + '/../delete/' + products_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                products_id: products_id,
            },
            success: function (response) {
                if (response.status == 'true') {
                    $('#productsDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else {
                    $('#productsDeleteModel').modal('hide')
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

    $('#Tdatatable').on('click', '.active-inactive-link', function () {
        // var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        // toggleButton.trigger('click');
        var status = $(this).attr('data-value');
        var products_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if ($(this).attr('aria-pressed') == 'false') {
            $(this).addClass('active');
        }
        if ($(this).attr('aria-pressed') == 'true') {
            $(this).removeClass('active');
        }
        $('#productsIsActiveModel').on('show.bs.modal', function (e) {
            $('#products_id').val(products_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#productsIsActiveModel').modal('show');
    });
    /** toggle active switch and show confirmation */
    $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
        var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var products_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if ($(this).attr('aria-pressed') == 'false') {
            $(this).addClass('active');
        }
        if ($(this).attr('aria-pressed') == 'true') {
            $(this).removeClass('active');
        }
        $('#productsIsActiveModel').on('show.bs.modal', function (e) {
            $('#products_id').val(products_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#productsIsActiveModel').modal('show');
    });

    /** Activate or deactivate blog cateogry */
    $(document).on('click', '#productsIsActive', function () {
        var products_id = $('#products_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data: {
                "_token": $('#token').val(),
                "status": status,
                "id": products_id
            },
            success: function (response) {
                if (response.status == 'true') {
                    $('#productsIsActiveModel').modal('hide')
                    DatatableInitiate();
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


    /** Activate or deactivate blog cateogry */
    $(document).on('click', '#actionReview', function () {
        var id = $('#review_id').val();
        var status = ($('#review_action').val()=='accept')?1:2;
        // alert(status)
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data: {
                "_token": $('#token').val(),
                "status": status,
                "id": id
            },
            success: function (response) {
                if (response.status == 'true') {
                    $('#reviewStatusModel').modal('hide')
                    DatatableInitiate();
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
})

$(document).on('click', '#filter_products', function () {
    startDate = $('#daterange').data('daterangepicker').startDate;
    endDate = $('#daterange').data('daterangepicker').endDate;
    fromDate = startDate.format('DD-MM-YYYY');
    toDate = endDate.format('DD-MM-YYYY');
    /* var fromDate = $('.from_date_filter').val();
    var toDate = $('.to_date_filter').val(); */
    var product_id = $('#product_id').val();
    var ratings = $('#ratings').val();
    var status = $('#status').val();
    DatatableInitiate(fromDate, toDate, product_id, ratings, status);
});

function starRatingShow(){
    $('.star-rating-show').rating({
        displayOnly: true, 
        showCaption: false, 
        size: 'sm',
        step: 0.5,
        filledStar: '<i class="fa fa-star fa-xs" aria-hidden="true"></i>',
        emptyStar: '<i class="fa fa-star fa-xs" aria-hidden="true"></i>',
    });
}

function DatatableInitiate(fromDate = '', toDate = '', product_id = '', ratings = '', status = '') {
    $('#Tdatatable').DataTable({
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
            targets: [3,6],
            className: "text-left"
        },
        {
            targets: [5,7,8,9],
            className: "text-center", orderable: false, searchable: false
        }],
        "order": [[8, "desc"]],
        "scrollX": true,
        // columns: [
        //     {data: 'firstname', name: 'firstname'},
        //     {data: 'lastname', name: 'lastname'},
        //     {data: 'email', name: 'email'},
        //     {data: 'phone', name: 'phone'},
        //     {data: 'company_name', name: 'company_name'},            
        //     {data: 'address', name: 'address', orderable: false},            
        //     {data: 'user_created_at', name: 'user_created_at'},
        //     {
        //         data: 'is_subscribed', name: 'is_subscribed', orderable: false, searchable: false,
        //         render: function (data, type, full, meta){
        //             var output = "";
        //             if(data == 1){
        //                 output += '<div class="row">'
        //                 +'<div class="col-sm-5">'
        //                 +'<button type="button" class="btn btn-sm btn-toggle active toggle-is-subscribed-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
        //                 +'<div class="handle"></div>'
        //                 +'</button>'
        //                 +'</div>'
        //             }else{
        //                 output += '<div class="row">'
        //                 +'<div class="col-sm-5">'
        //                 +'<button type="button" class="btn btn-sm btn-toggle toggle-is-subscribed-switch" data-toggle="button" aria-pressed="false" autocomplete="off">'
        //                 +'<div class="handle"></div>'
        //                 +'</button>'
        //                 +'</div>'
        //             }
        //             return output;
        //         },
        //     },
        // ],
        "ajax": {
            url: "list", // json datasource
            data: function (d) {
                d.fromDate = fromDate;
                d.toDate = toDate;
                d.product_id = product_id;
                d.ratings = ratings;
                // d.userId = userId;
                d.status = status;
            },
            error: function () {  // error handling
                $(".Tdatatable-error").html("");
                $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#Tdatatable_processing").css("display", "none");

            },
            complete: function(){
                starRatingShow();
            }
        },
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        }
    });
}

