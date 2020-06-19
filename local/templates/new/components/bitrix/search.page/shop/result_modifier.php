<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["TAGS_CHAIN"] = array();
if($arResult["REQUEST"]["~TAGS"])
{
	$res = array_unique(explode(",", $arResult["REQUEST"]["~TAGS"]));
	$url = array();
	foreach ($res as $key => $tags)
	{
		$tags = trim($tags);
		if(!empty($tags))
		{
			$url_without = $res;
			unset($url_without[$key]);
			$url[$tags] = $tags;
			$result = array(
				"TAG_NAME" => htmlspecialcharsex($tags),
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), array("tags")),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam((count($url_without) > 0 ? "tags=".urlencode(implode(",", $url_without)) : ""), array("tags")),
			);
			$arResult["TAGS_CHAIN"][] = $result;
		}
	}
}

if (isset($_REQUEST['q'])) {
    global $arrSearchFilter;
    $arrSearchFilter = array('?NAME' => $_REQUEST['q']);
}

if (isset($_GET["method"])) {
    $arParams["ELEMENT_SORT_ORDER"] = $_GET['method'] == 'desc' ? 'desc' : 'asc';
}
$isDefault = !isset($_GET["sort"]) || $_GET["sort"] == 'shows' ? true : false;

if (isset($_GET["sort"])) {
    switch ($_GET["sort"]) {
        case 'price':
            $arParams["ELEMENT_SORT_FIELD"] = 'SCALED_PRICE_1';
            break;
        case 'name':
            $arParams["ELEMENT_SORT_FIELD"] = 'name';
            break;
        default:
            $arParams["ELEMENT_SORT_FIELD"] = 'shows';
            $isDefault = true;
    }
}
?>