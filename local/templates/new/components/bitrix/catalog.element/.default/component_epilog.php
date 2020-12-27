<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use \Bitrix\Catalog\CatalogViewedProductTable as CatalogViewedProductTable;
CatalogViewedProductTable::refresh($arResult['ID'], CSaleBasket::GetBasketUserID());