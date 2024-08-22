
var startDate = " ";
var endDate = " ";
var name = " ";
var email = " ";
$(document).ready(function () {
	$('#divFilter').hide();
	ajaxCall.getContactUs(startDate, endDate, name, email);

	$('body').on('click', '.viewMessage', function () {
		var activity = "";
		if ($(this).attr('data-target') == "#contactUsReplyModal") {
			activity = "contactUsReplyModal";
		} else if ($(this).attr('data-target') == "#contactUsMessageModal") {
			activity = "contactUsMessageModal";
		}
		ajaxCall.getContactUsInquiryData($(this).attr('data'), activity);
	});

	$('body').on('click', '#reply', function () {

		var formData = { 'inquiryId': $('#inquiryId').val(), 'customerMessage': $('#customerMessage').val() ,'replyMessage':CKEDITOR.instances['replyMessage'].getData() };
		ajaxCall.postReply(formData);
	})

	// $('body').on('click', '.applyBtn', function() {
	// 	startDate = $('#daterange').data('daterangepicker').startDate;
	// 	endDate = $('#daterange').data('daterangepicker').endDate;

	// 	startDate = startDate.format('YYYY-MM-DD');
	// 	endDate = endDate.format('YYYY-MM-DD');

	// 	ajaxCall.getContactUs(startDate, endDate);
	// });

	$('body').on('click', '#divFilterToggle', function () {
		$("#divFilter").toggle();
	});

	$('body').on('click', '#resetDate', function () {
		$('#daterange').val('');
		$('#name').val('');
		$('#email').val('');
		ajaxCall.getContactUs(" ", " ", "", "");
	});
	$('body').on('click', '#searchButton', function () {
		if ($('#daterange').val()) {
			startDate = $('#daterange').data('daterangepicker').startDate;
			endDate = $('#daterange').data('daterangepicker').endDate;
			startDate = startDate.format('YYYY-MM-DD');
			endDate = endDate.format('YYYY-MM-DD');
		} else {
			startDate = '';
			endDate = '';
		}
		name = $("#name").val();
		email = $("#email").val();

		ajaxCall.getContactUs(startDate, endDate, name, email);
	});

	$('body').on('click', '.deleteInquiry', function () {
		$('#inquiryIdForDelete').val($(this).attr('data'));
	});

	$('body').on('click', '#confirmDelete', function () {
		ajaxCall.postDeleteInquiry($('#inquiryIdForDelete').val())
	})
});

