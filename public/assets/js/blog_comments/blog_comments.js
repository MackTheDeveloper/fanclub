/** blog cateogrys listing */
$(document).ready(function(){
    $('#daterange').daterangepicker({
        startDate: moment().startOf('month'),
        autoApply: true,
        /* locale: {
            format: 'DD-MM-Y'
        } */
        //endDate: moment().startOf('month').add(16, 'day'),
    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    //$('.datepicker').datepicker();
    var origin = window.location.href;
    startDate = $('#daterange').data('daterangepicker').startDate;
    endDate = $('#daterange').data('daterangepicker').endDate;
    fromDate = startDate.format('DD-MM-YYYY');
    toDate = endDate.format('DD-MM-YYYY');
    DatatableInitiate('', '','','');

    /** delete blog cateogry */
    $('#Tdatatable').on('click', 'tbody .blog_comments_delete', function () {
        var blog_comments_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#blogCommentsDeleteModel').on('show.bs.modal', function(e){
            $('#blog_comments_id').val(blog_comments_id);
            $('#message_delete').text(message);
        });
        $('#blogCommentsDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteBlogComments', function(){
        var blog_comments_id = $('#blog_comments_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + blog_comments_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                blog_comments_id: blog_comments_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#blogCommentsDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#blogCommentsDeleteModel').modal('hide')                  
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
        var blog_comments_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";        
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }                        
        $('#blogCommentsIsActiveModel').on('show.bs.modal', function(e){
            $('#blog_comments_id').val(blog_comments_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#blogCommentsIsActiveModel').modal('show');                                         
    });    

    
    /** Activate or deactivate blog cateogry */
    $(document).on('click','#blogCommentsIsActive', function(){ 
        var blog_comments_id = $('#blog_comments_id').val();
        var status = $('#status').val();                          
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "blog_comments_id": blog_comments_id                  
            },
            success: function(response)
            {
                if(response.status == 'true')
                {                    
                    $('#blogCommentsIsActiveModel').modal('hide')
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

$(document).on('click', '#filter_blog_comments', function () {
    startDate = $('#daterange').data('daterangepicker').startDate;
    endDate = $('#daterange').data('daterangepicker').endDate;
    fromDate = startDate.format('DD-MM-YYYY');
    toDate = endDate.format('DD-MM-YYYY');
    /* var fromDate = $('.from_date_filter').val();
    var toDate = $('.to_date_filter').val(); */
    var blogId = $('#blog_id').val();
    var userId = $('#user_id').val();
    DatatableInitiate(fromDate, toDate, blogId, userId);
});

function DatatableInitiate(fromDate = '', toDate = '', blogId = '', userId = '') {
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
                targets: [1,3,4],
                className: "text-left"
            },
            {
                targets: [2,5,6],
                className: "text-center", searchable: false, orderable: false
            }],
            "order": [[0, "desc"]],
            "scrollX": true,
            "ajax": {
                url: urlList, // json datasource
                data: function (d) {
                    d.fromDate = fromDate;
                    d.toDate = toDate;
                    d.blogId = blogId;
                    d.userId = userId;
                },
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