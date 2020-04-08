<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="pagination center">
    <?if ($arResult['NavPageNomer'] > 1) {?>
        <a href="<?=$arResult['sUrlPathParams']?>PAGEN_1=<?=$arResult['NavPageNomer'] - 1?>" class="prev"></a>
    <?}?>
    <div class="count"><?=$arResult['NavPageNomer']?>/<span><?=$arResult['NavPageCount']?></span></div>
    <?if ($arResult['NavPageNomer'] < $arResult['NavPageCount']) {?>
        <a href="<?=$arResult['sUrlPathParams']?>PAGEN_1=<?=$arResult['NavPageNomer'] + 1?>" class="next"></a>
    <?}?>
</div>

<?$this->SetViewTarget('items')?>
<?=$arResult['NavRecordCount']?>
<?$this->EndViewTarget()?>