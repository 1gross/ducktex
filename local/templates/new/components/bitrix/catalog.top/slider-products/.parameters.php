<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    "BLOCK_TITLE" => Array(
        "NAME" => Loc::getMessage("BLOCK_TITLE_NAME"),
        "TYPE" => "STRING",
        "DEFAULT" => Loc::getMessage('BLOCK_TITLE_DEFAULT'),
    )
);