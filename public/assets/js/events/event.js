/** add  event form validation */
$("#event_create_form").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "event_name": {
            required: true,
        },
        "event_desc": {
            required: true,
        },
        "event_image": {
            required: true,
            extension: "jpg,jpeg,png"
        }
    },
    messages: {
        "event_name": {
            required: "Please enter event name"
        },
        "event_desc": {
            required: "Please enter event description"
        },
        "event_image": {
            required: "Please select event image",
            extension: "You're only allowed to upload jpg or png images."
        }
    },
    errorPlacement: function (error, element) 
    {
        if (element.attr("name") == "event_desc") 
        {
            error.appendTo("#ck_error");
        }
        else 
        {
            error.insertAfter(element)
        }
    },
    submitHandler: function(form) 
    {
        if($('#event_desc').val() != '')
        {
            $('#ck_error').html('');
        }
        form.submit();
    }
});

/** Show selected image in html */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#selected_event_image').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

$("#event_image").change(function(){
    readURL(this);
});

/** update event form validation */
$('#event_edit_form').validate({
    ignore: [], // ignore NOTHING
    rules: {
        "event_name": {
            required: true,
        }
    },
    "event_desc": {
        required: true,
    },
    messages: {
        "event_name": {
            required: "Please enter event name"
        }
    },
    "event_desc": {
        required: "Please enter event description"
    },
    submitHandler: function(form) {
        form.submit();
    }
});


/** events listing */
$(document).ready(function(){
    var origin   = window.location.href;
    var table = $('#event_listing').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": origin,
            "type": "GET"
        },
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'id', name: 'id'},
            {data: 'event_name', name: 'event_name'},
            {data: 'event_desc', name: 'event_desc'},
            {data: 'event_image', name: 'event_image',
                "render": function (data, type, full, meta) 
                {
                    // origin = window.location.pathname.split( '/' )[1];
                    // console.log(window.location.host);
                    // console.log(window.location.origin);

                    var path = 'http://' + window.location.host + '/public/assets/images/events/';
                    return "<img src=\"" + path + data + "\" height=\"100\" width=\"100\"/>";
                },
            },
            { data: 'zone', name: 'zone',
                render: function (data,type,row) {                    
                    if(row.user_zone != null)
                    {
                        var z = row.user_zone;
                        return moment.utc(row.e_created_at).utcOffset(z.replace(':', "")).format('YYYY-MM-DD HH:mm:ss')
                    }
                    else
                    {
                        return "-----"
                    }
                    
                }
            },
            {data: 'is_active', name: 'is_active',
                render: function (data, type, full, meta)
                {                                              
                    var output = "";             
                    if(full.is_active == 1)
                    {                                                             
                        output += '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>' 
                    }
                    else
                    {                         
                        output += '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-toggle="button" aria-pressed="false" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                    }                       
                    return output;
                },
            },            
            {data: 'id', name: 'action', // orderable: true, // searchable: true
                render: function(data, type, row)
                {
                    var output = "";
                    output += '<a href="'+ origin +'/../editEvent/'+ row.id +'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    output += '<a class="event_delete text-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                },
            },
        ],
        'columnDefs': [
            {
                "targets": [0,4,5,6],
                "className": "text-center",
            },
            {
                "targets": [1],
                "visible": false
            },
        ],
    }); 

    /** toggle active switch and show confirmation */
    $('#event_listing').on('click', 'tbody .toggle-is-active-switch', function () {            
        var is_active = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;         
        var data_row = table.row($(this).closest('tr')).data();                             
        var event_id = data_row.id; 
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";        
        if($(this).attr('aria-pressed') == false)
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == true)
        {
            $(this).removeClass('active');
        }                        
        $('#eventIsActiveModel').on('show.bs.modal', function(e){
            $('#event_id').val(event_id);
            $('#is_active').val(is_active);
            $('#message').text(message);
        });
        $('#eventIsActiveModel').modal('show');                                             
    });    

    /** Activate or deactivate event */
    $(document).on('click','#eventIsActive', function(){ 
        var event_id = $('#event_id').val();
        var is_active = $('#is_active').val();                          
        $.ajax({
            url: origin + '/../activeDeactiveEvent',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "is_active":is_active,
                "event_id":event_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#eventIsActiveModel').modal('hide')
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

    /** delete event */
    $('#event_listing').on('click', 'tbody .event_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();  
        var event_id = data_row.id;  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#eventDeleteModel').on('show.bs.modal', function(e){
            $('#event_id').val(event_id);
            $('#message_delete').text(message);
        });
        $('#eventDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteevent', function(){
        var event_id = $('#event_id').val(); 
        $.ajax({
            url: origin + '/../' + event_id + '/deleteEvent',
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                event_id: event_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#eventDeleteModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#eventDeleteModel').modal('hide')                  
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


    /** Export Events */
    $('body').on('click', '#exportEvents', function() {
        $.ajax({
            type: "get",
            url: origin + '/../exportEvent',
            success: function (response) 
            {
                if (response === "events.csv") 
                {
                    window.location.href = '../../events.csv';
                }
            }
        });
    });
    
})
