$('.slider').each(function () {
    var status = $(this).prev().find('.count');
    $(this).on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
        var i = (currentSlide ? currentSlide : 0) + 1;
        status.html(i + '/<span>' + slick.slideCount + '</span>');
    });
    $(this).slick({
        infinite: true,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        prevArrow: $(this).prev().find('.left'),
        nextArrow: $(this).prev().find('.right'),
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                    centerMode: true,
                }
            }
        ]
    });
});
$('.burger').on('click', function () {
    $('.mobile-menu').addClass('show');
});
$('.mobile-menu').on('click', function (e) {
    var div = $(".mobile-menu-block");
    if (!div.is(e.target)
        && div.has(e.target).length === 0) {
        $('.mobile-menu').removeClass('show');
    }
});
$('.mobile-menu .close').on('click', function (e) {
    $('.mobile-menu').removeClass('show');
});
$('#header .search-btn').on('click', function () {
    $('#header .search').toggleClass('open');
});

$('.slider-big').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-thumb',
    responsive: [
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: false,
                arrows: false,
                dots: true,
                centerMode: true,
            }
        }
    ]
});
$('.slider-thumb').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-big',
    focusOnSelect: true
});

$(document).ready(function() {
    if ($(window).width() <= 768) {
        $('meta[name=\'viewport\']').attr('content','width=375');
        $('.catalog-menu-block, .news-wrap').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            centerMode: true,
        });

        $(".select").on('click', function () {
           $(this).toggleClass('open');
        });

        $(document).mouseup(function(event) {

            var target = event.target;
            var select = $(".select");

            if (!select.is(target) && select.has(target).length === 0) {
                select.removeClass("open");
            }

        });

    }

});
