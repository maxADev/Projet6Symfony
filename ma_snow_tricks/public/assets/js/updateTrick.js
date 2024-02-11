$(document).ready(function() {
    $(document).on('click', '.remove-image', function() {
        let imageId = $(this).data('image-id');
        let token = $(this).data('token');

        if(confirm('Confirmer la suppression ?'))
        {
            $.ajax({
                url: '/delete-image/'+imageId,
                type: "POST",
                data: {
                    token : token,
                },
                success: function(msg)
                {
                    if(msg.success) {
                        $('.remove-image[data-image-id='+imageId+']').parent().remove();
                    } else {
                        console.log('non');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log(textStatus + jqXHR.responseText);
                }
            });
        }
    });
});