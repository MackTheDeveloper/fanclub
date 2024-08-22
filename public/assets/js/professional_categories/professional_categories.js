/** add  blog cateogry form validation */
$("#addProfessionalCategoryForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
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
        messages:{required:'Please write blog cateogry body'}
    });
}); */



/** blog cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete blog cateogry */
    $('#Tdatatable').on('click', 'tbody .professional_categories_delete', function () {
        var professional_categories_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#professionalCategoriesDeleteModel').on('show.bs.modal', function(e){
            $('#professional_categories_id').val(professional_categories_id);
            $('#message_delete').text(message);
        });
        $('#professionalCategoriesDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteProfessionalCategories', function(){
        var professional_categories_id = $('#professional_categories_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + professional_categories_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                professional_categories_id: professional_categories_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#professionalCategoriesDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#professionalCategoriesDeleteModel').modal('hide')                  
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
        var professional_categories_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";        
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }                        
        $('#professionalCategoriesIsActiveModel').on('show.bs.modal', function(e){
            $('#professional_categories_id').val(professional_categories_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#professionalCategoriesIsActiveModel').modal('show');                                         
    });    

    
    /** Activate or deactivate blog cateogry */
    $(document).on('click','#professionalCategoriesIsActive', function(){ 
        var professional_categories_id = $('#professional_categories_id').val();
        var status = $('#status').val();                          
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "professional_categories_id": professional_categories_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#professionalCategoriesIsActiveModel').modal('hide')
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
                targets: [2],
                className: "text-left"
            },
            {
                targets: [1,3,4],
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