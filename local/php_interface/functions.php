<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL;

function AppGetCascadeDirProperties($PROPERTY_ID, $default_value = false)
{
    global $APPLICATION;
    $pathMap = explode("/", trim(substr($APPLICATION->GetCurDir(), strlen(SITE_DIR)), "/"));
    do {
        $path = SITE_DIR . implode("/", $pathMap);
        $propertyValue = $APPLICATION->GetDirProperty($PROPERTY_ID, $path, false);
        if ($propertyValue !== false) {
            break;
        }
        array_pop($pathMap);
    }
    while (!empty($pathMap));

    return $propertyValue === false ? $default_value : $propertyValue;
}

function GetPropertyForHlBlock($sTableName, $propertyXmlId)
{
    /**
     * Работаем только при условии, что
     *    - модуль highloadblock подключен
     *    - в описании присутствует таблица
     *    - есть заполненные значения
     */
    if ( Loader::IncludeModule('highloadblock') && !empty($sTableName) && !empty($propertyXmlId) )
    {
        /**
         * @var array Описание Highload-блока
         */
        $hlblock = HL\HighloadBlockTable::getRow([
            'filter' => [
                '=TABLE_NAME' => $sTableName
            ],
        ]);

        if ( $hlblock )
        {
            /**
             * Магия highload-блоков компилируем сущность, чтобы мы смогли с ней работать
             *
             */
            $entity      = HL\HighloadBlockTable::compileEntity( $hlblock );
            $entityClass = $entity->getDataClass();

            $arRecords = $entityClass::getList([
                'filter' => [
                    'UF_XML_ID' => $propertyXmlId
                ],
            ]);
            foreach ($arRecords as $record)
            {
                return $record;
            }
        }
    }
    return false;
}
function dump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}