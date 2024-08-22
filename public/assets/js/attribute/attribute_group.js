/** Ajax - datatable for package listing */

$(document).ready(function () {
    var origin = window.location.href;
    var table = $('#attribute_group_listing').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": origin,
            "type": "GET"
        },
        'columnDefs': [{
                "targets": [0,4,5],
                "className": "text-center",
            },
               {
                   "targets": [1],
                   "visible": false
               }
        ],
        columns: [{
                data: 'rownum',
                name: 'rownum'
            },
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'display_name',
                name: 'display_name'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'sort_order',
                name: 'sort_order'
            },
            { data: 'zone', name: 'zone',
                render: function (data,type,row) {                    
                    if(row.user_zone != null)
                    {
                        var z = row.user_zone;
                        return moment.utc(row.ag_created_at).utcOffset(z.replace(':', "")).format('YYYY-MM-DD HH:mm:ss')
                    }
                    else
                    {
                        return "-----"
                    }
                    
                }
            },
            {
                data: 'id',
                name: 'action', // orderable: true, // searchable: true
                render: function (data, type, row) {
                    // console.log(row.id);
                    var output = "";
                    output += '<a href="' + origin + '/../edit/' + row.id + '"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp; &nbsp;'
                    output += '<a class="attr_group_delete text-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                },
            },
        ]
    });

    /** Delete attribute group */
    $('#attribute_group_listing').on('click', 'tbody .attr_group_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();  
        var attGroup_id = data_row.id;  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#attGroupDeleteModel').on('show.bs.modal', function(e){
            $('#attGroup_id').val(attGroup_id);
            $('#message_delete').text(message);
        });
        $('#attGroupDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteAttGroup', function(){
        var attGroup_id = $('#attGroup_id').val(); 
        $.ajax({
            url: origin + '/../' + attGroup_id + '/deleteAttributeGroup',
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                attGroup_id: attGroup_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#attGroupDeleteModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#attGroupDeleteModel').modal('hide')                  
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
    
    // export attribute groups
    $('body').on('click', '#exportAttributeGroups', function()  {
        $.ajax({
            type: "get",
            url: origin + '/../exportAttributeGroup',
            success: function (response) {
                if (response === "attributeGroups.csv") {
                    window.location.href = '../../attributeGroups.csv';
                }
            }
        });
    });
    

});

/* Add attribute Group - Validations*/

$("#addAttriGroupForm").validate({
    ignore: [],  // ignore NOTHING
    rules: {
            "display_name": {
                required: true,
            },
            "name": {
                required: true,
            },
            'sort_order': {
                required: true
            }
        },
        messages: {
            "display_name": {
                required: "Please enter display name"
            },
            "name": {
                required: "Please enter name",
            },
            'sort_order': {
                required: "Please enter sort order"
            }
        },
    });

/* Update attribute Group - Validations*/

$("#updateAttriGroupForm").validate({
    ignore: [],  // ignore NOTHING
    rules: {
            "display_name": {
                required: true,
            },
            "name": {
                required: true,
            },
            'sort_order': {
                required: true
            }
        },
        messages: {
            "display_name": {
                required: "Please enter display name"
            },
            "name": {
                required: "Please enter name",
            },
            'sort_order': {
                required: "Please enter sort order"
            }
        },
    });
