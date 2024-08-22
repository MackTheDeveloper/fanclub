    /** add  music cateogry form validation */
/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    $('.expand_collapse_filter').on('click', function() {
        $(".expand_filter").toggle();
    })

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .landing_interest_delete', function () {
        var landing_interest_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#landingInterestDeleteModel').on('show.bs.modal', function(e){
            $('#landing_interest_id').val(landing_interest_id);
            $('#message_delete').text(message);
        });
        $('#landingInterestDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteLandingInterest', function(){
        var landing_interest_id = $('#landing_interest_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + landing_interest_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                landing_interest_id: landing_interest_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#landingInterestDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#landingInterestDeleteModel').modal('hide')                  
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
        var how_it_works_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";        
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }                        
        $('#howItWorksIsActiveModel').on('show.bs.modal', function(e){
            $('#how_it_works_id').val(how_it_works_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#howItWorksIsActiveModel').modal('show');                                         
    });    

    
    /** Activate or deactivate music cateogry */
    $(document).on('click','#howItWorksIsActive', function(){ 
        var how_it_works_id = $('#how_it_works_id').val();
        var status = $('#status').val();                          
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "how_it_works_id": how_it_works_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#howItWorksIsActiveModel').modal('hide')
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

// $(document).on('click','#search_type', function(){
//     var type = $('#type').val();
//     // $('#exportArtist #type').val(type);
//     DatatableInitiate(type);
// });

$(document).on('click','#search_landing_interest', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var type = $('#type').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    $('#exportUserInterested #startDate').val(fromDate);
    $('#exportUserInterested #endDate').val(toDate);
    $('#exportUserInterested #role').val(type);
    DatatableInitiate(type,fromDate,toDate);
});

$('#Tdatatable').on('search.dt', function() {
    var value = $('.dataTables_filter input').val();
    $('#exportArtist #search').val(value);
});

function DatatableInitiate(type='',startDate='',endDate='') {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Name, Email, Role..."
            },
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            /* 'stateSave': true,
            stateSaveParams: function (settings, data) {
                delete data.order;
            }, */
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
                    targets: [2,3],
                    className: "text-left",
                },
                {
                    targets: [1,4],
                    className: "text-center",
                },
                {
                    targets: [2,3,4],
                    "orderable": true
                },
                {
                    targets: [5],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[4, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: {type:type,startDate:startDate,endDate:endDate},
                error: function () {  // error handling
                    $(".Tdatatable-error").html("");
                    $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
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