$(document).ready(function() {
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
                             dataLayer.push({'event': 'formsend'});
                             //location.reload();
                             switch (id) {
                                 case 'subscribe':
                                     alert('Вы успрешно подписалиь, спасибо!');
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
                                         let verificationCode = '';
                                         verificationForm.find('.digit').each(function () {
                                             verificationCode += $(this).val();

                                         });
                                         console.log(verificationCode);
                                         if (verificationCode.length === 6) {
                                             $.ajax({
                                                 url: url,
                                                 dataType: 'json',
                                                 data: {
                                                     action: 'send_form',
                                                     id: 'auth_check_code',
                                                     data: verificationForm.serialize(),
                                                 },
                                                 success: function (res) {
                                                     if (typeof res !== 'undefined') {
                                                         if (res.result === true) {
                                                             $.arcticmodal('close');
                                                             setTimeout(function () {
                                                                 location.reload();
                                                             }, 300);
                                                         } else {
                                                             if (typeof res.message !== 'undefined') {
                                                                 $.each(res.message, function (code, text) {
                                                                     $('[name="'+code+'"]').addClass('error');
                                                                 });
                                                             }
                                                         }
                                                     }
                                                 }
                                             });
                                         }
                                     });

                                     verificationModal.arcticmodal();
                                     break;

                             }
                             break;
                         case 'add_basket':
                             if (typeof response.basket !== 'undefined') {
                                 countBasket.text(response.basket.count_items);
                             }
                             $this.text('В корзине');
                             break;
                         case 'clear_compare':
                             countCompare.text(0);
                             break;
                         case 'update_basket':
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
                         window.location.href = '/personal/';
                     }
                 } else {
                     switch (action) {
                         case 'send_form':
                             if (typeof response.message !== 'undefined' && typeof response.message === 'object') {
                                 $.each(response.message, function (code, text) {
                                     let field = $('[name="'+code+'"]');
                                     field.addClass('error');

                                 });
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
