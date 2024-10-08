$(document).ready(function() {    
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[0];
    
    var table = $('#currency_list').DataTable({
        processing: true,
        serverSide: true,
        order: [[ 4, "desc" ]],
        ajax: {
            "url": window.location.href,
            "type": "GET"
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'currency_code', name: 'currency_code'},
            {data: 'currency_symbol', name: 'currency_symbol'},
            { data: 'zone', name: 'zone',
                render: function (data,type,row) {                    
                    if(row.user_zone != null)
                    {
                        var z = row.user_zone                        
                        return moment.utc(row.curr_created_at).utcOffset(z.replace('.', "")).format('YYYY-MM-DD HH:mm:ss')
                    }
                    else
                    {
                        return "-----"
                    }
                    
                }
            },
            {data: 'is_default', name: 'is_default',
                render: function (data, type, full, meta)
                {                        
                    var output = "";             
                    if(data == 1)
                    {                                                             
                        output += '<div class="row">'
                        +'<div class="col-sm-5">'
                        +'<button type="button" disabled class="btn btn-sm btn-toggle active toggle-is-active-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
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
            },
            {data: 'id', name: 'id', 
                render: function(data, type, row)
                {                    
                    var output = "";
                    output += '<a href="'+window.location.href+'/../edit/'+row.id+'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    output += '<a href="javascript:void(0);" class="currency_delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                }
            },                                 
        ],
    });
    
    $('#currency_list').on('click', 'tbody .currency_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();  
        var currency_id = data_row.id;  
        var message = "Are you sure ?";         
        $('#currencyDeleteModel').on('show.bs.modal', function(e){
            $('#currency_id').val(currency_id);
            $('#message').text(message);
        });
        $('#currencyDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteCurrency', function(){
        var currency_id = $('#currency_id').val(); 
        $.ajax({
            url: window.location.href + '/../delete',
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                currency_id: currency_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#currencyDeleteModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#currencyDeleteModel').modal('hide')
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

    $('#currency_list').on('click', 'tbody .toggle-is-active-switch', function () { 
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }  
        var data_row = table.row($(this).closest('tr')).data();                              
        var is_default = data_row.is_default;
        var curr_id = data_row.id;                
        var message = "Are you sure?";                                       
        $('#curruncyDefaultModel').on('show.bs.modal', function(e){
            $('#curr_id').val(curr_id);
            $('#is_dflt').val(is_default);
            $('#default_message').text(message);
        });
        $('#curruncyDefaultModel').modal('show');
    }) 

    $(document).on('click','#currencyIsDefault', function(){
        var curr_id = $('#curr_id').val();
        var is_default = $('#is_dflt').val();
        $.ajax({
            url: window.location.href + '/../default',
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                curr_id: curr_id,
                is_default: is_default,
            },            
            success: function(response)
            {       
                // console.log(response);         
                if(response.status == 'true')
                {
                    $('#curruncyDefaultModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#curruncyDefaultModel').modal('hide')
                    // table.ajax.reload();                    
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
});