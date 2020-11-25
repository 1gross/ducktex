<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->SetTitle("История операций");?>
<?
global $USER, $_arrFilter;
$_arrFilter = ['PROPERTY_USER' => $USER->GetID()];
$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"bonus_operations", 
	array(
		"COMPONENT_TEMPLATE" => "bonus_operations",
		"IBLOCK_TYPE" => "LOGICTIM_BONUS_STATISTIC",
		"IBLOCK_ID" => "31",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"USE_FILTER" => 'Y',
		"FILTER_NAME" => "_arrFilter",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "OPERATION_TYPE",
			1 => "OPERATION_SUM",
			2 => "USER",
			3 => "ORDER_ID",
			4 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>
<?/*$APPLICATION->IncludeComponent(
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
);*/?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>