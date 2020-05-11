<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

if (!$USER->IsAuthorized()) {
	dump(13432);
	LocalRedirect('/');
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:main.profile", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"SET_TITLE" => "N",
		"USER_PROPERTY" => array(
			0 => "UF_LOGICTIM_BONUS",
		),
		"SEND_INFO" => "N",
		"CHECK_RIGHTS" => "N"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
