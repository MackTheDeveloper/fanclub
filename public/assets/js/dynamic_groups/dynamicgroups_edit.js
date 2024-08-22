/** add  music cateogry form validation */
$("#addGroupForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "type": {
            required: true,
        },
        "slug": {
            required: true,
        },
        "image_shape": {
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
        "slug": {
            required: "Please enter slug"
        },
        "image_shape": {
            required: "Please select shape",
        },
    },
    errorPlacement: function (error, element) {
        if (element.is(":radio")) {
            error.appendTo(element.parent().parent());
        }
        else { // This is the default behavior of the script
            error.insertAfter(element);
        }
    },
    submitHandler: function (form) {
        form.submit();
    }
});


/** music cateogrys listing */
$(document).ready(function () {
    var origin = window.location.href;
    var serachType = $('#serachType').val();
    var groupId = $('input[name="group_id"]').val();
    DatatableInitiate(groupId, serachType);
    // filter_song
    $(document).on('click', '#btnFilterData', function () {
        var serachType = $('#serachType').val();
        var startDate = $('#daterange').data('daterangepicker').startDate;
        var endDate = $('#daterange').data('daterangepicker').endDate;
        startDate = startDate.format('YYYY-MM-DD');
        endDate = endDate.format('YYYY-MM-DD');
        var criteria = $('#criteria').val();
        var likeMin = $('#likeMin').val();
        var likeMax = $('#likeMax').val();
        var viewMin = $('#viewMin').val();
        var viewMax = $('#viewMax').val();
        var downloadMin = '';
        var downloadMax = '';
        if ($('#downloadMin').length > 0) {
            var downloadMin = $('#downloadMin').val();
        }
        if ($('#downloadMax').length > 0) {
            var downloadMax = $('#downloadMax').val();
        }
        DatatableInitiate(
            groupId,
            serachType,
            startDate,
            endDate,
            criteria,
            likeMin,
            likeMax,
            viewMin,
            viewMax,
            downloadMin,
            downloadMax
        );
    });
})
function DatatableInitiate(
    groupId,
    serachType = "",
    startDate = "",
    endDate = "",
    criteria = "",
    likeMin = "",
    likeMax = "",
    viewMin = "",
    viewMax = "",
    downloadMin = "",
    downloadMax = ""
) {
    var token = $('input[name="_token"]').val();
    if (serachType == "1") {
        var colunnData = [
            {
                targets: [0, 2],
                className: "text-left",
            },
            {
                targets: [1, 3, 4, 5],
                className: "text-center",
            },
            {
                targets: [0, 1],
                orderable: false,
            },
            {
                targets: [2, 3, 4],
                orderable: true,
            },
        ];
    } else if (serachType == "2") {
        var colunnData = [
            {
                targets: [0, 2],
                className: "text-left",
            },
            {
                targets: [1, 3, 4, 5, 6],
                className: "text-center",
            },
            {
                targets: [0, 1],
                orderable: false,
            },
            {
                targets: [2, 3, 4, 6],
                orderable: true,
            },
        ];
    } else {
        var colunnData = [
            {
                targets: [0, 2],
                className: "text-left",
            },
            {
                targets: [1],
                className: "text-center",
            },
            {
                targets: [0, 1],
                orderable: false,
            },
            {
                targets: [2],
                orderable: true,
            },
        ];
    }
    var table = $("#GroupDataList").DataTable({
        language: {
            searchPlaceholder: "Search by Name...",
        },
        bDestroy: true,
        processing: true,
        serverSide: true,
        columnDefs: colunnData,
        order: [[2, "desc"]],
        scrollX: true,
        ajax: {
            url: baseUrl + "/securefcbcontrol/dynamic-groups/groupdatalist", // json datasource
            data: {
                _token: token,
                criteria: criteria,
                startDate: startDate,
                endDate: endDate,
                likeMin: likeMin,
                likeMax: likeMax,
                viewMin: viewMin,
                viewMax: viewMax,
                downloadMin: downloadMin,
                downloadMax: downloadMax,
                serachType: serachType,
                groupId: groupId,
            },
            error: function () {
                // error handling
                $(".GroupDataList-error").html("");
                $("#GroupDataList").append(
                    '<tbody class="GroupDataList-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                );
                $("#GroupDataList_processing").css("display", "none");
            },
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
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust().responsive.recalc();
});

// Select all checkbox at once
$('#selectAll').on('click', function () {
    var checked = $(this).is(':checked'); // Checkbox state

    // Select all
    if (checked) {
        $("input[type=checkbox]").prop('checked', true);
    } else {
        $("input[type=checkbox]").prop('checked', false);
    }
    // var rows = table.rows({ 'search': 'applied' }).nodes();
    // $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// if any checkbox is unchecked
$('#GroupDataList tbody').on('change', 'input[type="checkbox"]', function () {
    console.log(this)
    if (!this.checked) {
        var el = $('#selectAll').get(0);
        if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
        }
    }
});
// To add bulk items
$(document).on('click', '#btnAddData', function () {
    //var selectedVal = $('#bulkActionDropdown :selected').val();
    var url;
    var checkedValues = [];
    url = baseUrl + '/securefcbcontrol/dynamic-groups/addbulkitems';
    $('#GroupDataList').find('input[type="checkbox"]:checked').each(function () {
        if (this.checked) {
            checkedValues.push(this.value)
        }
    });
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
            "checkedValues": checkedValues,
            "grpId": grpId,
            "grpType": grpType,
        },
        success: function (response) {
            if (response.status == 'true') {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.success(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
            else {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.error(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    });
});
// To remove bulk items
$(document).on('click', '#btnRemoveData', function () {
    //var selectedVal = $('#bulkActionDropdown :selected').val();
    var url;
    var checkedValues = [];
    url = baseUrl + '/securefcbcontrol/dynamic-groups/removebulkitems';
    $('#GroupDataList').find('input[type="checkbox"]:checked').each(function () {
        var deleteId = $(this).attr("data-delete");
        if (deleteId) {
            checkedValues.push(deleteId);
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (checkedValues.length) {
        $.ajax({
            url: url,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                "checkedValues": checkedValues,
                "grpId": grpId,
                "grpType": grpType,
            },
            success: function (response) {
                if (response.status == 'true') {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
                else {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            }
        });
    } else {
        var totalChecked = $('#GroupDataList input[type="checkbox"]:checked').length
        var msg = totalChecked ? "Your selected item is not already added in list" : "Please select atleast one item to delete";
        toastr.clear();
        toastr.options.closeButton = true;
        toastr.options.timeOut = 0;
        toastr.error(msg);
        setTimeout(function () {
            //    location.reload();
        }, 2000);
    }
});
// To add items
$(document).on('click', '.item_add', function () {
    //var selectedVal = $('#bulkActionDropdown :selected').val();
    var url;
    var itemID = $(this).data('id');;
    url = baseUrl + '/securefcbcontrol/dynamic-groups/additems';
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
            "itemID": itemID,
            "grpId": grpId,
            "grpType": grpType,
        },
        success: function (response) {
            if (response.status == 'true') {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.success(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
            else {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.error(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    });
});
// To remove items
$(document).on('click', '.item_delete', function () {
    //var selectedVal = $('#bulkActionDropdown :selected').val();
    var url;
    var itemID = $(this).data('id');;
    url = baseUrl + '/securefcbcontrol/dynamic-groups/removeitems';
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
            "itemID": itemID,
            "grpId": grpId,
            "grpType": grpType,
        },
        success: function (response) {
            if (response.status == 'true') {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.success(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
            else {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.options.timeOut = 0;
                toastr.error(response.msg);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    });
});
$('#resetFilter').click(function () {
    $('#criteria').val(false).trigger("change");
    $('#GroupItemSearch')[0].reset();
    var origin = window.location.href;
    var serachType = $('#serachType').val();
    DatatableInitiate(serachType);
});

$(document).on('change', 'input[name="type"]', function () {
    var val = $(this).val();
    $('.hideShowConfigure').addClass('d-none');
    $("input[name=view_all][value='1']").prop("checked",true);
    if (jQuery.inArray(val, ['1', '2']) !== -1) {
        $('.hideShowViewAll').removeClass('d-none');
        if (val == 1) {
            $('.type-for-songs').addClass('d-none');
            $('.type-for-artist').removeClass('d-none');
        } else {
            $('.type-for-artist').addClass('d-none');
            $('.type-for-songs').removeClass('d-none');
        }
    } else {
        $('.hideShowViewAll').addClass('d-none');
    }
})

$(document).on('change', 'input[name="view_all"]', function () {
    var val = $(this).val()
    if (jQuery.inArray(val, ['2', '3', '4', '5']) !== -1) {
        $('.hideShowConfigure').removeClass('d-none');
    } else {
        $('.hideShowConfigure').addClass('d-none');
    }
})
