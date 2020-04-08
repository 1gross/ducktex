<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Дактекс, Дакатекс, Dakteks, daktex, dukteks, duckteks, дуктекс");
$APPLICATION->SetTitle("Магазин тканей ducktex.ru");
?>
    <?$APPLICATION->IncludeComponent('bitrix:sale.order.ajax', '', array())?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>