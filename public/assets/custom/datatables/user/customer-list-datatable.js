$(document).ready(function() {
    var origin = window.location.href;

    var table = $('#customer_list').DataTable({
        processing: true,
        serverSide: true,
        'scrollX': true,
        ajax: {
            "url": origin,
            "type": "GET"
        },
        columns: [
            {data: 'customer_unique_id', name: 'customer_unique_id'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'mobile', name: 'mobile'},
            {data: 'ip_address', name: 'ip_address'},
            {data: 'os_name', name: 'os_name'},
            {
                "data": null,
                "render": function(data, type, full, meta){
                   return full["browser_name"] + " " + full["browser_version"];
                }
            },
            {data: 'customer_created_at', name: 'customer_created_at',
                render: function (data,type,row) {                   
                    if(row.user_zone != null)
                    {
                        var z = row.user_zone;                        
                        return moment.utc(row.customer_created_at).utcOffset(z.replace(':', "")).format('YYYY-MM-DD HH:mm:ss')
                    }
                    else
                    {
                        return "-----"
                    }
                    
                }
            },
            {data: 'is_active', name: 'is_active',
                render: function (data)
                {                           
                    var output = "";             
                    if(data == 1)
                    {                                                             
                        output += '<div class="row">'
                        +'<div class="col-sm-5">'
                        +'<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'                    
                    }
                    else
                    {                         
                        output += '<div class="row">'
                        +'<div class="col-sm-5">'
                        +'<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-toggle="button" aria-pressed="false" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'                        
                    }                       
                    return output;
                },
                // className: 'text-center',
            },                         
            {data: 'is_verify', name: 'is_verify',
                render: function (data)
                {                           
                    var output = "";             
                    if(data == 1)
                    {                        
                        output += '<div class="mb-2 mr-2 badge badge-focus">Yes</div>';                    
                    }
                    else
                    {                        
                        output += '<div class="mb-2 mr-2 badge badge-focus">No</div>';                    
                    }                    
                    return output;
                },
                // className: 'text-center',
            },
            // {data: 'is_deleted', name: 'is_deleted',
            //     render: function (data, type, row)
            //     {                           
            //         var output = "";             
            //         if(data == 1)
            //         {                                                           
            //             output += '<div class="mb-2 mr-2 badge badge-focus">Yes</div>';                    
            //         }
            //         else
            //         {                                          
            //             output += '<div class="mb-2 mr-2 badge badge-focus">No</div>';                    
            //         }                    
            //         return output;
            //     },
            //     // className: 'text-center',
            // },                           
            {data: 'id', name: 'action', // orderable: true, // searchable: true
                render: function(data, type, row)
                {                    
                    var output = "";
                    output += '<a href="'+origin+'/../edit/'+row.id+'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    output += '<a href="javascript:void(0);" class="customer_delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                },
                // className: 'text-center',
            },            
        ],
        fnDrawCallback: function() {
            // $('.toggle-is-active').bootstrapToggle();
            $('.toggle-is-approve').bootstrapToggle();
            $('.toggle-is-deleted').bootstrapToggle();
        },
    });    
    
    // $('#customer_list').on('click', 'tbody .customer_delete', function () {
    //     var data_row = table.row($(this).closest('tr')).data();  
    //     var is_deleted = data_row.is_deleted;
    //     var customer_id = data_row.id;
    //     if(confirm("Are you sure you want to delete this customer ?"))
    //     {
    //         $.ajax({
    //             url: origin + "/../" + customer_id + '/delete',
    //             method: "GET",
    //             data:{is_deleted: is_deleted},
    //             success: function(response)
    //             {
    //                 if(response.status == 'true')
    //                 {
    //                     window.location.href = './list';
    //                 }                    
    //             }
    //         });
    //     }
    // })
    
    $('#customer_list').on('click', 'tbody .customer_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();          
        var is_deleted = data_row.is_deleted;
        var customer_id = data_row.id;                
        var message = "Are you sure?";                
        $('#deleteCustomerModel').on('show.bs.modal', function(e){
            $('#customer_id').val(customer_id);
            $('#is_deleted').val(is_deleted);
            $('#delete_message').text(message);
        });
        $('#deleteCustomerModel').modal('show');
    })

    $(document).on('click', '#customerDelete', function(){        
        var customer_id = $('#customer_id').val();
        var is_deleted = $('#is_deleted').val(); 
        $.ajax({
            url: origin + '/../' + customer_id + '/delete',
            method: "GET",
            data:{is_deleted: is_deleted},
            success: function(response)
            {                    
                if(response.status == 'true')
                {
                    $('#deleteCustomerModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success("Customer deleted successfully!");
                    // window.location.href = '/securefcbcontrol/user/list';
                }
                else
                {
                    $('#deleteCustomerModel').modal('hide')
                    // table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success("Something went wrong!");
                }                    
            }
        });
    })

    $('#customer_list').on('click', 'tbody .toggle-is-active-switch', function () {
        var is_active = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;        
        var data_row = table.row($(this).closest('tr')).data();                               
        var customer_id = data_row.id; 
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?": "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        } 
        $('#customerIsActiveModel').on('show.bs.modal', function(e){
            $('#customer_id').val(customer_id);
            $('#is_active').val(is_active);
            $('#message').text(message);
        });
        $('#customerIsActiveModel').modal('show');                         
    });      
    
    $(document).on('click','#customerIsActive', function(){ 
        var customer_id = $('#customer_id').val();
        var is_active = $('#is_active').val();                          
        $.ajax({
            url: origin + '/../activate-deactivate',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "is_active":is_active,
                "customer_id":customer_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#customerIsActiveModel').modal('hide')
                    table.ajax.reload();                    
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
});