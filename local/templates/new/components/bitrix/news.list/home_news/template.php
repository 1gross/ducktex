<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<?if ($arResult['ITEMS']) {?>
<section class="block news">
    <div class="wrapper">
        <div class="news-block">
            <div class="header">
                <h2><?=Loc::getMessage('BLOCK_NEWS_TITLE')?></h2>
                <a href="<?=SITE_DIR?>news/" class="link"><?=Loc::getMessage('ALL_NEWS_LINK')?></a>
            </div>
            <div class="news-wrap">
                <?foreach ($arResult['ITEMS'] as $arItem) {?>
                <article class="news-item">

                    <div class="image" style="background-image: url(<?=$arItem['PREVIEW_PICTURE']['SRC'] ?: '/local/front/files/img/product-bg.jpg'?>);"></div>

                    <div class="info">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="title">
                            <?=$arItem['NAME']?>
                        </a>
                        <div class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
                    </div>
                </article>
                <?}?>
            </div>
        </div>
    </div>
</section>
<?}?>