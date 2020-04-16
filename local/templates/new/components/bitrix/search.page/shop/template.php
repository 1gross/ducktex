<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?//dump($arResult)?>
<div class="page search-page">
    <div class="wrapper">
        <h1><?=$APPLICATION->GetTitle(false)?></h1>
        <form action="<?=$APPLICATION->GetCurPage()?>" class="search-input">
            <input type="text" name="q" value="<?=$_REQUEST['q'] ?: ''?>" placeholder="Поиск по сайту">
            <input type="submit" class="btn blue small" value="Найти">
        </form>
        <div class="search-block">
            <?if ($arResult['SEARCH']) {
                $arProducts = array();
                foreach ($arResult['SEARCH'] as $arItem) {
                    $arProducts[$arItem['ID']] = $arItem['ID'];
                }
                ?>
                <div class="search-header">
                    <div class="filter">
                        <a href="/" class="filter-item">По популярности</a>
                        <a href="/" class="filter-item desc">По алфавиту</a>
                        <a href="/" class="filter-item">По цене</a>
                    </div>
                </div>
                <?
                $GLOBALS['arrSearchFilter'] = array('ID' => array_keys($arProducts));
                $APPLICATION->IncludeComponent(
                        'bitrix:catalog.top',
                        'search',
                        array(
                                'FILTER_NAME' => 'arrSearchFilter',
                            'IBLOCK_ID' => 13
                        )
                )?>
            <?} elseif ((isset($_REQUEST['q']) && strlen($_REQUEST['q']) > 0) || $arResult['ERROR_TEXT']) {?>
                <div class="block-message"><?=$arResult['ERROR_TEXT'] ?: 'К сожалению ничего не найдено'?></div>
            <?}?>
        </div>
    </div>
</div>
