<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<?if ($arResult['SECTIONS']) {?>
<section class="catalog">
    <div class="catalog-menu">
        <div class="wrapper">
            <div class="catalog-menu-block">
                <?foreach ($arResult['SECTIONS'] as $arItem) {?>
                    <a href="<?=$arItem['SECTION_PAGE_URL']?>" style="background-image: url('<?=$arItem['PICTURE']['SRC']?>');">
                        <span><?=$arItem['NAME']?></span>
                    </a>
                <?}?>
            </div>
        </div>
    </div>
    <div class="go-to-catalog">
        <div class="wrapper">
            <a href="<?=SITE_DIR?>catalog/" class="btn blue">
                <?=Loc::getMessage('CATALOG_MORE_LINK')?>
            </a>
        </div>
    </div>
</section>
<?}?>