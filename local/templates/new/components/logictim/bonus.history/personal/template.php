<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<div class="personal-wrap personal-bonus">
<h2><?=$APPLICATION->GetTitle()?></h2>
<div class="bonus-block">
    <div class="bonus-balanсe">
        <div class="amount">
            <?=Loc::getMessage('BALANCE_BONUS')?>: <span><?=$arResult['USER_BONUS'] ?: 0?> руб.</span>
        </div>
        <div class="status">
        <?if (isset($arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']])) {?>
            <?=Loc::getMessage('CURRENT_STATUS')?>: <span><?=$arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']]['NAME']?></span>, мы начисляем <span><?=$arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']]['PERCENT']?>%</span> бонусов за каждый заказ
        <?} else { ?>
            <? if ($arResult['PAID_SUM_MIN_ID'] > 0) { ?>
                До статуса <?= $arResult['PAID_STATUS'][$arResult['PAID_SUM_MIN_ID']]['NAME'] ?>, осталось заказать на сумму <?= $arResult['PAID_SUM_MIN'] - $arResult['SUM_ORDER_PRICE'] ?> руб.
            <? } ?>
        <?}?>
        </div>
    </div>
    <div class="bonus-line">
        <?foreach ($arResult['PAID_STATUS'] as $arItem) {?>
            <div class="bonus-item <?=$arItem['ACTIVE'] == 'Y' ? 'active' : ''?>" data-percent="<?=$arItem['PERCENT']?>%"><?=$arItem['SUM']?> р</div>
        <?}?>
    </div>
</div>
<div class="bonus-rules">
    <div class="title"><?=Loc::getMessage('STATUS_ITEM_INFO_TERMS')?></div>
    <?foreach ($arResult['PAID_STATUS'] as $arItem) {?>
        <div class="bonus-rules-item"><?=Loc::getMessage('STATUS_ITEM_INFO', $arItem)?></div>
    <?}?>

    <div class="bonus-rules-item"><?=Loc::getMessage('STATUS_INFO_HINT')?></div>
</div>
</div>
