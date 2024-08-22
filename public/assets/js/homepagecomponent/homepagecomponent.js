/** add  music cateogry form validation */
$("#addHomePageComponentForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "type": {
            required: true,
        },
        "sort_order": {
            required: true,
        },
        "status": {
            required: true,
        },

    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "type": {
            required: "Please select type"
        },
        "sort_order": {
            required: "Please enter sort order"
        },
        "status": {
            required: "Please select status"
        },
    },
    errorPlacement: function(error, element) {
        if ( element.is(":radio") ) {
            error.prependTo( element.parent().parent() );
        }
        else { // This is the default behavior of the script
            error.insertAfter( element );
        }
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
        var comp_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#DeleteModel').on('show.bs.modal', function(e){
            $('#comp_id').val(comp_id);
            $('#message_delete').text(message);
        });
        $('#DeleteModel').modal('show');
    })

    $(document).on('click','#delete', function(){
        var comp_id = $('#comp_id').val();
        $.ajax({
            url: origin + '/../delete/' + comp_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                comp_id: comp_id,
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
        // var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        // toggleButton.trigger('click');
        var status = $(this).data('status');
        var comp_id = $(this).data('id');
        var message = "Are you sure ?";
        $('#IsActiveModel').on('show.bs.modal', function(e){
            $('#comp_id').val(comp_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#IsActiveModel').modal('show');
    });
    /** toggle active switch and show confirmation */
    // $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
    //     var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
    //     var comp_id = $(this).data('id');
    //     var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
    //     if($(this).attr('aria-pressed') == 'false')
    //     {
    //         $(this).addClass('active');
    //     }
    //     if($(this).attr('aria-pressed') == 'true')
    //     {
    //         $(this).removeClass('active');
    //     }
    //     $('#IsActiveModel').on('show.bs.modal', function(e){
    //         $('#comp_id').val(comp_id);
    //         $('#status').val(status);
    //         $('#message').text(message);
    //     });
    //     $('#IsActiveModel').modal('show');
    // });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#IsActive', function(){
        var comp_id = $('#comp_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "comp_id": comp_id
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
        var comp_id = $(this).data('id');
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
            $('#comp_id').val(comp_id);
            $('#approve').val(approve);
            $('#messageApprove').text(message);
        });
        $('#IsApproveModel').modal('show');
    });
});
$(document).on('click','#search_homepage_comp', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var status = $('#statusFilter').val();
    var type = $('#type').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    // console.log(startDate);
    DatatableInitiate(status,fromDate,toDate,type);
});


function DatatableInitiate(status='',startDate='',endDate='',type='') {
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
                targets: [0],
                className: "hide_column"
            },
            {
                targets: [1],
                className: "text-center opacity1",
            },
            {
                targets: [2],
                className: "text-left"
            },
            {
                targets: [1,3,4,5],
                className: "text-center"
            },
            {
                targets: [1],
                "orderable": false
            },
            {
                targets: [2,3,4,5],
                "orderable": true
            },
            // {
            //     targets: [3],
            //     className: "text-center",
            //     "orderable": true
            // },
            {
                targets: [5],
                className: "text-center", orderable: false, searchable: false
            }
        ],
        "order": [[4, "desc"]],
        "scrollX": true,
        "ajax": {
            url:  'list', // json datasource
            data:{
                _token : token,
                status : status,
                startDate:startDate,
                endDate:endDate,
                type:type,
            },
            error: function () {  // error handling
                $(".Tdatatable-error").html("");
                $("#Tdatatable").append('<tbody class="TdatatableDataList-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
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
$(document).ready(function(){
  $(".componentText").hide();
  $(".bannerImage").hide();
  $(".dynamicGroups").hide();
  $(".dynamicGroupsLink").hide();
  $(".urlCategory").hide();
  if(type=='1')
  $(".componentText").show();
  if(type=='2'){
  $(".bannerImage").show();
    if(url_type!=0 || url_type!=''){
      $("#banner_url_type"+url_type).attr('checked', true).trigger('click');
      $(".urlCategory").show();
    }
  }
  if(type=='3')
  $(".dynamicGroups").show();
    $("input[name='type']").click(function() {
      $(".componentText").hide();
      $(".bannerImage").hide();
      $(".dynamicGroups").hide();
        var val = $(this).val();
        if(val=='1')
        $(".componentText").show();
        if(val=='2')
        $(".bannerImage").show();
        if(val=='3')
        $(".dynamicGroups").show();
    });
});
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
            $(".dynamicGroupsLink").html(response);
            $(".dynamicGroupsLink").show();
          }
      }
  });
});
$("input[name='banner_url_type']").click(function() {
  //var selectedVal = $('#bulkActionDropdown :selected').val();
  var url;
  var urlTypeId = $(this).val();
  url = baseUrl + '/securefcbcontrol/dynamic-groups/getUtlTypeDetails';
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
          "urlTypeId":urlTypeId,
      },
      success: function(response)
      {
          if(response)
          {
            $("#banner_url_type_id").html('<option value="">Please select...</option>');
            var obj = JSON.parse(response);
            $(".urlCategory").show();
            $.each(obj, function( key, value ) {
              var selected='';
              if(value.id==url_type_id)
              selected='selected';
              $("#banner_url_type_id").append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
            });
          }
          else{
            $(".urlCategory").hide();
          }
      }
  });
});
