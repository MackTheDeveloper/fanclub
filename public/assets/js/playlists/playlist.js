/** add  music cateogry form validation */
$("#addPlaylistForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "dynamic_group_id": {
            required: true,
        },
        "sort_order": {
            required: true,
        },
        "status": {
            required: true,
            number: true
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "dynamic_group_id": {
            required: "Please select dynamic group"
        },
        "sort_order": {
            required: "Please enter sort order"
        },
        "status": {
            required: "Please select status"
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


/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .delete', function () {
        var playlist_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#DeleteModel').on('show.bs.modal', function(e){
            $('#playlist_id').val(playlist_id);
            $('#message_delete').text(message);
        });
        $('#DeleteModel').modal('show');
    })

    $(document).on('click','#delete', function(){
        var playlist_id = $('#playlist_id').val();
        $.ajax({
            url: origin + '/../delete/' + playlist_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                playlist_id: playlist_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#DeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#DeleteModel').modal('hide')
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
        var playlist_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#IsActiveModel').on('show.bs.modal', function(e){
            $('#playlist_id').val(playlist_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#IsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#IsActive', function(){
        var playlist_id = $('#playlist_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "playlist_id": playlist_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#IsActiveModel').modal('hide')
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

    $('#Tdatatable').on('click', 'tbody .toggle-is-approve-switch', function () {
        var approve = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var playlist_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#IsApproveModel').on('show.bs.modal', function(e){
            $('#playlist_id').val(playlist_id);
            $('#approve').val(approve);
            $('#messageApprove').text(message);
        });
        $('#IsApproveModel').modal('show');
    });
});
$(document).on('click','#search_group', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var status = $('#statusFilter').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    // console.log(startDate);
    DatatableInitiate(status,fromDate,toDate);
});


function DatatableInitiate(status='',startDate='',endDate='') {
    var token = $('input[name="_token"]').val();
    var table = $('#Tdatatable').DataTable({
        language: {
            searchPlaceholder: "Search by Name..."
        },
        "bDestroy": true,
        "processing": true,
        "serverSide": true,
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass( data[0] );
        },
        "columnDefs": [
            // {
            //     targets : [-1],
            //     "orderable": false
            // },
            {
                targets: [0,4],
                className: "hide_column"
            },
            {
                targets: [1],
                className: "opacity1 text-center"
            },
            {
                targets: [1,3,4,5],
                className: "text-center"
            },
            {
                targets: [1,4],
                "orderable": false
            },
            {
                targets: [3,4,5],
                "orderable": true
            },
            {
                targets: [2],
                className: "text-left",
                "orderable": true
            },
            {
                targets: [5],
                className: "text-center", orderable: false, searchable: false
            }
        ],
        "order": [[2, "asc"]],
        "scrollX": true,
        "ajax": {
            url:  'list', // json datasource
            data:{
                _token : token,
                status : status,
                startDate:startDate,
                endDate:endDate,
            },
            error: function () {  // error handling
                $(".Tdatatable-error").html("");
                $("#Tdatatable").append('<tbody class="GroupDataList-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
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
$(document).on('change','#dynamic_group_id', function(){
  //var selectedVal = $('#bulkActionDropdown :selected').val();
  var url;
  var grpId = $(this).val();
  url = baseUrl + '/securefcbcontrol/dynamic-groups/getGroupDetails';
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $.ajax({
      url: url,
      method: "POST",
      data: {
          "_token": $('#token').val(),
          "grpId":grpId,
      },
      success: function(response)
      {
          if(response)
          {
            $(".dynamicGroupsLinkPlaylists").html(response);
            $(".dynamicGroupsLinkPlaylists").show();
          }
      }
  });
});
