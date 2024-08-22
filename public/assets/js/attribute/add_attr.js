
$(document).ready(function () {

    /* Hide attribute types tab on page load */
    if(page_name == 'add')
        $('a[href$="attribute_types"]:first').hide();

    // if attribute type is 6,7,8 - show attribute options tab on page load
    if(page_name == 'edit')
    {
        var counter = $('#block_count').val();
        var index = 1;
        $('.attribute_block').each(function(){            
            if(index != counter)
            {
                $('#add_more_type_' + index).css('display','none');
            }
            else
            {
                $('#add_more_type_' + index).css('display','block');
            }
            index = index + 1;
        })

        var attr_type = $('#attribute_type_id').val();
       
        if (attr_type == 7 || attr_type == 8 || attr_type == 6) 
        {
            $('a[href$="attribute_types"]:first').show();
        }
        if (attr_type != 7 && attr_type != 8 && attr_type != 6) 
        {
            $('a[href$="attribute_types"]:first').hide();
        }
        if (attr_type == 6) 
        {
            $('input[id*=display_name').addClass('colorpicker-default');
            $('.multi').show();
            $('.colorpicker-default').colorpicker();
        } 
        else 
        {
            $('input[id*=display_name').removeClass('colorpicker-default');
            $('.multi').hide();
        }
    }
})

/* enable tab on attribute type - dropdown change */
$('#attribute_type_id').on('change', function () {

    if ($(this).val() == 7 || $(this).val() == 8 || $(this).val() == 6) 
    {
        $('a[href$="attribute_types"]:first').show();
    }
    if ($(this).val() != 7 && $(this).val() != 8 && $(this).val() != 6) 
    {
        $('a[href$="attribute_types"]:first').hide();
    }
    if ($(this).val() == 6) 
    {
        $('input[id*=display_name').addClass('colorpicker-default');
        $('.multi').show();
        $('.colorpicker-default').colorpicker();
    } 
    else 
    {
        $('input[id*=display_name').removeClass('colorpicker-default');
        $('.multi').hide();
    }
});

/* store attribute details and attribute types */
function add_attr_type_block(index) 
{
    if(page_name == 'add')
        url = "../addAttrTypeBlock";
    if(page_name == 'edit')
        url = "../.././addAttrTypeBlock";

    id = index + 1;
    var counter = $('#block_count').val();
    $('#add_more_type_' + index).hide();
    $.ajax({
        url: url,
        type: "POST",
        data: {
            'index': id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) 
        {
            $('#attr_type_add').append(data);
            counter++;

            $('#block_count').val(counter);

            var attr_type = $('#attribute_type_id').val();
            if (attr_type == 6) 
            {
                $('input[id*=display_name').addClass('colorpicker-default');
                $('.multi').show();
                $('.colorpicker-default').colorpicker();
            } 
            else 
            {
                $('input[id*=display_name').removeClass('colorpicker-default');
                $('.multi').hide();
            }
          
        }
    })
}

/* remove dynamic attribute block*/
function remove_attr_type_block(index)
{
    console.log(index);
    $('#attr_type_block_' + index).remove();  
    var counter = $('#block_count').val();
    $('#block_count').val(counter-1);
    
    // console.log(counter);
    counter = counter - 1;
    if(counter != index)
    {
        $('#add_more_type_' + (index-1)).show();
    }
    if($('#block_count').val() == 1)
        $('#add_more_type_' + (index-1)).show();

    if(index == counter)
    {
        id = index;
        $('#add_more_type_' + id).show();
        $('#remove_more_type_' + id).hide();
    }
    if(counter == 1)
    {
        $('#add_more_type_' + index).show();
        $('.fa-minus').hide();
    }

}
