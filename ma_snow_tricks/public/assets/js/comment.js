$(document).ready(function() {

    loadComment();

    $(document).on('click', '.load-comment', function() {
        loadComment();
    });

    function loadComment() {
        nbComment = $('.trick-comment-container').attr('data-nb-comment');
        slug = $('.trick-comment-container').attr('data-trick-slug');
  
        $.ajax({
            url: '/get-comment/'+slug,
            type: "POST",
            data: {
                nbComment : nbComment,
            },
            dataType: 'json',
            success: function(msg)
            {
                listComment = msg['listComment'];
                commentCard = '';
                $.each(listComment, function (key, val) {
                    commentCard += '<div class="container col-12 col-sm-12 col-lg-9 col-xl-9 d-flex justify-content-center flex-wrap align-items-center comment-container mt-3">';
                    commentCard += '<div class="container col-4 col-sm-4 col-lg-4 col-xl-2 m-0">';
                    if (listComment[key].userImage) {
                        commentCard += '<img class="user-image" src="" alt="Card image cap">';
                    } else {
                        commentCard += '<img class="user-image" src="/assets/images/user-no-image.jpg" alt="Card image cap">';
                    }
                    commentCard += '<p class="m-0">'+listComment[key].userName+'</p>';
                    commentCard += '<p class="m-0">Le '+listComment[key].createDate+'</p>';
                    commentCard += '</div>';
                    commentCard += '<div class="container col-8 col-sm-7 col-lg-8 col-xl-9 m-0">';
                    commentCard += '<p class="m-0">'+listComment[key].content+'</p>';
                    commentCard += '</div>';
                    commentCard += '</div>';

                    $('.trick-comment-container').append(commentCard);
                    commentCard = '';
                });
                $('.trick-comment-container').attr('data-nb-comment', msg['nbComment']);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + jqXHR.responseText);
            }
        });
    }

});
