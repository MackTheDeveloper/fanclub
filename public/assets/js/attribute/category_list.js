$(document).ready(function () {

    if(page_name == 'add')
        url = "../categories";
    if(page_name == 'edit')
        url = "../.././selectedCategories/" + attribute_id;

    init.handler()
    $('#category_list_div').jstree({
        'core': {
            'data': {
                'url': url,
                'data': {},
                "dataType": "json"
            },
            'check_callback': true,
            'themes': {
                'responsive': false
            }
		},
		
        "plugins": ["dnd", "types", "checkbox"],
       
    }).on("select_node.jstree", function (event, data) {
        handleEvent({
            'id': data.selected,
            'event': event.type
        }, data.node);
    });
});

function handleEvent(data, node) 
{
    $('#loaderimage').css("display", "block");
    $('#loadingorverlay').css("display", "block");

    var out = new Array();
	for (key in data) 
	{
        out.push(key + '=' + encodeURIComponent(data[key]));
    }
	if (data.event == 'select_node') 
	{
        var caturl = '../category/show/' + data['id'];
        var url = caturl.replace(-1, data['id']);
        
        if(page_name == 'add')
            showurl = '../category/show/' + data['id'];
        if(page_name == 'edit')
            showurl = '../.././category/show/' + data['id'];


        jQuery.ajax({
            type: "get",
            url: showurl,
            async: true,
            dataType: 'json',
            data: '&_token={{ csrf_token()}}',
			success: function (response) 
			{
                $('#loaderimage').css("display", "none");
                $('#loadingorverlay').css("display", "none");
				category_ids = [];
				if (response.success) 
				{
					var selectedNodes = $('#category_list_div').jstree("get_selected", true);
					// console.log(selectedNodes);
					$.each(selectedNodes, function() {
						category_ids.push(this.id);
					});
					// You can assign checked_ids to a hidden field of a form before submitting to the server
					// console.log(category_ids);
						$('#categoryId').val(category_ids);
                } else {
                    alert('An error occurs in loading Category data.');
                    return false;
                }
            }
        });
    }
}

var init = {
	handler: function () {
	
	}
}