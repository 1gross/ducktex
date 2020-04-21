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
    <?Asset::getInstance()->addCss("/local/front/files/slick/slick.css");?>
    <?Asset::getInstance()->addCss($APPLICATION->GetTemplatePath('public/js/arcticmodal/jquery.arcticmodal-0.3.css'));?>
    <?Asset::getInstance()->addCss("/local/front/files/css/main.css");?>
    <?Asset::getInstance()->addCss($APPLICATION->GetTemplatePath('public/css/custom.css'));?>
    <?$APPLICATION->ShowMeta("viewport");?>
    <?$APPLICATION->ShowMeta("HandheldFriendly");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
    <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
    <?$APPLICATION->ShowHead();?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-54ZPSNW');</script>
    <!-- End Google Tag Manager -->
    <meta name="yandex-verification" content="d923aaa6c6e8d5a7" />
    <meta name="google-site-verification" content="dAnFTXc0P1IVla1P3WjOpwtNVK3gq84OjV0gQK3vXqc" />
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-54ZPSNW"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

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