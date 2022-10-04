<?php
/**
 * Модель для таблицы продукции (products)
 */

/**
 * Получаем последние добавленные товары
 * 
 * @param integer $limit Лимит товаров
 * @return array Массив товаров
 */
function getLastProducts($limit = null) {
    $sql = "SELECT * FROM `products` ORDER BY `id` DESC";
    if($limit) {
        $sql .= " LIMIT {$limit}";
    }
    $rs = mysql_query($sql);
    return createSmartyRsArray($rs);
}

/**
 * получаем товары по id категории
 * 
 * @param integer $itemId id категории
 * @return array массив продкутов
 */
function getProductsByCat($itemId) {
    $itemId = intval($itemId);
    $sql = "SELECT * FROM products WHERE category_id = '{$itemId}'";
    $rs = mysql_query($sql);
    return createSmartyRsArray($rs);
}

/**
 * получение данных продукта по id
 * 
 * @param integer $itemId id продукта
 * @return array массив данных продукта
 */
function getProductById($itemId) {
    $itemId = intval($itemId);
    $sql = "SELECT * FROM products WHERE id = '{$itemId}'";
    $rs = mysql_query($sql);
    return mysql_fetch_assoc($rs);
}

/**
 * получить массив продуктов из массива идентификаторов 
 * 
 * @param array $itemIds массив идентификаторов
 * @return array массив продуктов
 */
function getProductsFromArray($itemIds) {
    $strIds = implode($itemIds, ', ');
    $sql = "SELECT * FROM products WHERE id in({$strIds})";
    $rs = mysql_query($sql);
    return createSmartyRsArray($rs);
}