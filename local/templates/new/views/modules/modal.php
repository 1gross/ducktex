<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs('/local/front/files/js/jquery.mask.js')
?>


<?$APPLICATION->IncludeComponent('bitrix:system.auth.form', '', array())?>
