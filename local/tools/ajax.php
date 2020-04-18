<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\UserPhoneAuthTable,
    Bitrix\Main\Controller\PhoneAuth;

define('STOP_STATISTICS', true);
define("NOT_CHECK_PERMISSIONS", true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('XHR_REQUEST', true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header("Access-Control-Allow-Origin: *");
CModule::IncludeModule('sale');

if (isset($_REQUEST['action']) && strlen($_REQUEST['action']) > 0) {
    global $USER, $APPLICATION;

    $arResponse = array();
    switch ($_REQUEST['action']) {
        case 'send_form':
            $isError = false;
            if (isset($_REQUEST['data'])) {
                $arFields = array();
                $arDataTemp = explode('&', $_REQUEST['data']);
                foreach ($arDataTemp as $arTemp) {
                    $arTemp = urldecode($arTemp);
                    list($code, $value) = explode('=', $arTemp);
                    if ($code == 'PHONE_NUMBER') {
                        $value = checkPhone(preg_replace('/[^0-9]/', '', $value));
                        if (empty($value) || strlen($value) != 12) {
                            $arResponse['message']['PHONE_NUMBER'] = 'Некорректный номер';
                            $arResponse['result'] = false;
                            $isError = true;
                        }

                    }
                    if (strpos($code, 'CODE') !== false) {
                        $arFields['CODE'][] = $value;
                    } else {
                        $arFields[$code] = $value;
                    }
                }
            }
            switch ($_REQUEST['id']) {
                case 'auth':
                    $rsUser = UserPhoneAuthTable::getList(
                        array(
                            "filter" => array(
                                "?PHONE_NUMBER" => $arFields['PHONE_NUMBER']
                            )
                        ));
                    $arUser = $rsUser->fetch();
                    if ($arUser['USER_ID']) {
                        list($code, $phoneNumber) = CUser::GeneratePhoneCode($arUser['USER_ID']);
                        $sms = new Bitrix\Main\Sms\Event(
                            'SMS_USER_CONFIRM_NUMBER',
                            [
                                "USER_PHONE" => $phoneNumber,
                                "CODE" => $code,
                            ]
                        );
                        $arResponse['code'] = $code;
                        $result = true;
                        //$result = $sms->send();
                        //if ($result->isSuccess()) {
                        if ($result) {
                            $arResponse['result'] = true;
                            $arResponse['phone'] = $phoneNumber;
                            $arResponse['send_sms'] = true;
                            $arResponse['sign_data'] = PhoneAuth::signData([
                                'phoneNumber' => $phoneNumber
                            ]);
                        } else {
                            $arResponse['false'] = false;
                            $arResponse['send_sms'] = false;
                            $arResponse['message'] = $result->getErrors();

                        }
                    }
                    break;
                case 'auth_check_code':

                    if (isset($arFields['SIGN_DATA']) && !empty($arFields['SIGN_DATA'])) {
                        $params = PhoneAuth::extractData($arFields['SIGN_DATA']);
                        $verificationCode = implode('', $arFields['CODE']);
                        if (strlen($verificationCode) == 6) {
                            $userId = CUser::VerifyPhoneCode($params['phoneNumber'], $verificationCode);
                            if ($userId) {
                                $USER->Authorize($userId);
                                $arResponse['result'] = true;

                            } else {
                                $arResponse['result'] = false;
                                $arResponse['message']['CODE'] = 'Неверный код';
                            }
                        } else {
                            $arResponse['result'] = false;
                            $arResponse['message']['CODE'] = 'Не корректная длина кода';
                        }
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message']['system'] = 'Ошибка: Отсутствует подпись';
                    }
                    break;
                case 'profile_edit':
                    $us = new CUser();
                    $result = $us->Update($USER->GetID(), $arFields);
                    if ($result) {
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message'] = $us->LAST_ERROR;
                        $arResponse['fields'] =  $arFields;
                        $arResponse['request'] =  $_REQUEST['data'];
                    }
                    break;
            }

            break;
        case 'clear_compare':
            if(empty($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID])) {
                $arResponse['result'] = false;
            } else {
                unset($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]);
                $arResponse['result'] = true;
            }
            break;
        case 'add_compare':
            $isAdd = true;
            if(!empty($_SESSION["CATALOG_COMPARE_LIST"]) && !empty($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]) && array_key_exists($_REQUEST["id"], $_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"])){
                unset($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"][$_REQUEST["id"]]);
                $isAdd = false;
            }
            else{
                $_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"][$_REQUEST["id"]] = CIBlockElement::GetByID($_REQUEST["id"])->Fetch();
            }
            $arResponse['result'] = true;
            $arResponse['count'] = count($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"]);
            $arResponse['isAdd'] = $isAdd;
            break;
        case 'remove_basket':
            $products = B24TechSiteHelper::getBasket();

            $arResponse['result'] = CSaleBasket::Delete(intval($products['items'][$_REQUEST['id']]['ids']));
            break;
        case 'update_basket':
            $products = B24TechSiteHelper::getBasket();

            $arFields = array(
                "QUANTITY" => $_REQUEST['quantity']
            );
            $result = CSaleBasket::Update(intval($products['items'][$_REQUEST['id']]['ids']), $arFields);
            if ($result) {
                $arResponse['result'] = true;
            } else {
                $arResponse['result'] = false;
                if($ex = $APPLICATION->GetException()) {
                    $arResponse['message'] = $ex->GetString();
                }
            }
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
            $isAdd = false;
            if(!$USER->IsAuthorized()) {
                $arElements = unserialize($APPLICATION->get_cookie('favorites'));
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                    $isAdd = true;
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
                    $isAdd = true;
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }

                $USER->Update($USER->GetID(), Array("UF_FAVORITES"=>$arElements));
            }
            $arResponse['count'] = count($arElements);
            $arResponse['result'] = true;
            $arResponse['isAdd'] = $isAdd;
            break;
        case 'clear_favorites':
            break;
    }
    echo json_encode($arResponse);
    die();
}

