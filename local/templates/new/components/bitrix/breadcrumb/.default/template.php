<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>

<?if (count($arResult) > 1) {?>
    <div class="breadcrumbs">
        <div class="wrapper">
            <div class="breadcrumbs-block">
                <?foreach ($arResult as $arItem) {?>
                    <div class="item">
                        <?if (strlen($arItem['LINK']) > 0) {?>
                            <a href="<?=$arItem['LINK']?>"><?=$arItem['TITLE']?></a>
                        <?} else {?>
                            <span><?=$arItem['TITLE']?></span>
                        <?}?>
                    </div>
                <?}?>
            </div>
        </div>
    </div>
<?}?>