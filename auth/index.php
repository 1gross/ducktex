<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<?$APPLICATION->IncludeFile(SITE_DIR."include/auth_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("AUTH_INCLUDE_AREA"), ));?>
<?/*if(!$USER->IsAuthorized()){

}elseif( !empty( $_REQUEST["backurl"] ) ){
	LocalRedirect( $_REQUEST["backurl"] );
}else{
	LocalRedirect(SITE_DIR.'personal/');
}*/?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"mshop",
	Array(
		"REGISTER_URL" => SITE_DIR."auth/registration/",
		"PROFILE_URL" => SITE_DIR."auth/forgot-password/",
		"SHOW_ERRORS" => "Y"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>