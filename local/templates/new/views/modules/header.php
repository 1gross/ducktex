<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arBasket = B24TechSiteHelper::getBasket();
$arCompare = B24TechSiteHelper::getCompareList();
?>

<header id="header">
    <div class="top-line">
        <div class="wrapper">
            <div class="top-line-block">
                <a href="tel:<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/phone.php", [], ["SHOW_BORDER" => false,"MODE" => "text"]);?>"
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
                        <button class="login"><?=Loc::getMessage('SIGN_IN')?></button>
                    <?} else {?>
                        <a href="<?=SITE_DIR?>personal/" class="login"><?=Loc::getMessage('LK')?></a>
                    <?}?>
                    <a href="<?=SITE_DIR?>compare/" class="compare"><span><?=count($arCompare)?></span></a>
                    <a href="<?=SITE_DIR?>basket/" class="basket"><span><?=$arBasket['count_items']?></span></a>
                    <div class="search">
                        <button></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-line">
        <div class="wrapper">
            <div class="nav-line-block">
                <a href="<?=SITE_DIR?>" class="logo">
                    <img src="<?=SITE_DIR?>local/front/files/img/logo.png" alt="">
                </a>
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
</header>
