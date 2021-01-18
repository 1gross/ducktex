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
    <link rel="apple-touch-icon" sizes="57x57" href="/include/favicon_ducktex_57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/include/favicon_ducktex_72.png">

    <meta property="og:title" content="<?=$APPLICATION->GetTitle()?>">
    <meta property="og:type" content="article">

    <?if ($APPLICATION->GetViewContent('og:image')) {?>
        <meta property="og:image" content="<?=$APPLICATION->GetViewContent('og:image')?>">
    <?}else{?>
        <meta property="og:image" content="/local/front/files/img/ducktex_logo_link.jpg">
    <?}
    ?>

    <meta property="og:description" content="<?$APPLICATION->ShowProperty('description')?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="yandex-verification" content="d923aaa6c6e8d5a7" />
    <meta name="google-site-verification" content="dAnFTXc0P1IVla1P3WjOpwtNVK3gq84OjV0gQK3vXqc" />
	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(55442308, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/55442308" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</head>
<body>
<main>
<? if(!isset($_SESSION['ALERT_MES']) || $_SESSION['ALERT_MES'] !== 'true') :?> 
    
    <?
    $arFilter = array(
        'IBLOCK_ID' => 42, // выборка элементов из инфоблока Уведомления
        'ACTIVE' => 'Y',  // выборка только активных элементов
    );

    $res = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID','NAME','ACTIVE','PROPERTY_UV_TEXT'));

    // вывод элементов
    while ($element = $res->GetNext()) {?>
   

     
     <div id="header_alert">
         <div class="alert_box">
            <div class="wrapper">
                 <div class="alert_mes">
                    <p class="alert_text"><?=$element['PROPERTY_UV_TEXT_VALUE'];?></p>
                    <span class="alert_button close_icon"></span>
                </div>
            </div>
            
         </div>
     </div>  
       

        
    <?
     }
    ?>
<? endif;?>



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