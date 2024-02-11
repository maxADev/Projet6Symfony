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

    $(document).on('click', '.show-trick-asset', function() {
        $('.hide-trick-asset').addClass("hide-trick-asset-block");
        $('.trick-asset-container').addClass("display-flex");
        $(this).hide();
    });

    $(document).on('click', '.hide-trick-asset', function() {
        $('.show-trick-asset').css("display", "block");
        $('.trick-asset-container').removeClass("display-flex");
        $(this).removeClass('hide-trick-asset-block');
    });

    $(document).on('click', '.close-flash-message', function() {
        $(this).parent().hide();
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
                    trickImageValue = "/assets/images/trick-no-image.jpg";
                    trickCard += '<div class="card trick-card col-9 col-sm-5 col-lg-2 col-xl-2 m-1 mt-5">';
                    if(trickList[key].image) {
                        trickImageValue = "/upload/"+trickList[key].image;
                    }
                        trickCard += '<img class="card-img-top home-card-img" src="'+trickImageValue+'" alt="Card image cap"/>';
                        trickCard += '<div class="card-body row">';
                            trickCard += '<div class="col-9">';
                            trickCard += '<h5 class="card-title"><a href="/trick/'+trickList[key].slug+'">'+trickList[key].name+'</a></h5>';
                            trickCard += '</div">';
                            trickCard += '</div>';
                            if(trickList[key].modify === true) {
                                trickCard += '<div class="col-3">';
                                trickCard += '<div><a href="/modify-trick/'+trickList[key].slug+'"><i class="bi bi-pencil-square"></i></a></div>';
                                trickCard += '</div">';
                            }
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