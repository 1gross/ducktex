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
$('.compare-list').each(function () {
    var status = $(this).prev().find('.count');
    $(this).on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
        var i = (currentSlide ? currentSlide : 0) + 1;
        status.html(i + '/<span>' + slick.slideCount + '</span>');
    });
    $(this).slick({
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: $(this).prev().find('.left'),
        nextArrow: $(this).prev().find('.right'),
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });
});

function quantity() {
    jQuery('.quantity-block').each(function () {
        var quant = $(this).find('.quantity-num');
        var quantMinus = $(this).find('.quantity-arrow-minus');
        var quantPlus =  $(this).find('.quantity-arrow-plus');
        var min = parseFloat(quant.attr('data-min'));
        var max = parseFloat(quant.attr('data-max'));
        var step = parseFloat(quant.attr('data-step'));
        var unit = quant.attr('data-unit');

        console.log(step);

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
    });

}

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
    quantity();

    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });
    $('button.tab-link').click(function(){
        var tab_id = $(this).attr('data-tab');

        $(this).toggleClass('current');
        $("#"+tab_id).slideToggle();
    });

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

    $(".sms-code input").keypress(function (e) {
        if (e.which != 8 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $('.sms-code input').keyup(function (e) {

    });

    $('.sms-code input').keydown(function (e) {
        if (this.value.length == this.maxLength && e.which != 8) {
            if($(this).next('input').length == 0){
                var final = '';
                $('.sms-code input').each(function () {
                    final = final + $(this).val();
                });
                console.log(final);
            } else {
                $(this).next('input').focus();
            }
        } else if(this.value.length == 0 && e.which == 8){
            $(this).prev('input').focus().val('');
        }

    });

    $('.modal-link').on('click', function () {
        $('.show').removeClass('show');
       $($(this).attr('data-modal')).addClass('show');
    });
    $('.modal').on('click', function (e) {
        var div = $(".modal-block");
        if (!div.is(e.target)
            && div.has(e.target).length === 0) {
            $('.modal.show').removeClass('show');
        }
    });
    $('.modal-block .close').on('click', function (e) {
        $('.modal.show').removeClass('show');
    });

    $('.modal .back').on('click', function (e) {
        $('.modal.show').removeClass('show');
        $('#sign').addClass('show');
    });

    $('[type="tel"]').mask("+7 (000) 000-00-00");
    $('[name="dob"]').mask("00/00/0000");

    $('.faq-item .faq-item-header').on('click', function () {
        $(this).toggleClass('open');
       $(this).siblings('.faq-item-body').slideToggle();
    });
    $('.filter-body-item .header').on('click', function () {
        $(this).toggleClass('open');
        $(this).siblings('.body').slideToggle();
    });


});



$('.product-card button.compare, #card button.compare').on('click', function () {
    var button = $(this);
    var request = $.ajax({
        url: 'http://ducktext.eto-yasno.ru/local/tools/ajax.php',
        type: 'GET',
        data: { action: $(this).attr('data-action'), id : $(this).attr('data-id')} ,
    });
    request.done(function( data ) {
            var result = jQuery.parseJSON(data);
            $('#header .compare span').text(result.compare_count);
            if (result.is_add){
                button.addClass('active');
            } else {
                button.removeClass('active');
            }
        });
    request.fail(function( ) {
            alert('Ошибка');
        });
});

$('.product-card button.favorites, #card button.favorites').on('click', function () {
    var button = $(this);
    var request = $.ajax({
        url: 'http://ducktext.eto-yasno.ru/local/tools/ajax.php',
        type: 'GET',
        data: { action: $(this).attr('data-action'), id : $(this).attr('data-id')} ,
    });
    request.done(function( data ) {
        var result = jQuery.parseJSON(data);
        if (result.is_add){
            button.addClass('active');
        } else {
            button.removeClass('active');
        }
    });
    request.fail(function( ) {
        alert('Ошибка');
    });
});

$('#card .add-cart').on('click', function () {
    var button = $(this);
    var request = $.ajax({
        url: 'http://ducktext.eto-yasno.ru/local/tools/ajax.php',
        type: 'GET',
        data: { action: $(this).attr('data-action'), id : $(this).attr('data-id'), quantity : $('#card .quantity-block .quantity-num').val().replace(/[^\d.-]/g, '')} ,
    });

    request.done(function( data ) {
        var result = jQuery.parseJSON(data);
        $('#header .basket span').text(result.basket.count_items);
        button.text('ДОБАВЛЕНО');
    });
    request.fail(function( ) {
        alert('Ошибка');
    });
});

$('#basket .quantity-num').on('change', function () {
    console.log(10);
});



