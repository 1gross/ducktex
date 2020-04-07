<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<section id="basket">
    <div class="wrapper">
        <h1><?$APPLICATION->ShowTitle()?></h1>
        <?$APPLICATION->IncludeComponent('bitrix:sale.basket.basket', '', array(
            'ALL_BONUS' => $arResult['BONUS']['ALL_BONUS']
        ))?>
        <form action="<?=POST_FORM_ACTION_URI?>" method="post" class="order-block">
            <?=bitrix_sessid_post()?>
            <input type="hidden" name="action" value="order">
            <div class="order-info">
                <h2><?=Loc::getMessage('ORDER_CHECKOUT_TITLE')?></h2>
                <div class="order-info-name">1. Ваши данные</div>
                <div class="order-info-item">
                    <div class="title">Выберите тип плательщика</div>
                    <div class="switch-box">
                        <input type="checkbox" class="switch" name="fields[person_type]">
                        <?foreach ($arResult['PERSON_TYPE'] as $arPersonType) {?>
                            <span class="<?=$arPersonType['ID'] == 1 ? 'first' : 'second'?>" data-person-type-id="<?=$arPersonType['ID']?>"><?=$arPersonType['NAME']?></span>
                        <?}?>
                    </div>
                    <?
                    $first = true;
                    foreach ($arResult['PERSON_TYPE'] as $personTypeId => $arPersonType) {?>
                        <div class="entity <?=$first ? 'active' : ''?>" data-person-type-id="<?=$arPersonType['ID']?>">
                            <?foreach ($arPersonType['PROPS'] as $prop) {
                                switch ($prop['TYPE']) {
                                    case 'TEXT':
                                    case 'LOCATION':
                                        ?>
                                        <div class="form-item">
                                            <input type="text"
                                                   name="props[<?= $prop['ID']?>]"
                                                   placeholder="<?= $prop['NAME'] ?><?= $prop['REQUIED'] == 'Y' ? '*' : '' ?>">
                                        </div>
                                        <?
                                        break;
                                    case 'TEXTAREA':
                                        ?>
                                        <div class="form-item">
                                            <textarea type="text"
                                                      name="props[<?=  $prop['ID'] ?>]"
                                                      placeholder="<?= $prop['NAME'] ?><?= $prop['REQUIED'] == 'Y' ? '*' : '' ?>"></textarea>
                                        </div>
                                        <?
                                        break;
                                }
                            }
                            ?>
                        </div>
                    <?
                        $first = false;
                    }?>
                    <?if ($arParams['SHOW_PARTICIPATE_BONUS'] == 'Y') {?>
                        <label class="checkbox-box">
                            <input type="checkbox" name="fields[participate_bonus]" checked="checked">
                            <span class="checkmark"></span>
                            Учавствовать в бонусной программе
                        </label>
                    <?}?>
                </div>

                <div class="order-info-name">2. Выберите способ оплаты</div>
                <?
                $first = true;
                foreach ($arResult['PERSON_TYPE'] as $personTypeId => $arPersonType) {?>
                    <div class="order-info-item order-block-select <?=$first ? 'active' : ''?>"
                         data-person-type-id="<?=$personTypeId?>">
                        <?
                        $isFirst = true;
                        foreach ($arPersonType['PAY_SYSTEMS'] as $ID => $PAY_SYSTEM) {?>
                            <label class="radio-box" data-pay-system-id="<?=$ID?>">
                                <input type="radio" name="fields[pay_systems]" value="<?=$ID?>" <?=$isFirst ? 'checked="checked"' : ''?>>
                                <span class="checkmark"></span>
                                <?=$PAY_SYSTEM['NAME']?>
                            </label>
                        <?
                            $isFirst = false;
                        }?>
                    </div>
                <?
                    $first = false;
                }?>

                <div class="order-info-name">3. Выберите способ получения</div>
                <div class="order-info-item order-block-select active">
                    <?foreach ($arResult['DELIVERY'] as $arItem) {?>
                        <label class="radio-box">
                            <input type="radio" name="fields[delivery]" value="<?=$arItem['ID']?>" checked="checked">
                            <span class="checkmark"></span>
                            <?=$arItem['NAME']?> (<button>выбрать место получения</button>)
                        </label>
                    <?}?>
                </div>
                <div class="order-info-item">
                    <div class="title">Комментарий к заказу</div>
                    <div class="form-item">
                        <textarea name="fields[order_description]" id="" cols="30" rows="10"></textarea>
                    </div>

                </div>
            </div>
            <div class="order-check">
                <div class="order-check-block">
                    <div class="title"><?=Loc::getMessage('ORDER_SIDEBAR_TITLE')?></div>
                    <div class="order-check-item">
                        <div class="order-check-title">Сумма заказа:</div>
                        <div class="order-check-sum"><?=$arResult['FULL_PRICE']?> руб.</div>
                    </div>
                    <?if ($arResult['DISCOUNT_PRICE']) {?>
                        <div class="order-check-item">
                            <div class="order-check-title">Сумма скидки:</div>
                            <div class="order-check-sum"><?=$arResult['DISCOUNT_PRICE']?> руб.</div>
                        </div>
                    <?}?>
                    <?if ($arResult['DELIVERY_PRICE']['PRICE'] > 0) {?>
                        <div class="order-check-item">
                            <div class="order-check-title">Доставка:</div>
                            <div class="order-check-sum">не выбрана</div>
                        </div>
                    <?}?>
                    <div class="order-check-item">
                        <div class="order-check-title"><?=Loc::getMessage('ORDER_PAID_BONUS')?>:</div>
                        <div class="order-check-sum">0 руб.</div>
                    </div>
                    <div class="order-check-footer">
                        <div class="order-check-item">
                            <div class="order-check-title"><?=Loc::getMessage('ORDER_SUM')?>:</div>
                            <div class="order-check-sum"><?=$arResult['PRICE']?> руб.</div>
                        </div>
                        <?if ($arResult['BONUS']['ALL_BONUS']) {?>
                            <div class="order-check-item small">
                                <div class="order-check-title"><?=Loc::getMessage('ORDER_ALL_BONUS')?>:</div>
                                <div class="order-check-sum"><?=$arResult['BONUS']['ALL_BONUS']?> руб.</div>
                            </div>
                        <?}?>
                        <button class="btn blue"><?=Loc::getMessage('BTN_ORDER')?></button>
                        <div class="politic"><?=Loc::getMessage('POLITIC_TEXT')?></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
