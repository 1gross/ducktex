<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->SetTitle("Мои бонусы");?>

<?$APPLICATION->IncludeComponent(
	"logictim:bonus.history", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"FIELDS" => array(
			0 => "ID",
			1 => "DATE",
			2 => "NAME",
			3 => "OPERATION_SUM",
			4 => "BALLANCE_BEFORE",
			5 => "BALLANCE_AFTER",
			6 => "ADD_DETAIL",
		),
		"SORT" => "ASC",
		"ORDER_LINK" => "Y",
		"ORDER_URL" => "/personal/order/",
		"PAGE_NAVIG_LIST" => "10",
		"PAGE_NAVIG_TEMP" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>