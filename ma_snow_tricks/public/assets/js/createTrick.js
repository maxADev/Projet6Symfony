$(document).ready(function() {
    $(document).on('click', '.add-video', function() {
        let index = $(".trick-video-item" ).length;
        let deleteButton = '<button type="button" class="btn btn-primary remove-video">Supprimer</button>';
        let videoInput = '<div>'
        let newInput = $('.trick-video').data('prototype').replace(/__name__/g, index);
        videoInput += newInput;
        videoInput += deleteButton;
        videoInput += '</div>';
        $('.trick-video').append(videoInput);
    });

    $(document).on('click', '.remove-video', function() {
        $(this).parent().remove();
    });
});