<?php

/**
 * контроллер страницы товара(/product/i/)
 */

//подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
 * Формирование станицы продукта
 * 
 * @param object $smarty
 */
function indexAction($smarty) {
    $itemId = isset($_GET['id']) ? $_GET['id'] : null;
    if($itemId == null)
        exit();
    
    //получение данных продукта
    $rsProduct = getProductById($itemId);
    
    //получение всех категорий для отображения
    $rsCategories = getAllMainCatsWithChildren();
    
    $smarty->assign('itemInCart', 0);
    if(in_array($itemId, $_SESSION['cart'])) {
        $smarty->assign('itemInCart', 1);
    }
    $smarty->assign('pageTitle', $rsProduct['name']);
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProduct', $rsProduct);
    
    loadTemplate($smarty, 'header');
    loadTemplate($smarty, 'product');
    loadTemplate($smarty, 'footer');
}
    