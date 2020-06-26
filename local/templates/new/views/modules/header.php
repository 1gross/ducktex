<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\IO\File,
    Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs('/local/front/files/js/lib/jquery.inputmask.bundle.js');
Asset::getInstance()->addJs('/local/front/files/js/lib/inputmask.multi/js/jquery.inputmask-multi.min.js');
Asset::getInstance()->addJs('/local/front/files/js/phone_mask.js');
Asset::getInstance()->addCss('/local/front/files/css/flags.css');

Loc::loadMessages(__FILE__);

if (file_exists($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php")) {
    $phonePath = File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php");
    $phoneValue = '+' . preg_replace('/[^0-9]/', '', $phonePath);
}

$arBasket = B24TechSiteHelper::getBasket();
$arCompare = B24TechSiteHelper::getCompareList();
?>
<header id="header">
    <div class="top-line">
        <div class="wrapper">
            <div class="top-line-block">
                <a href="<?=SITE_DIR?>" class="logo-mob"><img src="/local/front/files/img/logo.png" alt=""></a>
                <a href="tel:<?= $phonePath ?: '' ?>"
                   class="phone"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/phone.php", [], ["MODE" => "text"]);?></a>
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
                <div class="user-buttons">
                    <?if (!$USER->IsAuthorized()) {?>
                        <button class="login modal-link js-init-action" data-action="show_modal" data-modal="#sign"><?=Loc::getMessage('SIGN_IN')?></button>
                    <?} else {?>
                        <a href="<?=SITE_DIR?>personal/" class="login"><?=Loc::getMessage('LK')?></a>
                    <?}?>
                       <!-- <a href="<?=SITE_DIR?>compare/" class="compare"><span><?=count($arCompare)?></span></a>-->
                    <?if (!$USER->IsAuthorized()) {?>
                        <button class="basket js-init-action" data-action="show_modal" data-modal="#sign_basket"><span><?=$arBasket['count_items']?></span></button>
                    <?} else {?>
                        <a href="<?=SITE_DIR?>basket/" class="basket"><span><?=$arBasket['count_items']?></span></a>
                    <?}?>
                    <form action="<?=SITE_DIR?>search/" class="search">
                        <div class="search-btn"></div>
                        <div class="search-input">
                            <input type="text" class="product_search--inp" name="q" id="product_search" placeholder="Поиск по сайту">
                            <input type="submit" value="Найти">
                        </div>
                    </form>
                </div>
                <button class="burger js-init-action" data-action="show_modal" data-modal="#mobile-menu"></button>
            </div>
        </div>
    </div>
    <div class="nav-line">
        <div class="wrapper">
            <div class="nav-line-block">
                <a href="<?=SITE_DIR?>" class="logo"><img src="/local/front/files/img/logo.png" alt=""></a>
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
            </div>
        </div>
    </div>
    <?if ($APPLICATION->GetCurPage(false) != SITE_DIR) {?>
        <?$APPLICATION->IncludeComponent(
                'bitrix:breadcrumb',
            '',
                array()
        )?>
    <?}?>
</header>
