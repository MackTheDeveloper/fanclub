
/** add  template form validation */
$("#addEmailTemplateForm").validate({
    ignore: [], // ignore NOTHING
    debug: false,
    rules: {
        "title": {
            required: true,
        },
        "slug": {
            required: true,
        },
        "subject": {
            required: true,
        },
        // "body": {
        //     required: function(){
        //         CKEDITOR.instances.ckeditorBody.updateElement();
        //     },
        // },
    },
    messages: {
        "title": {
            required: "Please enter title"
        },
        "slug": {
            required: "Please enter slug"
        },
        "subject": {
            required: "Please enter subject"
        },
        "body": {
            required: "Email Body is required"
        },
    },
    errorPlacement: function (error, element) 
    {
        error.insertAfter(element)
    },
    submitHandler: function(form) 
    {      
        form.submit();
    }
});
 
/* $(".ckeditor").each(function(){
    $(this).rules("add", { 
        required:true,
        messages:{required:'Please write template body'}
    });
}); */

// $('.ckeditorBody').ckeditor();
// var editor = CKEDITOR.replace( 'ckeditorBody', {
//     filebrowserUploadUrl: "{{route('ckeditor.upload_cms_page_image', ['_token' => csrf_token() ])}}",
//     filebrowserUploadMethod: 'form'
//     allowedContent: true
// } );


/** templates listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete template */
    $('#Tdatatable').on('click', 'tbody .template_delete', function () {
        var template_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#templateDeleteModel').on('show.bs.modal', function(e){
            $('#template_id').val(template_id);
            $('#message_delete').text(message);
        });
        $('#templateDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteTemplate', function(){
        var template_id = $('#template_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + template_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                template_id: template_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#templateDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#templateDeleteModel').modal('hide')                  
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
        var is_active = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;         
        var template_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";        
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }                        
        $('#templateIsActiveModel').on('show.bs.modal', function(e){
            $('#template_id').val(template_id);
            $('#is_active').val(is_active);
            $('#message').text(message);
        });
        $('#templateIsActiveModel').modal('show');                                         
    });    

    
    /** Activate or deactivate template */
    $(document).on('click','#templateIsActive', function(){ 
        var template_id = $('#template_id').val();
        var is_active = $('#is_active').val();                          
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "is_active":is_active,
                "template_id":template_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#templateIsActiveModel').modal('hide')
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


function DatatableInitiate() {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Title, Slug, Subject..."
            },
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            "createdRow": function( row, data, dataIndex ) {
                $(row).addClass( data[0] );
            },
            /* 'stateSave': true,
            stateSaveParams: function (settings, data) {
                delete data.order;
            }, */
            "columnDefs": [{
                "targets": [-1],
                "orderable": false
            },
            {
                targets: [0,5],
                className: "hide_column"
            },
            {
                targets: [1],
                className: "opacity1 text-center",
                orderable: false
            },
            {
                targets: [1,2,3],
                className: "text-left"
            },
            {
                targets: [1],
                className: "text-center", orderable: false, searchable: false
            }],
            "order": [[0, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: { },
                error: function () {  // error handling
                    $(".Tdatatable-error").html("");
                    $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#Tdatatable_processing").css("display", "none");

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

$(document).on("click", ".addmore", function (e) {
    countCc = countCc + 1;
    
    if (countCc == 0) {
        countCc = 1;
    }
    e.preventDefault();
    var str = '<div class="col-md-12" id="add-' + countCc + '"><div class="col-md-7 row"><div class="col-md-10"><div class="form-group"><div><input class="form-control" name = "emailCc[email_cc][' + countCc + ']" type = "email" value = "" style = "" data-temp-mail- org="0" /></div></div></div><div class="col-md-2"><div class="form-group" style = "margin-top:15px" ><div><a href = "javascript:void(0)" class="btn btn-success btn-xs addmore" style="margin-right:5px">+</a><a style="" href="javascript:void(0)" class="btn btn-danger btn-xs removemore" id="' + countCc + '">-</a></div></div></div></div></div>';
    $('.rowForCc').append(str);
});

$(document).on("click", ".removemore", function (e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $("#add-" + id).remove();
});
