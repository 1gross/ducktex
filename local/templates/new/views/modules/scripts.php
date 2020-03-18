<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Page\Asset;

CJSCore::Init('jquery2');
Asset::getInstance()->addJs('/local/front/files/js/map.js');
Asset::getInstance()->addJs('/local/front/files/slick/slick.min.js');