$(document).ready(function() {
    //filter
    let filterApply = $('.js-init-filter_apply');

    filterApply.on('click', function (e) {
        e.preventDefault();
        $(this).closest('form').append('<input type="hidden" name="set_filter" value="y">').submit();
        return false;
    });
    //function detail product page
    $('[data-fancybox="gallery"]').fancybox();

    //function API
   let url = '/local/tools/ajax.php',
       countBasket = $('.basket span'),
       countCompare = $('.compare span');

    $(document).on('change', 'input', function (e) {
        if ($(this).hasClass('error')) {
            $(this).removeClass('error');
        }
    });

        //initButton.on('click', function (e) {
   $(document).on('click', '.js-init-action', function (e) {
      let action = $(this).attr('data-action'),
          id = $(this).attr('data-id') || '',
          refresh = $(this).attr('data-refresh') || false,
          quantity = $('#quantity-c').attr('data-value'),
          measure = ' м',
          $this = $(this),
          send = false,
          data = {
              id: id,
              action: action
          };
      if (typeof quantity !== 'undefined' && quantity.indexOf(measure)) {
          quantity.replace(' м', '');
      }

      switch (action) {
          case 'show_modal':
              let modal_id = $this.attr('data-modal');
              $(modal_id).arcticmodal();
              break;
          case 'add_basket':
              if (typeof quantity !== 'undefined') {
                  data.quantity = quantity;
              }
              $this.addClass('loader');
              send = true;
              break;
          case 'update_basket':
              let quantityBtnType = $this.attr('data-type'),
                  quantityBlock = $this.closest('.quantity-block'),
                  quantityTypePage = quantityBlock.attr('data-page'),
                  quantityInput = quantityBlock.find('#quantity-c'),
                  value = parseFloat(quantityInput.attr('data-value')),
                  valueMin = parseFloat(quantityInput.attr('data-min')),
                  valueMax = parseFloat(quantityInput.attr('data-max')),
                  valueStep = parseFloat(quantityInput.attr('data-step')),
                  valueMeasure = quantityInput.attr('data-unit');
              switch (quantityBtnType) {
                  case 'de':
                      if (value > valueMin) {
                          value -= valueStep;
                          if (quantityTypePage === 'basket') {
                              send = true;
                          }
                      }
                      break;
                  case 'in':
                      if (value < valueMax) {
                          value += valueStep;
                          if (quantityTypePage === 'basket') {
                              send = true;
                          }
                      }
                      break;
              }
              let currentValue = value.toFixed(1);
              quantityInput
                  .attr('value', currentValue+' '+valueMeasure)
                  .attr('data-value', currentValue);
              data.quantity = currentValue;
              break;
          case 'remove_basket':
          case 'add_favorites':
          case 'add_compare':
          case 'clear_compare':
              send = true;
              break;
          case 'send_form':
              switch (id) {
                  case 'set_coupon':
                      let coupon = $('[name="coupon_code"]').val();
                      if (typeof coupon !== 'undefined') {
                          data.coupon_code = coupon;
                      }
                      break;
                  case 'subscribe':
                      let email = $('[name="email_subscribe"]').val();
                      if (typeof email !== 'undefined') {
                          data.email = email;
                      }
                      break;
                  default:
                      data.data = $this.closest('form').off().serialize();
              }

              send = true;

              break;
      }
      if (send) {
          $.ajax({
             url: url,
             dataType: 'json',
             data: data,
             success: function (response) {
                 if (response.result === true) {
                     switch (action) {
                         case 'send_form':
                             //dataLayer.push({'event': 'formsend'});
                             //location.reload();
                             switch (id) {
                                 case 'subscribe':
                                     alert('Подписка произведена успешно, спасибо!');
                                     break;
                                 case 'set_coupon':
                                     submitForm();
                                     break;
                                 case 'auth':
                                     let verificationModal = $('#code'),
                                         verificationForm = verificationModal.find('form'),
                                         phoneDesc = verificationForm.find('.modal-desc__phone');

                                     verificationForm.prepend('<input type="hidden" name="SIGN_DATA" value="'+response.sign_data+'">');
                                     phoneDesc.text(response.phone);

                                     verificationForm.find('.digit').on('keyup', function () {
                                         let errorBlock = verificationForm.find('.error');
                                         if (errorBlock.length > 0) {
                                             verificationForm.find('.digit').each(function () {
                                                 if ($(this).attr('data-id') === '1') {
                                                     $(this).focus();
                                                 }
                                                 $(this).val('');
                                             });
                                             errorBlock.removeClass('error');
                                         }

                                         let verificationCode = '',
                                             value = $(this).val().replace(/[^\d]/g, '');

                                         if (value.length > 0 && parseInt(value) > 0) {
                                             verificationForm.find('.digit').each(function () {
                                                 verificationCode += $(this).val();
                                             });
                                             if (verificationCode.length < 6) {
                                                 $(this).next().focus();
                                             }
                                         }

                                         if (verificationCode.length === 6) {
                                             verificationForm.find('[name="CODE"]').val(verificationCode);
                                             verificationForm.addClass('loading');
                                             $.ajax({
                                                 url: url,
                                                 dataType: 'json',
                                                 data: {
                                                     action: 'send_form',
                                                     id: 'auth_check_code',
                                                     data: verificationForm.serialize(),
                                                 },
                                                 success: function (res) {
                                                     verificationForm.removeClass('loading');
                                                     if (typeof res !== 'undefined') {
                                                         if (res.result === true) {
                                                             $.arcticmodal('close');
                                                             setTimeout(function () {
                                                                 window.location.reload();
                                                             }, 300);
                                                         } else {
                                                             if (typeof res.message !== 'undefined') {
                                                                 verificationForm.find('.sms-code').addClass('error');
                                                             }
                                                         }
                                                     }
                                                 }
                                             });
                                         }
                                     });

                                     verificationModal.arcticmodal({
                                         beforeOpen: function(data, modalFrom) {
                                             let errorBlock = modalFrom.find('.error');
                                             if (errorBlock.length > 0) {
                                                 modalFrom.find('.digit').each(function () {
                                                     if ($(this).attr('data-id') === '1') {
                                                         $(this).focus();
                                                     }
                                                     $(this).val('');
                                                 });
                                                 errorBlock.removeClass('error');
                                             }
                                         }
                                     });
                                     break;
                                 case 'profile_edit':
                                     $.arcticmodal('close');
                                     setTimeout(function () {
                                         location.reload();
                                     }, 300);

                                     break;

                             }
                             break;
                         case 'add_basket':
                             if (typeof response.basket !== 'undefined') {
                                 countBasket.text(response.basket.count_items);
                             }
                             $this.removeClass('loader').text('В корзине');
                             break;
                         case 'clear_compare':
                             countCompare.text(0);
                             break;
                         case 'update_basket':
                             let elm = $this.closest('.quantity-block');
                             let time = (new Date()).getTime();
                             let delay = 300; /* Количество мксек. для определения окончания печати */

                             elm.attr({'data-time': time});
                             setTimeout(function () {
                                 let oldtime = parseFloat(elm.attr('data-time'));
                                 if (oldtime <= (new Date()).getTime() - delay & oldtime > 0 & elm.attr('keyup') != '' & typeof elm.attr('data-time') !== 'undefined') {
                                     submitForm();
                                 }
                             }, delay);
                             break;
                         case 'remove_basket':
                             submitForm();
                             break;
                         case 'add_compare':
                         case 'add_favorites':
                             if (typeof response.isAdd !== 'undefined') {
                                 if (response.isAdd === true) {
                                     $this.addClass('active');
                                 } else {
                                     $this.removeClass('active');
                                 }
                                 if (typeof response.count !== 'undefined' && action === 'add_compare') {
                                     countCompare.text(response.count);
                                 }
                             }
                             break;
                         default:
                         // location.reload();
                     }

                     if (refresh === 'true') {
                         window.location.reload();
                     }
                 } else {
                     switch (action) {
                         case 'send_form':
                             switch (id) {
                                 case 'profile_edit':
                                     if (typeof response.message !== 'undefined' && response.message.length > 0) {
                                         alert(response.message);
                                     }
                                     break;
                                 default:
                                     if (typeof response.message !== 'undefined' && typeof response.message === 'object') {
                                         $.each(response.message, function (code, text) {
                                             let field = $('[name="'+code+'"]');
                                             field.addClass('error');

                                         });
                                     }
                                     break;
                             }

                             break;
                    }

                 }

                 console.log(response);
             }
          });
      }
      return false;
   });
});