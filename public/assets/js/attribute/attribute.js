/** Ajax - datatable for package listing */

$(document).ready(function () {
    var origin = window.location.href;
    var table = $('#attribute_listing').DataTable({
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
                data: 'internal_name',
                name: 'internal_name'
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
                        return moment.utc(row.a_created_at).utcOffset(z.replace(':', "")).format('YYYY-MM-DD HH:mm:ss')
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
                    var output = "";
                    output += '<a href="' + origin + '/../edit/' + row.id + '"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp; &nbsp;'
                    output += '<a class="attr_delete text-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                },
            },
        ]
    });

    /** Delete attribute */
    $('#attribute_listing').on('click', 'tbody .attr_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();  
        var attribute_id = data_row.id;  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#attributeDeleteModel').on('show.bs.modal', function(e){
            $('#attribute_id').val(attribute_id);
            $('#message_delete').text(message);
        });
        $('#attributeDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteAttribute', function(){
        var attribute_id = $('#attribute_id').val(); 
        $.ajax({
            url: origin + '/../' + attribute_id + '/deleteAttribute',
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                attribute_id: attribute_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#attributeDeleteModel').modal('hide')
                    table.ajax.reload();                    
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#attributeDeleteModel').modal('hide')                  
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


    $('body').on('click', '#exportAttributes', function()  {
        $.ajax({
            type: "get",
            url: origin + '/../exportAttribute',
            success: function (response) {
                if (response === "attributes.csv") {
                    window.location.href = '../../attributes.csv';
                }
            }
        });
    });

});
