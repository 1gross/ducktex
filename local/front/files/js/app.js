$(document).ready(function() {
    let url = '/local/tools/ajax.php';

    $('.product_search--inp').on('keyup', function (e) {
        elm = $(this);
        value = $(this).val();
        time = (new Date()).getTime();
        delay = 1000; /* Количество мксек. для определения окончания печати */

        elm.attr({'keyup': time});
        elm.off('keydown');
        elm.off('keypress');
        elm.on('keydown', function (e) {
            $(this).attr({'keyup': time});
        });
        elm.on('keypress', function (e) {
            $(this).attr({'keyup': time});
        });

        if ($(this).val().length >= 3) {
            setTimeout(function () {
                oldtime = parseFloat(elm.attr('keyup'));
                if (oldtime <= (new Date()).getTime() - delay & oldtime > 0 & elm.attr('keyup') != '' & typeof elm.attr('keyup') !== 'undefined') {
                    getResult(value);
                    elm.removeAttr('keyup');
                }
            }, delay);
        }
    });

    function getResult(value) {
        $.ajax({
            url: '/local/tools/search.php',
            data: {
                q: value
            },
            method: 'get',
            dataType: 'json',
            success: function (response) {
                let searchInput = $('.product_search--inp'),
                    searchBlock = searchInput.parent();

                if ($('.search_result').length > 0) {
                    $('.search_result').remove();
                }

                if (typeof response !== 'undefined') {
                    searchBlock.append('<div class="search_result"></div>');
                    $.each(response, function (i, product) {
                        searchBlock.find('.search_result').append('<div class="result_item">' +
                            '<div class="result__img" style="background-image:url('+product['PICTURE']+')"></div>' +
                            '<a href="'+product['DETAIL_PAGE_URL']+'">'+product['NAME']+'</a>' +
                            '<span>'+product['PRICES']['PRINT_PRICE']+'</span></div>');
                    });
                    searchBlock.find('.search_result').append('<div class="search__btn"><a class="btn blue" href="/search/?q='+value+'">Посмотреть все результаты</a></div>');
                }
            }
        });
    }

    $(document).on('click', '.js-init-catalog_show', function () {
        $(this).removeClass('js-init-catalog_show').addClass('js-init-catalog_hide').text('Скрыть разделы');
        $('.catalog-menu-block_block').fadeIn();
    });
    $(document).on('click', '.js-init-catalog_hide', function () {
        $(this).removeClass('js-init-catalog_hide').addClass('js-init-catalog_show').text('Показать разделы');
        $('.catalog-menu-block_block').fadeOut();
    });
    $(document).on('click', '.js-init-filter_show', function () {
        $(this).removeClass('js-init-filter_show').addClass('js-init-filter_hide').text('Скрыть фильтр');
        $('.bx_filter_section').fadeIn();
    });
    $(document).on('click', '.js-init-filter_hide', function () {
        $(this).removeClass('js-init-filter_hide').addClass('js-init-filter_show').text('Показать фильтр');
        $('.bx_filter_section').fadeOut();
    });
    $('#header .search-btn').on('click', function () {
        $('#header .search').toggleClass('open');
    });

    $(document).on('focus', '#quantity-c', function () {
        console.log(1);
        let value = parseFloat($(this).attr('data-value'));
        $(this).val(value);
    });

    $(document).on('blur', '#quantity-c', function () {
        let elm = $(this),
            val = elm.val();

        if (elm.val().indexOf(',') >= 0) {
            let value = String(val).replace(',', '.');
            elm.attr('data-value', value);
            elm.val(value);
        }
        if (elm.attr('data-step').indexOf('.') >= 0) {
            elm.attr('data-value', parseFloat(elm.val()).toFixed(1));
        } else {
            elm.attr('data-value', Math.round(parseFloat(elm.val())));
        }
        /*if (parseFloat(elm.attr('data-value')) > parseFloat(elm.attr('data-max'))) {
            elm.attr('data-value', parseFloat(elm.attr('data-max')));
        }*/
        if (parseFloat(elm.attr('data-value')) < parseFloat(elm.attr('data-min'))) {
            elm.attr('data-value', parseFloat(elm.attr('data-min')));
        }
        if (elm.attr('data-value') === 'NaN') {
            elm.attr('data-value', parseFloat(elm.attr('data-min')));
        }
        elm.val(elm.attr('data-value') + ' ' + elm.attr('data-unit'));
        if ($('.basket-table').length > 0) {
            let id = elm.next().attr('data-id');
            $.ajax({
                url: url,
                dataType: 'json',
                data: {
                    id: id,
                    action: 'update_basket',
                    quantity: elm.attr('data-value'),
                },
                success: function (response) {
                    if (response.result === true) {
                        submitForm();
                    }
                }
            });
        }
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
                       let btn = phoneInput.closest('form').find('.btn');
                       btn.attr('disabled', 'disabled');

                       let phoneNumber = $('input[data-code="PHONE"]');

                       if (phoneNumber.length > 0 && phoneNumber.val().length > 10) {
                           phoneInput.val(phoneNumber.val());
                           btn.removeAttr('disabled');
                           btn.click();
                       }


                       /*if (phoneInput.length > 0) {
                           phoneInput.on('change', function () {
                               checkPhoneInput($(this));
                           });
                           phoneInput.on('keyup', function () {
                               checkPhoneInput($(this));
                           });
                       }

                       function checkPhoneInput(el) {
                           let btn = el.closest('form').find('.btn'),
                               value = el.val();
                           if (value.length === 18) {
                               btn.removeAttr('disabled');
                           } else {
                               btn.attr('disabled', 'disabled');
                           }
                       }*/
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
                       value += valueStep;
                       if (quantityTypePage === 'basket') {
                           send = true;
                       }
                       break;
               }
               let currentValue = value.toFixed(1);
               quantityInput
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
               send = true;
               switch (id) {
                   case 'profile_edit':
                       let newPass = $('[name="NEW_PASS"]').val(),
                           confirmPass = $('[name="CONFIRM_PASS"]').val();

                       if (newPass) {
                           if (newPass.length < 8) {
                               $('[name="CONFIRM_PASS"]').addClass('error');
                               $('[name="NEW_PASS"]').addClass('error');
                               alert('Пароль не может быть меньше 8-ми символов!');
                               send = false;
                           } else {
                               if (newPass !== confirmPass) {
                                   $('[name="CONFIRM_PASS"]').addClass('error');
                                   $('[name="NEW_PASS"]').addClass('error');
                                   alert('Введенные пароли не совпадают!');
                                   send = false;
                               }
                           }
                       }
                       if (send) {
                           $this.addClass('loader');
                           data.data = form.off().serialize();
                       }

                       break;
                   case 'auth_pass':
                       $('.error').each(function () {
                          $(this).removeClass('error');
                       });
                       data.data = form.off().serialize();
                       send = true;
                       break;
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
                       data.data = form.off().serialize();
                       break;
                   default:
                       $this.addClass('loader');
                       data.data = form.off().serialize();
               }



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
                               if (id !== 'auth' && id !== 'auth_pass' && id !== 'resend_code' && id !== 'auth_check_code') {
                                   dataLayer.push({'event': 'formsend'});
                               }

                               //location.reload();
                               switch (id) {
                                   case 'auth_pass':
                                       location.reload();
                                       break;
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

                                       if (response.redirect_url) {
                                           verificationForm.prepend('<input type="hidden" name="REDIRECT_URL" value="'+response.redirect_url+'">');
                                       }
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

                                           //request to Google Analitic
                                           dataLayer.push({'event': 'sms-ok'});
                                           if ('redirect_url' in response && response.redirect_url.indexOf('basket') >= 0) {
                                               $('button[id="ORDER_CONFIRM_BUTTON"]').click();
                                               //submitForm('Y');
                                           } else {
                                               location.reload();
                                           }
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
                                   case 'auth_pass':
                                       if (typeof response.message !== 'undefined' && typeof response.message === 'object') {
                                           $.each(response.message, function (code, text) {
                                               if (code === 'MAIN') {
                                                   $('.modal-error').addClass('error').html(text);
                                               } else {
                                                   let field = $('[name="'+code+'"]');
                                                   field.addClass('error');
                                               }


                                           });
                                       }
                                       break;
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

                                           //request to Google Analitic
                                           dataLayer.push({'event': 'sms-error'});

                                       }
                                       break;
                                   case 'subscribe':
                                       if (typeof response.message !== 'undefined') {
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
                let seconds = parseInt(time[2]);

                console.log(seconds);
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