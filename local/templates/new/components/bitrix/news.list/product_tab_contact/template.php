<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="map-block">
    <div class="data">
        <?if ($arResult['ITEMS'][0]['PROPERTIES']['HOME_BLOCK_TITLE']['VALUE']) {?>
            <h2 class="title"><?=$arResult['ITEMS'][0]['PROPERTIES']['HOME_BLOCK_TITLE']['VALUE']?></h2>
        <?}?>
        <?if ($arResult['ITEMS'][0]['PROPERTIES']['ADDRESS']['VALUE']) {?>
            <div class="street"><?=$arResult['ITEMS'][0]['PROPERTIES']['ADDRESS']['VALUE']?></div>
        <?}?>
        <?if ($arResult['ITEMS'][0]['PROPERTIES']['SCHEDULE']['VALUE']) {?>
            <div class="work-times"><?=htmlspecialchars_decode($arResult['ITEMS'][0]['PROPERTIES']['SCHEDULE']['VALUE']['TEXT'])?></div>
        <?}?>
    </div>
    <div id="address"></div>
</div>