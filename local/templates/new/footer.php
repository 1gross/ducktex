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
    <?Asset::getInstance()->addCss("/local/front/files/css/main.css");?>
    <?Asset::getInstance()->addCss("/local/front/files/slick/slick.css");?>
    <?$APPLICATION->ShowMeta("viewport");?>
    <?$APPLICATION->ShowMeta("HandheldFriendly");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
    <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
    <?$APPLICATION->ShowHead();?>
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
</main>
</body>
</html>