<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Page\Asset,
    Bitrix\Main\IO\File;

Asset::getInstance()->addJs('/local/front/files/js/jquery.mask.js');

if (file_exists($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php")) {
    $phonePath = File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php");
    $phoneValue = '+' . preg_replace('/[^0-9]/', '', $phonePath);
}

$arBasket = B24TechSiteHelper::getBasket();
$arCompare = B24TechSiteHelper::getCompareList();
?>


<?$APPLICATION->IncludeComponent('bitrix:system.auth.form', '', array())?>

<div style="display: none">
    <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-block">
            <button class="arcticmodal-close close"></button>
            <div class="user-buttons">
                <?if (!$USER->IsAuthorized()) {?>
                    <button class="login modal-link js-init-action" data-action="show_modal" data-modal="#sign">Войти</button>
                <?} else {?>
                    <a href="<?=SITE_DIR?>personal/" class="login">Личный кабинет</a>
                <?}?>
                <a href="<?=SITE_DIR?>compare/" class="compare"><span><?=count($arCompare)?></span></a>
                <a href="<?=SITE_DIR?>basket/" style="display: none;" class="basket"><span><?=$arBasket['count_items']?></span></a>
                <form action="<?=SITE_DIR?>search/" class="search">
                    <div class="search-btn"></div>
                    <div class="search-input">
                        <input type="text" class="product_search--inp-mobile" name="q" placeholder="Поиск по сайту">
                        <input type="submit" value="Найти">
                    </div>
                </form>
            </div>
            <nav class="menu">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ROOT_MENU_TYPE" => "top_general",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false
                );?>
            </nav>
            <nav class="main-menu">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ROOT_MENU_TYPE" => "top_catalog",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false
                );?>
            </nav>
            <div class="contacts">
                <a href="tel:<?=$phoneValue?>" class="phone"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/phone.php", [], ["MODE" => "text"]);?></a>
                <a href="mailto:<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/email.php", [], ["SHOW_BORDER" => false,"MODE" => "text"]);?>"
                   class="email"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/email.php", [], ["MODE" => "text"]);?></a>
                <a href="https://instagram.com/<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/instagram.php", [], ["SHOW_BORDER" => false,"MODE" => "text"]);?>"
                   class="instagram" target="_blank"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/instagram.php", [], ["MODE" => "html"]);?></a>
            </div>
        </div>

    </div>
</div>
