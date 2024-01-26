$(document).ready(function() {

    menu();

    // Load trick
    $(document).on('click', '.load-trick', function() {
        if ($('#trick-list').attr('data-lock-trick') == 0) {
            loadTrick();
            $('#trick-list').attr('data-lock-trick', 1);
        }
    });

    // Display first trick
    $(document).on('click', '.home-page-down-button', function() {
        $('.trick-container').css("display", "block");

        if ($('#trick-list').attr('data-lock-trick') == 0) {
            trickListDiv = $('#trick-list').offset().top;
            $('html, body').scrollTop(trickListDiv);
            $('#trick-list').attr('data-lock-trick', 1);
            loadTrick();
        }
    });

    $(document).on('click', '.home-page-up-button', function() {
        trickListTitle = $('.text-title-trick').offset().top;
        $('html, body').scrollTop(trickListTitle); 
    });

    // Load trick function
    function loadTrick() {
        $('.home-page-down-button').hide();
        nbTrick = $('#trick-list').attr('data-nb-trick');

        $('#trick-list').append('<p class="col-12 text-loading text-center bg-success text-uppercase mt-3">chargement des tricks</p>');
        $.ajax({
            url: '/show',
            type: "POST",
            data: {
                nbTrick : nbTrick,
            },
            success: function(msg)
            {
                trickList = msg['listTrick'];
                trickCard = '';
                $.each(trickList, function (key, val) {
                    trickCard += '<div class="card trick-card col-9 col-sm-5 col-lg-2 col-xl-2 m-1 mt-5">';
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
                    if(msg['nbTrick'] >= 15)
                    {
                        $('.home-page-up-button').css("display", "block");
                    }
                });

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + jqXHR.responseText);
            }
        });
    }

    function menu() {
        href = window.location.href;
        href = href.replace('http://localhost:8741', '');
        $(".menu li a").each(function() {
            if(href === $(this).attr('href'))
            {
                $(this).addClass('current-menu');
            }
        });
    }
});