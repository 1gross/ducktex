function quantity() {
    var quant = $('.quantity-block').find('.quantity-num');
    var quantMinus = $('.quantity-block').find('.quantity-arrow-minus');
    var quantPlus =  $('.quantity-block').find('.quantity-arrow-plus');
    var min = parseFloat(quant.attr('data-min'));
    var max = parseFloat(quant.attr('data-max'));
    var step = parseFloat(quant.attr('data-step'));
    var unit = quant.attr('data-unit');

    quant.val(min + ' ' + unit);

    quantMinus.on('click', function () {
        if (parseFloat(quant.val().replace(unit, '')) > min) {
            var tempMin = +parseFloat(quant.val().replace(unit, '')) - step;

            if (parseInt(min) === min){
                quant.val(parseInt(tempMin) + ' ' + unit);
            } else {
                quant.val(tempMin.toFixed(1) + ' ' + unit);
            }
        }
    });
    quantPlus.on('click',  function () {
        if (parseFloat(quant.val().replace(unit, '')) < max) {
            var tempMax = +parseFloat(quant.val().replace(unit, '')) + step;
            if (parseInt(min) === min){
                quant.val(parseInt(tempMax) + ' ' + unit);
            } else {
                quant.val(tempMax.toFixed(1) + ' ' + unit);
            }

        }
    });

}
$(document).ready(function () {
    quantity();

    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });

});

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
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

$('.slider-big').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-thumb'
});
$('.slider-thumb').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-big',
    focusOnSelect: true
});
