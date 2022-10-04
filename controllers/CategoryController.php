<?php
/**
 * Контроллер страницы категории (/category/)
 */

//подключение моделей
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
 * формирование страницы категории
 * 
 * @param object $smarty
 */
function indexAction($smarty) {
    $catId = isset($_GET['id']) ? $_GET['id'] : null;
    if(! $catId) 
        exit();
    $rsCategory = getCatById($catId);
    $rsChildCats = null;
    $rsProducts = null;
    
    //если это главная категория - показываем дочерние
    //иначе показываем товары
    if($rsCategory['parent_id'] == 0)
        $rsChildCats = getChildrenForCat($catId);
    else
        $rsProducts = getProductsByCat($catId);
    $rsCategories = getAllMainCatsWithChildren();
    $smarty->assign('pageTitle', "Товары категории " . $rsCategory['name']);
    
    $smarty->assign('rsCategory', $rsCategory);
    $smarty->assign('rsChildCats', $rsChildCats);
    $smarty->assign('rsProducts', $rsProducts);
    $smarty->assign('rsCategories', $rsCategories);
    
    loadTemplate($smarty, "header");
    loadTemplate($smarty, "category");
    loadTemplate($smarty, "footer");
}