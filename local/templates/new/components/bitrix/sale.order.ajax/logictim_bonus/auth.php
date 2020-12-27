<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?CJSCore::Init('jquery')?>
<?$APPLICATION->IncludeComponent('bitrix:system.auth.form', '',  [
    'DESCRIPTION' => 'Чтобы мы корректно отобразили ваши предыдущие заказы и учли баллы на бонусный счет <strong>введите ваш номер телефона</strong>',
    'MODAL_ID' => 'sign_basket',
    'CLOSE_BUTTON' => false
])?>
<section style="height: calc(100vh - 628px);">
    <h3>Для продолжения работы с корзиной требуется авторизации или регистрация на сайте</h3>
</section>
<script>
    $(document).ready(function () {
        $('#sign_basket').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false
        });
    });

</script>