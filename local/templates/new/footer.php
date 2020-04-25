<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Page\Asset;

$pageContent = ob_get_clean();
$pageContent = trim(implode("", $APPLICATION->buffer_content)) . $pageContent;
$APPLICATION->RestartBuffer();
ob_end_clean();

if (function_exists("getmoduleevents")) {
    foreach (GetModuleEvents("main", "OnLayoutRender", true) as $arEvent) {
        ExecuteModuleEventEx($arEvent);
    }
}

$pageLayout = $APPLICATION->GetCurPage(false) == SITE_DIR ? 'home' : $APPLICATION->GetPageProperty("PAGE_LAYOUT", AppGetCascadeDirProperties("PAGE_LAYOUT", "column1"));
//require_once 'modules/settings.php';
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?$APPLICATION->ShowTitle()?></title>
    <?Asset::getInstance()->addCss('https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap&subset=cyrillic');?>
    <?Asset::getInstance()->addCss("/local/front/files/css/normalize.css");?>
    <?Asset::getInstance()->addCss("/local/front/files/fancybox/jquery.fancybox.min.css");?>
    <?Asset::getInstance()->addCss("/local/front/files/slick/slick.css");?>
    <?Asset::getInstance()->addCss($APPLICATION->GetTemplatePath('public/js/arcticmodal/jquery.arcticmodal-0.3.css'));?>
    <?Asset::getInstance()->addCss("/local/front/files/css/main.css");?>
    <?Asset::getInstance()->addCss($APPLICATION->GetTemplatePath('public/css/custom.css'));?>
    <?$APPLICATION->ShowMeta("HandheldFriendly");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
    <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
    <?$APPLICATION->ShowHead();?>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta name="yandex-verification" content="d923aaa6c6e8d5a7" />
    <meta name="google-site-verification" content="dAnFTXc0P1IVla1P3WjOpwtNVK3gq84OjV0gQK3vXqc" />
</head>
<body>


<main>
    <?$APPLICATION->ShowPanel();?>
    <?
    //include header
    $APPLICATION->IncludeFile(
        "views/modules/header.php",
        array(),
        array(
            "SHOW_BORDER" => false,
            "MODE" => "php"
        )
    );?>
    <?
    //include layout
    $APPLICATION->IncludeFile(
        "views/layouts/".$pageLayout.".php",
        array(
            "CONTENT" => $pageContent
        ),
        array(
            "SHOW_BORDER" => false,
            "MODE" => "php"
        )
    );?>
    <?
    //include header
    $APPLICATION->IncludeFile(
        "views/modules/footer.php",
        array(),
        array(
            "SHOW_BORDER" => false,
            "MODE" => "php"
        )
    );?>
    <?
    //include scripts
    $APPLICATION->IncludeFile(
        "views/modules/scripts.php",
        array(),
        array(
            "SHOW_BORDER" => false,
            "MODE" => "php"
        )
    );?>
    <?
    //include modals
    $APPLICATION->IncludeFile(
        "views/modules/modal.php",
        array(),
        array(
            "SHOW_BORDER" => false,
            "MODE" => "php"
        )
    );?>
</main>
</body>
</html>