var ajaxCall = {
	getContactUs: function (startDate, endDate, name, email) {
		// $('#tblContactUs').DataTable().destroy();
		$('#tblContactUs').DataTable({
			language: {
                searchPlaceholder: "Search by Name, Email, Message..."
            },
			bDestroy: true,
			processing: true,
			serverSide: true,
			"scrollX": true,
			"ajax": {
				'type': 'get',
				'url': baseUrl + '/securefcbcontrol/contactUs/contactUsData',
				'data': { startDate: startDate, endDate: endDate, name: name, email: email }
			},
			// columns: [/*{
            // 	"target": 0,
            //     "bSortable": false,
            //     "order":false,
            // 	"render": function ( data, type, row ) {
            //         return "<input type='checkbox'>";
            //     },
            // },*/{
			// 		"target": 1,
			// 		"data": 'name'
			// 	}, {
			// 		"target": 2,
			// 		"data": 'email'
			// 	}, {
			// 		"target": 3,
			// 		"data": 'message'
			// 	}, {
			// 		"target": 4,
			// 		"data": 'ip_address'
			// 	}, {
			// 		"target": 5,
			// 		"data": 'created_at',
			// 		'className': 'text-center'
			// 		// "render": $.fn.dataTable.render.moment( 'M/D/YYYY' )
				// },
				// {
				// 	"target": 0,
				// 	"bSortable": false,
				// 	"order": false,
				// 	'className': 'text-center',

				// 	"render": function (data, type, row) {
				// 		// var isReplied = "<a href='#'><i class='fas fa-reply viewMessage' data-toggle='modal' data='"+row['id']+"' data-target='#contactUsReplyModal'></i></a> <a href='#'><i class='fas fa-trash deleteInquiry' data-toggle='modal' data-target='#contactUsDeleteModel' data='"+row['id']+"'></i></a>";
				// 		var isReplied = '<a class="nav-link viewMessage" data-toggle="modal" data="' + row['id'] + '" data-target="#contactUsReplyModal">Reply</a>';
				// 		if(row['is_replied'] == 1){
				// 			// isReplied = "<a  href='#'><i class='fas fa-eye viewMessage' data-toggle='modal' data='"+row['id']+"' data-target='#contactUsMessageModal'></i></a> ";
				// 			isReplied = '<a class="nav-link viewMessage" data-toggle="modal" data="'+row['id']+'" data-target="#contactUsMessageModal">View</a>';
				// 		}
				// 		//       return isReplied;
				// 		var output = '<div class="d-inline-block dropdown">'
				// 			+ '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
				// 			+ '<span class="btn-icon-wrapper pr-2 opacity-7">'
				// 			+ '<i class="fa fa-cog fa-w-20"></i>'
				// 			+ '</span>'
				// 			+ '</button>'
				// 			+ '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
				// 			+ '<ul class="nav flex-column">'
				// 			+ '<li class="nav-item">'
				// 			+ isReplied
				// 			+ '</li>'
				// 			+ '<li class="nav-item">'
				// 			+ '<a class="nav-link deleteInquiry" data-toggle="modal" data-target="#contactUsDeleteModel" data="' + row['id'] + '">Delete</a>'
				// 			+ '</li>'
				// 			+ '</ul>'
				// 			+ '</div>'
				// 			+ '</div>';
				// 		return output;
				// 	},
			// 	}],
			columns: [
				{
					data: 'id',
					name: 'action', // orderable: true, // searchable: true
					orderable: false,
					className:'text-center',
					"render": function (data, type, row) {
						// var isReplied = "<a href='#'><i class='fas fa-reply viewMessage' data-toggle='modal' data='"+row['id']+"' data-target='#contactUsReplyModal'></i></a> <a href='#'><i class='fas fa-trash deleteInquiry' data-toggle='modal' data-target='#contactUsDeleteModel' data='"+row['id']+"'></i></a>";
						var isReplied = '<a class="nav-link viewMessage" data-toggle="modal" data="' + row['id'] + '" data-target="#contactUsReplyModal">Reply</a>';
						if(row['is_replied'] == 1){
							// isReplied = "<a  href='#'><i class='fas fa-eye viewMessage' data-toggle='modal' data='"+row['id']+"' data-target='#contactUsMessageModal'></i></a> ";
							isReplied = '<a class="nav-link viewMessage" data-toggle="modal" data="'+row['id']+'" data-target="#contactUsMessageModal">View</a>';
						}
						//       return isReplied;
						var output = '<div class="d-inline-block dropdown">'
				 			+ '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
							+ '<span class="btn-icon-wrapper pr-2 opacity-7">'
							+ '<i class="fa fa-cog fa-w-20"></i>'
							+ '</span>'
							+ '</button>'
							+ '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
							+ '<ul class="nav flex-column">'
							+ '<li class="nav-item">'
							+ isReplied
							+ '</li>'
							+ '<li class="nav-item">'
							+ '<a class="nav-link deleteInquiry" data-toggle="modal" data-target="#contactUsDeleteModel" data="' + row['id'] + '">Delete</a>'
							+ '</li>'
							+ '</ul>'
							+ '</div>'
							+ '</div>';
						return output;
					},
				},
				{
					data: 'name',
					name: 'name',
					className:'text-left'
				},
				{
					data: 'email',
					name: 'email',
					className:'text-left'
				},
				{
					data: 'message',
					name: 'message',
					className:'text-left'
				},
				{
					data: 'ip_address',
					name: 'ip_address',
					className:'text-left'
				},
				{
					data: 'created_at',
					name: 'created_at',
					className:'text-center',
					"render": function (data, type, row) {
						// return data
						return moment.utc(data).format('Do MMM YYYY')
						// return moment.utc(data).utcOffset(z.replace('.', "")).format('Do MMM YYYY')
					}
				},
			],
			"order": [[5, 'desc']],
		});
	},

	getContactUsInquiryData: function (inquiryId, activity) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: 'get',
			url: baseUrl + '/securefcbcontrol/contactUs/inquiry',
			data: { 'inquiryId': inquiryId, 'activity': activity },
			beforeSend: function () {
				$('#loaderimage').css("display", "block");
				$('#loadingorverlay').css("display", "block");
			},
			success: function (response) {
                console.log(response['contact_us_reply']);
				if (typeof response['contact_us_reply'] === 'undefined') {
					$('#customerName').val(response['first_name']);
					$('#customerMessage').text(response['message']);
					$('#inquiryId').val(response['id']);
				} else if (typeof response['contact_us_reply'] !== 'undefined') {
					$('#customerNameView').text(response['first_name']);
					$('#customerMessageVew').text(response['message']);
					$('#replyMessageView').text(response['contact_us_reply']['reply']);
				}
			}
		});
	},

	postReply: function (formData) {
        $.blockUI();

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: baseUrl + '/securefcbcontrol/contactUs/reply',
			type: 'POST',
			data: formData,
			success: function (result) {
				if (result['success'] == true) {
					toastr.success(result.message);
					$("#contactUsReplyModal").modal('hide');
					ajaxCall.getContactUs(startDate, endDate, name, email);
				}
			},
			error: function (data) {
				console.log(data);
			}
		}).always(function(){
			$.unblockUI();
		});
	},

	postDeleteInquiry: function (inquiryIdForDelete) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: baseUrl + '/securefcbcontrol/contactUs/deleteInquiry',
			type: 'POST',
			data: { inquiryIdForDelete: inquiryIdForDelete },
			success: function (result) {
				if (result['success'] == true) {
					// $('#contactUsDeleteModel').hide();
					toastr.success(result.message);
					$("#contactUsDeleteModel").modal('hide');
					ajaxCall.getContactUs(startDate, endDate, name, email);

				}
			},
			error: function (data) {
				console.log(data);
			}
		});
	}
}
