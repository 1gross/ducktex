<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->SetTitle("Мои бонусы");?>

<?$APPLICATION->IncludeComponent("logictim:bonus.history", "personal", Array(
	"COMPONENT_TEMPLATE" => "personal",
		"FIELDS" => array(	// Какие поля показывать
			0 => "ID",
			1 => "DATE",
			2 => "NAME",
			3 => "OPERATION_SUM",
			4 => "BALLANCE_BEFORE",
			5 => "BALLANCE_AFTER",
			6 => "ADD_DETAIL",
		),
		"SORT" => "DESC",	// Количество операций на странице
		"ORDER_LINK" => "Y",	// Показывать ссылку на заказ по которому совершена операция
		"ORDER_URL" => "/personal/order/",	// url страницы с комонентом информации о заказах
		"PAGE_NAVIG_LIST" => "30",	// Количество операций на странице
		"PAGE_NAVIG_TEMP" => "arrows",	// Шаблон постраничной навигации
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>