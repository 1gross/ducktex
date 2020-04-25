$(document).ready(function() {
    let url = '/local/tools/ajax.php';
        $(document).on('keyup', '#quantity-c', function () {
            let id = $(this).next().attr('data-id'),
                elm = $(this),
                time = (new Date()).getTime(),
                urlPage = location.href,
                delay = urlPage.indexOf('basket') >= 0 ? 1500 : 50, /* Количество мксек. для определения окончания печати */
                step = $(this).attr('data-step');

            if (parseFloat(elm.val()) >= parseFloat(elm.attr('data-max'))) {
                elm.val(elm.attr('data-max'));
                elm.attr('data-value', parseFloat(elm.val()));
            }
            elm.attr({'data-time': time});
            setTimeout(function () {
                let oldtime = parseFloat(elm.attr('data-time'));
                if (oldtime <= (new Date()).getTime() - delay & oldtime > 0 & elm.attr('keyup') != '' & typeof elm.attr('data-time') !== 'undefined') {
                    if (step.indexOf('.') >= 0) {
                        elm.attr('data-value', parseFloat(elm.val()));
                        elm.val(parseFloat(elm.val()) + ' ' + elm.attr('data-unit'));
                    } else {
                        elm.attr('data-value', Math.round(parseFloat(elm.val())));
                        elm.val(Math.round(parseFloat(elm.val())) + ' ' + elm.attr('data-unit'));
                    }
                    let value = parseFloat(elm.attr('data-value')).toFixed(1);
                    if ($('.basket-table').length > 0) {
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            data: {
                                id: id,
                                action: 'update_basket',
                                quantity: value,
                            },
                            success: function (response) {
                                if (response.result === true) {
                                    submitForm();
                                }
                            }
                        });
                    }
                }
            }, delay);
        });

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
   let countBasket = $('.basket span'),
       countCompare = $('.compare span');

    $(document).on('change', 'input', function (e) {
        if ($(this).hasClass('error')) {
            $(this).removeClass('error');
        }
    });

        //initButton.on('click', function (e) {
   $(document).on('click', '.js-init-action', initButton);

   function initButton() {
       let action = $(this).attr('data-action'),
           id = $(this).attr('data-id') || '',
           refresh = $(this).attr('data-refresh') || false,
           quantity = $('#quantity-c').attr('data-value'),
           measure = ' м',
           $this = $(this),
           send = false,
           form = $(this).closest('form'),
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
               $(modal_id).arcticmodal({
                   beforeOpen: function (data, modal) {
                       let phoneInput = modal.find('[name="PHONE_NUMBER"]');

                       phoneInput.on('change', function () {
                           checkPhoneInput($(this));
                       });
                       phoneInput.on('keyup', function () {
                           checkPhoneInput($(this));
                       });
                       function checkPhoneInput(el) {
                           let btn = el.closest('form').find('.btn'),
                               value = el.val();
                           if (value.length === 18) {
                               btn.removeAttr('disabled');
                           } else {
                               btn.attr('disabled', 'disabled');
                           }
                       }
                   }
               });
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
               //.attr('value', currentValue+' '+valueMeasure)
                   .val(currentValue+' '+valueMeasure)
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
               if ($this.hasClass('btn')) {
                   $this.addClass('loader');
               }
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
                   case 'auth_check_code':
                       console.log(form);
                       data.data = form.off().serialize();
                       break;
                   default:
                       $this.addClass('loader');
                       data.data = form.off().serialize();
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
                   if ($this.hasClass('btn')) {
                       $this.removeClass('loader');
                   }
                   if (response.result === true) {
                       switch (action) {
                           case 'send_form':

                               dataLayer.push({'event': 'formsend'});
                               //location.reload();
                               switch (id) {
                                   case 'subscribe':
                                       alert('Подписка произведена успешно, спасибо!');
                                       break;
                                   case 'set_coupon':
                                       submitForm();
                                       break;
                                   case 'resend_code':
                                       checkErrorAuthCode(form, true);
                                       timerCalc(true);
                                       break;
                                   case 'auth':
                                       let verificationModal = $('#code'),
                                           verificationForm = verificationModal.find('form'),
                                           phoneDesc = verificationForm.find('.modal-desc__phone'),
                                           btnSubmit = verificationForm.find('.btn-accept');

                                       verificationForm.prepend('<input type="hidden" name="SIGN_DATA" value="'+response.sign_data+'">');
                                       verificationForm.prepend('<input type="hidden" name="USER_ID" value="'+response.user_id+'">');
                                       phoneDesc.text(response.phone);

                                       $(document).on('keyup', '.digit', function () {
                                           checkErrorAuthCode(verificationForm);

                                           let verificationCode = '',
                                               value = $(this).val().replace(/[^\d]/g, '');

                                           if (value.length > 0 && parseInt(value) >= 0) {
                                               verificationForm.find('.digit').each(function () {
                                                   verificationCode += $(this).val();
                                               });
                                               if (verificationCode.length < 6) {
                                                   $(this).next().focus();
                                               }
                                           }

                                           if (verificationCode.length === 6) {
                                               verificationForm.find('[name="CODE"]').val(verificationCode);
                                               btnSubmit.removeAttr('disabled');
                                           } else {
                                               btnSubmit.attr('disabled', 'disabled');
                                           }
                                       });

                                       verificationModal.arcticmodal({
                                           beforeOpen: function(data, modalForm) {
                                               checkErrorAuthCode(modalForm);
                                               timerCalc();
                                           }
                                       });
                                       break;
                                   case 'auth_check_code':
                                       setTimeout(function () {
                                           $.arcticmodal('close');
                                           location.reload();
                                       }, 50);
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
                                   case 'auth_check_code':
                                       if (typeof response.message !== 'undefined') {
                                           let modalMessageBlock = form.find('.error-info'),
                                               codeBlock = form.find('.sms-code');

                                           $.each(response.message, function (code, text) {
                                               modalMessageBlock.prepend('<div >' + text + '</div>');
                                           });
                                           codeBlock.addClass('error');
                                           $this.attr('disabled', 'disabled');

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
                           case '':

                               break;
                       }

                   }
               }
           });
       }
       return false;
   }
});
function checkErrorAuthCode(form, clear = false) {
    let errorBlock = form.find('.error'),
        errorMessage = form.find('.error-info');

    if (errorMessage.length > 0) {
        errorMessage.empty();
    }
    if (errorBlock.length > 0 || clear === true) {
        form.find('.digit').each(function () {
            if ($(this).attr('data-id') === '1') {
                $(this).focus();
            }
            $(this).val('');
        });
        if (errorBlock.length > 0) {
            errorBlock.removeClass('error');
        }
    }
}
function timerCalc(refresh = false) {
    let timeBlock = $('.timer');

    if (timeBlock.length > 0) {
        if (refresh === false) {
            let timer = timeBlock.find('span'),
                time = timer.text().split(':');

            if (typeof time !== 'undefined' && typeof time[1] !== 'undefined' && time[1].length > 0) {
                let seconds = parseInt(time[1]);

                if (seconds > 0) {
                    seconds -= 1;
                    if (seconds < 10) {
                        timer.text('0:' + '0' + seconds);
                    } else {
                        timer.text('0:' + seconds);
                    }
                    setTimeout(function () {
                        timerCalc();
                    }, 1000);
                } else {
                    timeBlock.empty().text('Не получили код? Вы можете ').append('<button class="resend-link js-init-action" data-action="send_form" data-id="resend_code">запросить новый код</button>');
                }
            }
        } else {
            timeBlock.empty().text('Запросить новый код можно через ').append('<span>0:59</span>');
            setTimeout(function () {
                timerCalc();
            }, 1000);
        }
    }
}