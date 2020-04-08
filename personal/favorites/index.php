<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->SetTitle("Избранное");
?>
<?
$APPLICATION->IncludeComponent('b24tech:catalog.favorites', '', array())
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
