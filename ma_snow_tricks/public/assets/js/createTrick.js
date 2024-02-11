$(document).ready(function() {
    $(document).on('click', '.add-video', function() {
        let index = $(".trick-video-item" ).length;
        let deleteButton = '<button type="button" class="btn btn-danger remove-video"><i class="bi bi-x-square"></i></button>';
        let videoInput = '<div class="container-fluid col-12 row align-items-center m-0 p-0"><div class="container-fluid col-10 m-0 p-0">'
        let newInput = $('.trick-video').data('prototype').replace(/__name__/g, index);
        videoInput += newInput;
        videoInput += '</div>';
        videoInput += '<div class="container-fluid delete-button-container col-2 m-0 p-0">';
        videoInput += deleteButton;
        videoInput += '</div>';
        videoInput += '</div>';
        $('.trick-video').append(videoInput);
    });

    $(document).on('click', '.remove-video', function() {
        if(confirm('Confirmer la suppression ?'))
        {
            $(this).parent().parent().remove();
        }
    });
});