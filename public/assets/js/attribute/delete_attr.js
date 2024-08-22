function delete_attribute(id)
{
    // console.log(id);
    Swal.fire({
        title: 'Are you sure to delete this Attribute?',
        showCancelButton: true,
        confirmButtonColor: '#f5365c',
        cancelButtonColor: '#f7fafc',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) 
        {
            $.ajax({
                url: "../attribute/delete",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                data:{ id : id},
                success: function (data) 
                {
                    if (data.status == true) 
                    {
                        Swal.fire("", "Atrribute deleted successfully!");
                        // window.location.reload();
                        window.setTimeout(function(){location.reload()},3000)
                        table.ajax.reload();                    
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.options.timeOut = 0;
                        toastr.success(response.msg);

                    }
                },
                error: function () {}
            });
        } 
        else
        {
            // result.dismiss can be 'cancel', 'overlay', 'esc' or 'timer'
        }
    })
}
