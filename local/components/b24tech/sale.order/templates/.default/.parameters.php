<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Loader;
Loader::includeModule('sale');

$dbPerson = CSalePersonType::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array('ACTIVE' => 'Y'));
while ($arPerson = $dbPerson->GetNext())
{
    $arPers2Prop = array();

    $dbProp = CSaleOrderProps::GetList(
        array("SORT" => "ASC", "NAME" => "ASC"),
        array("PERSON_TYPE_ID" => $arPerson["ID"])
    );
    while ($arProp = $dbProp->Fetch())
    {

        if ($arProp["IS_LOCATION"] == 'Y')
        {
            if (intval($arProp["INPUT_FIELD_LOCATION"]) > 0)
                $altPropId = $arProp["INPUT_FIELD_LOCATION"];

            continue;
        }

        $arPers2Prop[$arProp["ID"]] = $arProp["NAME"];
    }

    if (isset($altPropId))
        unset($arPers2Prop[$altPropId]);

    if (!empty($arPers2Prop))
    {
        $arTemplateParameters["PROPS_FADE_LIST_".$arPerson["ID"]] =  array(
            "NAME" => 'Выводимые свойства ('.$arPerson["NAME"].')',
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arPers2Prop,
            "DEFAULT" => "",
            "COLS" => 25,
            "ADDITIONAL_VALUES" => "N",
            "PARENT" => "VISUAL"
        );
    }
}
unset($arPerson, $dbPerson);

$arTemplateParameters['SHOW_PARTICIPATE_BONUS'] = array(
    'NAME' => 'Выводить "Участовать в бонусной программе"',
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
);
