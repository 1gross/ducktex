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
                dump($arUser['UF_FAVORITES']);
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }
                dump($arElements);
                dump($USER->Update($USER->GetID(), Array("UF_FAVORITES"=>$arElements )));
            }
            $arResponse['result'] = true;
            break;
    }
    echo json_encode($arResponse);
    die();
}