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

$smsTestMode = false;

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
                    $userID = $arUser['USER_ID'];
                    if (!$userID) {
                        $arResult = $USER->Register(checkPhone($arFields['PHONE_NUMBER']),
                            "", "", "pass_" . $arFields['PHONE_NUMBER'],
                            "pass_" . $arFields['PHONE_NUMBER'], '', SITE_ID,
                            '', '', '', checkPhone($arFields['PHONE_NUMBER']));
                        $arResponse['add_new_user'] = true;
                        $userID = $arResult["ID"];
                    }
                    list($code, $phoneNumber) = CUser::GeneratePhoneCode($userID);
                    $us = new CUser();
                    $rs = $us->Update($userID, array('UF_HASHKEY' => md5(strval($code).strval($userID))));
                    $sms = new Bitrix\Main\Sms\Event(
                        'SMS_USER_CONFIRM_NUMBER',
                        [
                            "USER_PHONE" => $phoneNumber,
                            "CODE" => $code,
                        ]
                    );
                    $arResponse['code'] = $code;

                    if ($smsTestMode) {
                        $res = true;
                    } else {
                        $result = $sms->send();
                        $res = $result->isSuccess();
                    }

                    if ($res) {
                        $arResponse['result'] = true;
                        $arResponse['phone'] = $phoneNumber;
                        $arResponse['user_id'] = $userID;
                        $arResponse['send_sms'] = true;
                        $arResponse['sign_data'] = PhoneAuth::signData([
                            'phoneNumber' => $phoneNumber
                        ]);
                    } else {
                        $arResponse['false'] = false;
                        $arResponse['send_sms'] = false;
                        $arResponse['message'] = $result->getErrors();
                    }

                    break;
                case 'auth_check_code':


                    if (isset($arFields['SIGN_DATA']) && !empty($arFields['SIGN_DATA'])) {
                        $params = PhoneAuth::extractData($arFields['SIGN_DATA']);
                        $verificationCode = implode('', $arFields['CODE']);

                        if (strlen(trim($verificationCode)) == 6) {
                            $arUser = CUser::GetByID($arFields['USER_ID'])->Fetch();

                            if ($arUser['UF_HASHKEY'] == md5($verificationCode.$arFields['USER_ID'])) {
                                $us = new CUser();
                                $rs = $us->Update($arFields['USER_ID'], array('UF_HASHKEY' => ''));

                                //$userId = CUser::VerifyPhoneCode($params['phoneNumber'], $verificationCode);
                                if ($arFields['USER_ID']) {
                                    $USER->Authorize($arFields['USER_ID']);
                                    $arResponse['result'] = true;
                                }
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
                case 'resend_code':
                    if (isset($arFields['SIGN_DATA']) && !empty($arFields['SIGN_DATA']) && isset($arFields['USER_ID']) && !empty($arFields['USER_ID']) ) {
                        $params = PhoneAuth::extractData($arFields['SIGN_DATA']);

                        if (strlen(trim($params['phoneNumber'])) > 0) {

                            list($code, $phoneNumber) = CUser::GeneratePhoneCode($arFields['USER_ID']);
                            $us = new CUser();
                            $rs = $us->Update($userID, array('UF_HASHKEY' => md5(strval($code).strval($arFields['USER_ID']))));

                            $sms = new Bitrix\Main\Sms\Event(
                                'SMS_USER_CONFIRM_NUMBER',
                                [
                                    "USER_PHONE" => $phoneNumber,
                                    "CODE" => $code,
                                ]
                            );

                            if ($smsTestMode) {
                                $res = true;
                            } else {
                                $result = $sms->send();
                                $res = $result->isSuccess();
                            }

                            $arResponse['code'] = $code;

                            if ($res) {
                                $arResponse['result'] = true;
                                $arResponse['user_id'] = $arFields['USER_ID'];
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
                    } else {
                        $arResponse['false'] = false;
                    }
                    break;
                case 'profile_edit':
                    $arUserProps = array();
                    $arUserOrderProps = array();
                    foreach ($arFields as $CODE => $PROP) {
                        switch ($CODE) {
                            case 'PERSONAL_BIRTHDAY':
                            case 'UF_INSTAGRAM':
                                $arUserProps[$CODE] = $PROP;
                                break;
                            case 'FIO':
                                $arTmp = explode(' ', $PROP);
                                if (isset($arTmp[0])) {
                                    $arUserProps['LAST_NAME'] = $arTmp[0];
                                }
                                if (isset($arTmp[1])) {
                                    $arUserProps['NAME'] = $arTmp[1];
                                }
                                if (isset($arTmp[2])) {
                                    $arUserProps['SECOND_NAME'] = $arTmp[2];
                                }
                                $arUserOrderProps[$CODE] = $PROP;
                                break;
                            case 'PHONE':
                                if ($arFields['OLD_PHONE'] != $PROP) {
                                    $arUserProps['PHONE_NUMBER'] = $PROP;
                                    $arUserOrderProps[$CODE] = $PROP;
                                }
                                break;
                            case 'EMAIL':
                                $arUserOrderProps[$CODE] = $PROP;
                                $arUserProps[$CODE] = $PROP;
                                break;
                            case 'ADDRESS':
                                $arUserOrderProps[$CODE] = $PROP;
                                break;
                            case 'PERSON_TYPE_ID':
                                //$arUserOrderProps[$CODE] = $PROP;
                                break;
                        }
                    }

                    if (count($arUserProps) > 0) {
                        $us = new CUser();
                        $result = $us->Update($USER->GetID(), $arUserProps);
                    }
                    if (count($arUserOrderProps) > 0) {
                        $db_sales = CSaleOrderUserProps::GetList(
                            array(
                                'ID' => 'DESC'
                            ),
                            array(
                                "USER_ID" => $USER->GetID(),
                                "PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID']
                            )
                        );
                        $arUserProfile = $db_sales->Fetch();


                        if ($arUserProfile['ID']) {
                            $USER_PROPS_ID = $arUserProfile['ID'];
                        } else {
                            $arFields = array(
                                "NAME" => $arUserOrderProps['FIO'] ?: 'Профиль пользователя ID: '.$USER->GetID(),
                                "USER_ID" => $USER->GetID(),
                                "PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID']
                            );
                            $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);
                        }

                        if ($USER_PROPS_ID) {
                            foreach ($arUserOrderProps as $CODE => $VALUE) {
                                $rs = CSaleOrderUserPropsValue::GetList(
                                    array(),
                                    array(
                                        'USER_PROPS_ID' => $USER_PROPS_ID,
                                        "PROP_PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID'],
                                        'PROP_CODE' => $CODE
                                    ));
                                $PROP = $rs->Fetch();

                                if ($PROP['ID'] > 0) {
                                    if ($PROP['VALUE'] != $VALUE) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "ORDER_PROPS_ID" => $PROP['ORDER_PROPS_ID'],
                                            "NAME" => $PROP['NAME'],
                                            "VALUE" => $VALUE
                                        );

                                        CSaleOrderUserPropsValue::Update($PROP['ID'], $arFieldsProps);
                                    }

                                } else {
                                    $rs = CSaleOrderUserPropsValue::GetList(
                                        array(),
                                        array(
                                            "PROP_PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID'],
                                            'PROP_CODE' => $CODE
                                        ));
                                    $PROP = $rs->Fetch();

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "ORDER_PROPS_ID" => $PROP['ORDER_PROPS_ID'],
                                        "NAME" => $PROP['NAME'],
                                        "VALUE" => $VALUE
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                            }
                        }
                    }

                    if ($result) {
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message'] = $us->LAST_ERROR;
                        $arResponse['fields'] =  $arFields;
                        $arResponse['request'] =  $_REQUEST['data'];
                    }
                    break;
                case 'set_coupon':
                    if (CCatalogDiscountCoupon::SetCoupon($_REQUEST['coupon_code'])) {
                        CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message']['coupon_code'] = 'Error';
                    }

                    break;
                case 'subscribe':
                    if (check_email($_REQUEST['email'])) {
                        $list = array();
                        foreach (\Bitrix\Sender\Subscription::getMailingList(array('IS_PUBLIC' => 'Y')) as $l) {
                            $list[] = $l['ID'];
                        }
                        \Bitrix\Sender\Subscription::add($_REQUEST['email'], $list, SITE_ID);
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['message'] = 'Некорректный email';
                        $arResponse['result'] = false;
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

