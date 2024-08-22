$( document ).ready(function() {

	init.handler()

 	$('#jstree_demo_div').jstree({
        'core' : {
      		'data' : {
              	'url' : 'categories',
              	'data' : function (node) {
                	return { 'id' : node.id };
              	},
              	"dataType" : "json"
            },
            'check_callback' : true,
            'themes' : {
              	'responsive' : false
            }
      	},
      	"contextmenu":{
            "items": function($node) {
                var tree = $("#jstree_demo_div").jstree(true);
                return {
                    "Create": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Create",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            inst.create_node(obj, {}, "last", function (new_node) {
                                setTimeout(function () { inst.edit(new_node); },0);
                            });
                        }
                    },
                    "Rename": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Rename",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            inst.edit(obj);
                        }
                    },
                };
            }
        },
      "plugins" : [ "contextmenu", "dnd", "state", "types" ],
      "state" : { "key" : "demo3" },
	}).on('create_node.jstree', function (e, data) {
      $.post('category/update?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'title' : data.node.text, '_token': $('meta[name="csrf-token"]').attr('content') })
        .done(function (d) {
          data.instance.set_id(data.node, d.id);
        })
        .fail(function () {
          data.instance.refresh();
        });
    }).on('rename_node.jstree', function (e, data) {
      $.post('category/update?operation=rename_node', { 'id' : data.node.id, 'title' : data.text, '_token': $('meta[name="csrf-token"]').attr('content')})
        .fail(function () {
          data.instance.refresh();
        });
    }).on("select_node.jstree", function (event, data) {
     if( data.selected!=1 && data.instance.get_node(data.selected).text!='Root Category' ) {
            $('.categoryblock').show();
            handleEvent({'id':data.selected,'event':event.type},data.node);
         } else {
            $('.categoryblock').hide();
         }
	});

});



function handleEvent(data, node) {

    $('#loaderimage').css("display", "block");
    $('#loadingorverlay').css("display", "block");

    var out = new Array();
    for (key in data) {
        out.push(key + '=' + encodeURIComponent(data[key]));
    }
    if(data.event == 'select_node'){
        var caturl =  'category/show/'+data['id'];
        var url = caturl.replace(-1,data['id']);
        jQuery.ajax({
            type: "get",
            url: url,
            async: true,
            dataType:'json',
            data: '&_token={{ csrf_token()}}',
            success: function (response) {
                var category = response.category[0];
                $('#loaderimage').css("display", "none");
                $('#loadingorverlay').css("display", "none");

                if (response.success) {
                	$('#categoryName').val(category.title);
                    $('#frontName').val(category.frontName);
                    $('#status').val(category.status);
                    $('#title').val(category.title);
                    $('#categoryId').val(category.id);
                    $('#displayTopMenu').val(category.displayTopMenu);
                    $('#displayInHome').val(category.displayInHome);
                    // //$('#description').val(category.description);
                    // if(category.size_chart != null) {
                    //     CKEDITOR.instances.size_chart.setData(category.size_chart);
                    //     CKEDITOR.instances.size_chart.updateElement();
                    // } else {
                    //     CKEDITOR.instances.size_chart.setData('');
                    //     CKEDITOR.instances.size_chart.updateElement();
                    // }
                    $('#metaTitle').val(category.metaTitle);
                    $('#metaKeywords').val(category.metaKeywords);
                    $('#metaDescription').val(category.metaDescription);
                    $('#skuPrefix').val(category.skuPrefix);
                    $('#categorySlug').val(category.categorySlug);
                    $('#imageCategory').attr('src',category.categoryImage);
                    $("#refundable option:contains('" + category.refundable + "')").attr('selected', 'selected');
                    $("#replaceable option:contains('" + category.replaceable + "')").attr('selected', 'selected');
                    $("#tag option:contains('" + category.tag + "')").attr('selected', 'selected');
                    $("#shipBy option:contains('" + category.shipBy + "')").attr('selected', 'selected');

                } else {
                     alert('An error occurs in loading Category data.');
                    return false;
                }
            }
        });
    } else {
    	
        jQuery.ajax({
            type: "post",
        	url:'category/update',
            async: true,
            dataType:'json',

            data:out.join('&'),
            success: function (response) {
                $('#loaderimage').css("display", "none");
                $('#loadingorverlay').css("display", "none");
            }
        });
    }
}

var init = {
	handler: function () {
		$('#categoryExport').on('click', function() {
			ajaxCalls.categoryExport();
		})
	}
}

var ajaxCalls = {
	categoryExport:function () {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$.ajax({
          	type: 'get',
          	url: 'categoryExport',
          	beforeSend: function() {
               	$('#loaderimage').css("display", "block");
               	$('#loadingorverlay').css("display", "block");
          	},
          	success: function (response) {
          		if (response === "categories.csv") {
          			window.location.href = '../categories.csv';
          		}
          		console.log(response);
          	}
        });
	}
}

var formValidations = {
	//general form validations
	generalValidation : function() {
	  	$("form[name='generalDetails']").validate({
	    	rules: {
	      		categoryName: "required",
	    	},
	    	// Specify validation error messages
	    	messages: {
	      		categoryName: "Please enter Category Name",
	    	},
	    	submitHandler: function(form) {
	      		form.submit();
	    	}
	  	});
	}
}
