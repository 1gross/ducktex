$(document).ready(function () {
   $('form').submit(function (e) {
       e.preventDefault();
       $.ajax({
            url: window.location.href,
            data: $(this).serialize(),
            dataType: 'json',
           success: function (response) {
                if (response['result'] === false) {
                    $.each(response['message'], function (cd, props) {
                        $.each(props, function (id, text) {
                            $('[name="props['+id+']"]').parent().addClass('error');
                        });
                    });
                }
           }
       });
       return false;
   });
   let switchPersonType = $('.switch');

   switchPersonType.change(function () {
       let personTypeId = $(this).is(':checked') === true ? 2 : 1,
           orderBlockSelect = $('.order-block-select'),
           container = $(this).closest('.order-info-item');

       container.find('.active').each(function () {
           $(this).removeClass('active');
       });
       orderBlockSelect.each(function () {
          let dataTypeID = $(this).attr('data-person-type-id');

          if (parseInt(personTypeId) === parseInt(dataTypeID)) {
              $(this).addClass('active');
          } else {
              $(this).removeClass('active');
          }
       });
       $('.entity[data-person-type-id="'+personTypeId+'"]').addClass('active');
   });
});