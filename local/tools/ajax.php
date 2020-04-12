<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
define('STOP_STATISTICS', true);
define("NOT_CHECK_PERMISSIONS", true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('XHR_REQUEST', true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('sale');

if (isset($_REQUEST['action']) && strlen($_REQUEST['action']) > 0) {
    global $USER, $APPLICATION;

    $arResponse = array();
    switch ($_REQUEST['action']) {
        case 'add_compare':
            $iblock_id = 13;
            $isAdd = true;
            if(!empty($_SESSION["CATALOG_COMPARE_LIST"]) && !empty($_SESSION["CATALOG_COMPARE_LIST"][$iblock_id]) && array_key_exists($_REQUEST["id"], $_SESSION["CATALOG_COMPARE_LIST"][$iblock_id]["ITEMS"])){
                unset($_SESSION["CATALOG_COMPARE_LIST"][$iblock_id]["ITEMS"][$_REQUEST["id"]]);
                $isAdd = false;
            }
            else{
                $_SESSION["CATALOG_COMPARE_LIST"][$iblock_id]["ITEMS"][$_REQUEST["id"]] = CIBlockElement::GetByID($_REQUEST["id"])->Fetch();
            }
            $arResponse['result'] = true;
            $arResponse['compare_count'] = count($_SESSION["CATALOG_COMPARE_LIST"][$iblock_id]["ITEMS"]);
            $arResponse['is_add'] = $isAdd;
            break;
        case 'remove_basket':
            $products = B24TechSiteHelper::getBasket();

            $arResponse['result'] = CSaleBasket::Delete(intval($products['items'][$_REQUEST['id']]['ids']));
            break;
        case 'update_basket':

            $arResponse['result'] = true;
            break;
        case 'add_basket':
            $quantity = $_REQUEST['quantity'] ?: 0.1;
            $id = $_REQUEST['id'];
            $rs = Add2BasketByProductID($id, $quantity, array(), array());
            if ($rs) {
                $arResponse['result'] = true;
                $arResponse['basket'] = B24TechSiteHelper::getBasket();
            }
            break;
        case 'add_favorites':
            if(!$USER->IsAuthorized()) {
                $arElements = unserialize($APPLICATION->get_cookie('favorites'));
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }
                $APPLICATION->set_cookie("favorites", serialize($arElements));
            } else {
                $rsUser = CUser::GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();
                $arElements = $arUser['UF_FAVORITES'] ?: array();
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }

                $USER->Update($USER->GetID(), Array("UF_FAVORITES"=>$arElements));
            }
            $arResponse['result'] = true;
            break;
    }
    echo json_encode($arResponse);
    die();
}

