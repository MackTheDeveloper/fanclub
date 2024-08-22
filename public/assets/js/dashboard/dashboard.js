$(document).ready(function () {
    var origin = window.location.href;
    $('#from_date').datepicker({
        "format": "mm/dd/yyyy",
        "autoclose": true,
        "orientation": "top",
        "endDate": "today"
    });

    $('#to_date').datepicker({
        "format": "mm/dd/yyyy",
        "autoclose": true,
        "orientation": "top",
        "endDate": "today"
    });

    $('.radioBtnDuration').click(function(){
        displayDailySalesGraph($(this).val());
    })

    displayDailySalesGraph('daily');
    displayReviewsGraphp();
    DatatableInitiate();
})

// var baseUrl = $('#baseUrl').val();
function displayDailySalesGraph(duration) {
    $('#monthly-sales-graph').css("text-align", "center").html('<img src="../public/images/wait.gif" />');
    $.ajax({
        url: 'dashboard/monthly-graph/'+duration,
        method: 'GET',
        success: function (response) {
            if (response.status) {
                var options777 = {
                    chart: {
                        height: 397,
                        type: 'line',
                        toolbar: {
                            show: false,
                        }
                    },
                    series: [{
                        name: 'Total Artists',
                        type: 'column',
                        data: response.total_artist
                    }, {
                        name: 'Total Fans',
                        type: 'column',
                        data: response.total_fans
                    }, {
                        name: 'Total Sales',
                        type: 'line',
                        data: response.total_sale
                    }],
                    stroke: {
                        width: [1, 1, 1]
                    },
                    legend: {
                        position: 'top',
                    },
                    labels: response.dates_array,
                    xaxis: {
                        //type: 'datetime',
                        type: 'category',
                        categories: response.dates_array,
                        labels: {
                            show: true,
                            rotate: -45,
                            rotateAlways: duration == 'daily' ? true : false,
                        }
                    },
                    yaxis: [
                        {
                            seriesName: 'Total Artists',
                            opposite: false,
                            title: {
                                text: 'Total Artists',
                                style: {
                                    color: '#298ffb',
                                  }
                            }
                        },
                        {
                            seriesName: 'Total Fans',
                            opposite: true,
                            title: {
                                text: 'Total Fans',
                                style: {
                                    color: '#00e396',
                                  }
                            }
                        },
                        {
                            seriesName: 'Total Sales',
                            opposite: true,
                            axisTicks: {
                              show: true,
                            },
                            axisBorder: {
                              show: true,
                              color: '#FEB019'
                            },
                            labels: {
                              style: {
                                colors: '#FEB019',
                              },
                            },
                            title: {
                              text: "Total Sales",
                              style: {
                                color: '#FEB019',
                              }
                            }
                          },
                    ]
                };

                var chart777 = new ApexCharts(
                    document.querySelector("#monthly-sales-graph"),
                    options777
                );

                setTimeout(function () {
                    $('#monthly-sales-graph').html('');
                    if (document.getElementById('monthly-sales-graph')) {
                        chart777.render();
                    }
                }, 1000);
            }
        }
    })
}

