<?php
/**
 * Модель для таблицы категорий (categories)
 */

/**
 * Получить дочерние категории для категории $catId
 * 
 * @param integer $catId
 * @return array массив дочерних категорий
 */
function getChildrenForCat($catId) {
    $sql = "SELECT * FROM categories WHERE parent_id = '{$catId}'";
    $rs = mysql_query($sql);
    return createSmartyRsArray($rs);
}

/**
 * Получение главных категорий с привязками дочерних
 * 
 * @return array массив категорий
 */
function getAllMainCatsWithChildren() {
    $sql = 'SELECT * FROM categories WHERE parent_id = 0';
    $rs = mysql_query($sql);
    $smartyRs = array();
    while($row = mysql_fetch_assoc($rs)) {
        $rsChildren = getChildrenForCat($row['id']);
        if($rsChildren) {
            $row['children'] = $rsChildren;
        }
        $smartyRs[] = $row;
    }
    return $smartyRs;
}

/**
 * получить массив категорий по данному id
 * 
 * @param integer $catId id категории
 * @return array массив категорий
 */
function getCatById($catId) {
    $catId = intval($catId);
    $sql = "SELECT * FROM categories WHERE id = '{$catId}'";
    $rs = mysql_query($sql);
    return mysql_fetch_assoc($rs);
}