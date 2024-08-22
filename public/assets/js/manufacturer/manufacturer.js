$( document ).ready(function() {
    init.handler();
	ajaxCalls.loadDataTable();
    
});

var init = {
    handler: function () {
        $('body').on('click', '#brandExport', function() {
            ajaxCalls.exportBrand();
        });

        $('body').on('click', '.deleteBrand', function () {
            var brandId = $(this).attr('data');
            $('#brandDeleteModel').on('show.bs.modal', function(e){
                ajaxCalls.loadDataTableForDeleteBrand(brandId);
            });
            $('#brandDeleteModel').modal('show');
        });

        $('body').on('click', '.deleteBrandLanguage',function () {
            var brandDetailId = $(this).attr('data');
            $('#brandDetailId').val(brandDetailId);
            $('#brandLanguageDeleteModel').modal('show');
        })

        $('body').on('click', '#confirmDelete', function () {
            ajaxCalls.deleteBrand($('#brandDetailId').val());
        });
    }
}

var ajaxCalls = {
	loadDataTable: function () {
		
		$('#tableManufacturers').DataTable({
            processing: true,
            serverSide: true,
            ajax: '../securefcbcontrol/manufacturers/list',
            columns: [{
            	"target": 0,
                "visible": false,
            	"data":'id'
            },{
            	"target": 0,
            	"data":'brandName'
            },{
            	"target": 1,
            	"data":'status'
            },
            { data: 'mfg_created_at', name: 'mfg_created_at',
                render: function (data,type,row) {                    
                    if(row.user_zone != null)
                    {
                        var z = row.user_zone;
                        return moment.utc(row.mfg_created_at).utcOffset(z.replace(':', "")).format('YYYY-MM-DD HH:mm:ss')
                    }
                    else
                    {
                        return "-----"
                    }
                    
                }
            },
            /*{
                "target": 3,
                "data":'status'
            },*/{
                "target": -1,
                "bSortable": false,
                "order":false,
            	"render": function ( data, type, row ) {
                    return "<a href='"+ window.location.href +"/edit/"+ row.id + "'><i class='fas fa-edit'></i></a> &nbsp &nbsp"+
                     "<button class='btn'><i style='color:red;' class='fas fa-trash deleteBrand' id='deleteBrand' data="+row['id']+"></i></button>"+
                     "<a href='"+ window.location.href +"/add?page=anotherLanguage&brandId="+ row.id +"'> <button class='btn btn-primary ml-3'><i class='fa fa-plus'></i> Add Another Language</button></a>";
                },
            }]
        });
	},

    loadDataTableForDeleteBrand: function (brandId) {
        $('#tblDeleteBrand').DataTable().destroy();
        $('#tblDeleteBrand').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                'type': 'get',
                'url': baseUrl+'/securefcbcontrol/manufacturers/languageWiseBrand',
                'data': {brandId:brandId}
            },
            columns: [{
                "target": 0,
                "data":'id'
            },{
                "target": 1,
                "data":'brandName'
            },{
                "target": 2,
                "render": function ( data, type, row ) {
                    var language = row['languageName'];
                    if (row['isDefault'] == 1) {
                        language = row['languageName'] +"( Default )";
                    }
                    return language;
                },
            },{
                "target": -1,
                "bSortable": false,
                "order":false,
                "render": function ( data, type, row ) {
                    return "<i style='color:red;' class='fas fa-trash btn deleteBrandLanguage' data="+row['id']+"></i></a>";
                },
            }]
        });
    },

    deleteBrand: function (id) {
        $.ajax({
                type: "get",
                url:'manufacturers/deleteBrand',
                data:{'brandDetailId':id},
                success: function (response) {
                    if (response['success'] == true) {
                        $('#brandDeleteModel').modal('hide');
                        toastr.success(response.message);
                    }
                }
            });
    },

    exportBrand: function () {
        $.ajax({
                type: "get",
                url:'manufacturers/export',
                success: function (response) {
                    if (response === "brands.csv") {
                        window.location.href = '../brands.csv';
                    }
                }
            });
    }
}
