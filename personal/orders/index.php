<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->SetTitle("Мои заказы");
$_REQUEST['filter_history'] = 'Y';
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.list", 
	"new", 
	array(
		"COMPONENT_TEMPLATE" => "new",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"PATH_TO_DETAIL" => "",
		"PATH_TO_COPY" => "",
		"PATH_TO_CANCEL" => "",
		"PATH_TO_PAYMENT" => "payment.php",
		"PATH_TO_BASKET" => "",
		"PATH_TO_CATALOG" => "/catalog/",
		"ORDERS_PER_PAGE" => "10",
		"ID" => $ID,
		"DISALLOW_CANCEL" => "N",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"NAV_TEMPLATE" => "",
		"HISTORIC_STATUSES" => array(
			0 => "F",
			1 => "N",
			2 => "P",
		),
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"REFRESH_PRICES" => "N",
		"DEFAULT_SORT" => "DATE_INSERT"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
