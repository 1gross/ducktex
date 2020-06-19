<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Page\Asset;

CJSCore::Init('jquery2');

Asset::getInstance()->addJs($APPLICATION->GetTemplatePath('public/js/arcticmodal/jquery.arcticmodal-0.3.min.js'));

Asset::getInstance()->addJs('/local/front/files/slick/slick.min.js');
Asset::getInstance()->addJs('/local/front/files/fancybox/jquery.fancybox.min.js');

Asset::getInstance()->addJs('/local/front/files/js/main.js');

?>

<?
Asset::getInstance()->addJs('/local/front/files/js/app.js');

?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-54ZPSNW');</script>
<!-- End Google Tag Manager -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-54ZPSNW"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GTM-54ZPSNW"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'GTM-54ZPSNW');
</script>
