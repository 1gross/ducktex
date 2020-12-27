$(document).ready(function () {
   let orderShow = $('.js-init-order__show'),
       reOrder = $('.js-init-order__buy');

    $(document).on('click', '.js-init-order__show-products', function() {
        let parent = $(this).parent(),
            basketItems = parent.find('.order-basket');

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).find('span').removeClass('arrow-up').addClass('arrow-down');
            basketItems.fadeOut();
        } else {
            $(this).addClass('active');
            $(this).find('span').removeClass('arrow-down').addClass('arrow-up');
            basketItems.fadeIn();
        }

    });

    reOrder.on('click', function (e) {
       let arProducts = JSON.parse($(this).attr('data-products'));

       $.ajax({
           url: '/local/tools/ajax.php',
           data: {
               action: 'add_products_basket',
               products: arProducts
           },
           dataType: 'json',
           method: 'post',
           success: function (response) {
               let modal = '<div class="modal active">' +
                   '<div class="modal-block">' +
                   '<button class="arcticmodal-close close" />' +
                   '<div class="modal-title">Заказ добавлен в корзину</div> ' +
                   '<div class="modal-body"><a href="/basket/" class="btn blue">Перейти в корзину</a></div> ' +
                   '</div></div>';
               $.arcticmodal({
                   content: modal
               });
           }
       })
   });
   orderShow.on('click', function (e) {
       let orderId = $(this).closest('tr').attr('data-id'),
           orderBlock =  $('.order_' + orderId),
           c = $('<div class="modal active" />');


       c.prepend('<div class="modal-block modal-order" />');
       $('.modal-block', c)
           .append('<button class="arcticmodal-close close" />')
           .append('<div class="modal-title" />')
           .append('<div class="modal-body" />');

       orderBlock.clone().appendTo($('.modal-body', c));

       $('.modal-title', c).html('Мой заказ №' + orderId);

       $.arcticmodal({
           content: c
       });
       /*$.arcticmodal({
           type: 'ajax',
           url: '/local/tools/order.php',
           ajax: {
               type: 'POST',
               cache: false,
               data: {
                   action: 'getOrder',
                   orderId: orderId
               },
               dataType: 'json',
               success: function(data, el, responce) {
                   let h = $('<div class="modal active" id="sign">' +
                       '<div class="modal-block">' +
                       '<button class="arcticmodal-close close"></button>' +
                       '<div class="modal-title">Мой заказ №'+orderId+'</div>' +
                       '<div class="modal-body"></div>' +
                       '</div></div>');

                   data.body.html(h);
               }
           }
       })*/
   });
});