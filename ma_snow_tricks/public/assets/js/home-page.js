$(document).ready(function() {

    // Load trick
    $(window).scroll(function(){
        var trickList = $('#trick-list').height() + 150;
        if($(this).scrollTop() >= trickList) {
            if ($('#trick-list').attr('data-lock-trick') == 0)
            {
                loadTrick();
                $('#trick-list').attr('data-lock-trick', 1);
            }
        }
    });

    // Display first trick
    $(document).on('click', '.home-page-button', function()
    {
        $('.trick-container').css("display", "block");

        if ($('#trick-list').attr('data-lock-trick') == 0)
        {
            trickListDiv = $('#trick-list').offset().top - 350;
            $('html, body').scrollTop(trickListDiv);
            $('#trick-list').attr('data-lock-trick', 1);

            loadTrick();
        }
    });

    // Load trick function
    function loadTrick()
    {
        nbTrick = $('#trick-list').attr('data-nb-trick');
        $('#trick-list').append('<p class="col-12 text-loading text-center bg-success text-uppercase mt-3">chargement des tricks</p>');
        $.ajax({
            url: '/showTrick',
            type: "POST",
            data: {
                nbTrick : nbTrick,
            },
            success: function(msg)
            {
                trickList = msg['listTrick'];
                trickCard = '';
                $.each(trickList, function (key, val) {
                    trickCard += '<div class="card trick-card col-3 m-1 mt-3">';
                        trickCard += '<img class="card-img-top" src="/upload/'+trickList[key].image+'" alt="Card image cap"/>';
                        trickCard += '<div class="card-body">';
                            trickCard += '<h5 class="card-title"><a href="/trick/'+trickList[key].slug+'">'+trickList[key].name+'</a></h5>';
                        trickCard += '</div>';
                    trickCard += '</div>';

                    $('#trick-list').append(trickCard);
                    trickCard = '';
                    $('#trick-list').attr('data-nb-trick', msg['nbTrick']);
                    $('#trick-list').attr('data-lock-trick', 0);
                    $('.text-loading').remove();
                });

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + jqXHR.responseText);
            }
        });
    }
});