function displayReviewsGraphp() {
    $.ajax({
        url: 'dashboard/review-graph',
        method: 'GET',
        success: function (response) {
            if (response.status) {
                var options = {
                    series: [response.postive, response.neutral, response.negative],
                    labels: ['Positive', 'Neutral', 'Negative'],
                    colors: ['#00FF00', '#FFA500', '#FF0000'],
                    chart: {
                        type: 'donut',
                    },
                    plotOptions: {
                        pie: {
                            size: 120,
                            donut: {
                                size: '55%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '16px',
                                        fontFamily: 'Helvetica, Arial, sans-serif',
                                        fontWeight: 600,
                                        color: undefined,
                                        formatter: function (val) {
                                            return val
                                        }
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontFamily: 'Helvetica, Arial, sans-serif',
                                        fontWeight: 400,
                                        offsetY: 20,
                                        color: undefined,
                                        formatter: function (val) {
                                            return val
                                        }
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Total',
                                        fontSize: '16px',
                                        fontFamily: 'Helvetica, Arial, sans-serif',
                                        fontWeight: 600,
                                        color: '#373d3f',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 'bold',
                        },
                    },
                    fill: {
                        type: 'gradient',
                    },
                    legend: {
                        show: false,
                    },
                };

                var chart777 = new ApexCharts(
                    document.querySelector("#review-graph"),
                    options
                );

                setTimeout(function () {
                    if (document.getElementById('review-graph')) {
                        chart777.render();
                    }
                }, 1000);
            }
        }
    })
}

function DatatableInitiate(status = '', startDate = '', endDate = '') {
    var token = $('input[name="_token"]').val();
    table = $('#Tdatatable').DataTable({
        language: {
            searchPlaceholder: "Search by Name..."
        },
        "bDestroy": true,
        "processing": true,
        "serverSide": true,
        "columnDefs": [
            // {
            //     targets : [-1],
            //     "orderable": false
            // },
            // {
            //     targets: [0],
            //     className: "hide_column"
            // },
            {
                targets: [2, 3, 4, 5, 6],
                className: "text-left"
            },
            {
                targets: [0, 1, 7],
                className: "text-center"
            },
            {
                targets: [0, 3, 4, 5, 6],
                "orderable": false
            },
            {
                targets: [1, 2, 7],
                "orderable": true
            },
            // {
            //     targets: [3],
            //     className: "text-center",
            //     "orderable": true
            // },
            {
                targets: [0],
                className: "text-center", orderable: false, searchable: false
            }
        ],
        "order": [[6, "desc"]],
        "scrollX": true,
        "ajax": {
            url: "artists/dashboard-list", // json datasource
            data: {
                _token: token,
                is_active: status,
                startDate: startDate,
                endDate: endDate,
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

$(document).on('click', '#filter_dashboard_count', function () {
    $("#filterDashboardForm").valid();
    var token = $('input[name="_token"]').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if (Date.parse(from_date) > Date.parse(to_date)) {
        $('#from_date_error').html("<p>From date should be less then to To date</p>");
    }
    else {
        $('#from_date_error').html("");
    }
    $.ajax({
        url: "dashboard/dashboard-filter", // json datasource
        method: "POST",
        data: {
            _token: token,
            from_date: from_date,
            to_date: to_date,
        },
        success: function (response) {
            if (response.status == 'true') {
                $('#filter_artist').text(response.filter_artist);
                $('#filter_fans').text(response.filter_fans);
                $('#filter_subscription').text(response.filter_subscription);
                $('#filter_sales').text(response.filter_sales);
            }
        }
    });
})

$('#Tdatatable').on('click', '.approve-unaprove-link', function () {
    var approve = $(this).data('status');
    var artist_id = $(this).data('id');
    var message = "Are you sure ?";
    $('#artistIsApproveModel').on('show.bs.modal', function(e){
        $('#artistIsApproveModel #artist_id').val(artist_id);
        $('#approve').val(approve);
        $('#messageApprove').text(message);
    });
    $('#artistIsApproveModel').modal('show');
});

/** Activate or deactivate music cateogry */
$(document).on('click','#artistIsApprove', function(){
    var origin = window.location.href;
    var artist_id = $('#artist_id').val();
    var approve = $('#approve').val();
    $.ajax({
        url: origin + '/../artists/approve',
        method: "POST",
        data:{
            "_token": $('#token').val(),
            "approve": approve,
            "artist_id": artist_id
        },
        success: function(response)
        {
            if(response.status == 'true')
            {
                $('#artistIsApproveModel').modal('hide')
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
$('#Tdatatable').on('click', 'tbody .artist_pending_delete', function () {
    var artist_id = $(this).data('id');
    var message = "Are you sure ?";
    console.log(message);
    $('#artistDeleteModel').on('show.bs.modal', function(e){
        $('#artist_id').val(artist_id);
        $('#message_delete').text(message);
    });
    $('#artistDeleteModel').modal('show');
})

$(document).on('click','#deleteartist', function(){
    var artist_id = $('#artist_id').val();
    $.ajax({
        url:'dashboard/artist-delete',
        method: "POST",
        data: {
            "_token": $('#token').val(),
            artist_id: artist_id,
        },
        success: function(response)
        {
            if(response.status == 'true')
            {
                $('#artistDeleteModel').modal('hide')
                DatatableInitiate();
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.success(response.msg);
            }
            else
            {
                $('#artistDeleteModel').modal('hide')
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
