<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=7586449d-5a86-43b5-8f8a-61552f466d81');
Asset::getInstance()->addJs('/local/front/files/js/map.js');
