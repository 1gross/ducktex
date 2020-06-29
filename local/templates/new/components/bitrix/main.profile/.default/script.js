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
