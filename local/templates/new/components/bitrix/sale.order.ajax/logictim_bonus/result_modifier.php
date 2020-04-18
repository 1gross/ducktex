<?
$arUser = CUser::GetByID($USER->GetID())->Fetch();
if ($arUser['UF_TMP_USER'] == 'Y') {
    $us = new CUser();
    foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as &$arProperty) {
        if ($arProperty['IS_EMAIL'] == 'Y') {
            $arProperty['VALUE'] = $_REQUEST[$arProperty['FIELD_NAME']] ?: '';
            $arUserProp['EMAIL'] = $arProperty['VALUE'];
        }
    }
}

if ($arResult['BASKET_ITEMS']) {
    if (!$USER->IsAuthorized()) {
        $login = 'tmp_'.rand(1000000000, 9999999999).'@email'.rand(10, 99).'.com';
        $arResult = $USER->Register($login, "", "", "pass_".$login, "pass_".$login, $login);
        if ($arResult["ID"]) {
            $user = new CUser;
            $fields = Array(
                "UF_TMP_USER"  => "Y",
            );
            $user->Update($arResult["ID"], $fields);
            $USER->Authorize($arResult["ID"]);
        }
    }
}