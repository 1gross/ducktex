$(document).ready(function () {
   let tabItem = $('.js-init-tab');

   tabItem.on('click', function () {
       let typeId = $(this).attr('data-id');

       $(this).closest('.tab').find('.tabs_content').each(function () {
           let id = $(this).attr('data-type-id');

           $(this).removeClass('active');
           if (id === typeId) {
               $(this).addClass('active');
           }
       });
   });
});
$('.mask-date').mask('99.99.9999');
$(document).on('click', '.js-init-show-pass', function () {
   if ($(this).hasClass('hide-field')) {
       $(this).removeClass('hide-field').addClass('show-field');
       $(this).closest('div').find('input').prop('type', 'text');
   } else {
       $(this).removeClass('show-field').addClass('hide-field');
       $(this).closest('div').find('input').prop('type', 'password');
   }
});