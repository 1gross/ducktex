/*
$('.product-card button.compare, #card button.compare').on('click', function () {
    var button = $(this);
    var request = $.ajax({
        url: '/local/tools/ajax.php',
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
});*/
