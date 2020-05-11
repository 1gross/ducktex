<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

?>
<?if ($arResult['SECTIONS']) {?>
<div class="catalog-block with-description" style="margin-bottom: 20px;">
    <?foreach ($arResult['SECTIONS'] as $arItem) {?>
        <div class="catalog-item">
            <div class="catalog-item-image">
                <a href="<?=$arItem['SECTION_PAGE_URL']?>">
                    <img src="<?=$arItem['PICTURE']['SRC'] ?: '/local/front/files/img/no_image_sub_catalog.png'?>" alt="<?=$arItem['NAME']?>">
                </a>
            </div>
            <div class="catalog-item-list">
                <a href="<?=$arItem['SECTION_PAGE_URL']?>" class="catalog-item-link main"><?=$arItem['NAME']?> <?if ($arItem['ELEMENT_CNT']) {?><span>(<?=$arItem['ELEMENT_CNT']?>)</span><?}?></a>
                <?if ($arItem['DESCRIPTION']) {?>
                    <div class="description">
                        <?=$arItem['DESCRIPTION']?>
                    </div>
                <?}?>
            </div>
        </div>
    <?}?>
</div>
<?}?>