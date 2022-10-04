<?php

/**
 * контроллер для работы с корзиной (/cart/)
 */

//подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

/**
 * добавление продукта в корзину
 * 
 * @param integer id GET параметр - id добавляемого продукта
 * @return json информация об операции (успех, количество продуктов в корзине)
 */
function addtocartAction() {
    $itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
    //d($itemId);
    if(! $itemId)
        return false;
    $resData = array();
    
    //если значение не найдено, то добавляем
    if(isset($_SESSION['cart']) && array_search($itemId, $_SESSION['cart']) === false) {
        $_SESSION['cart'][] = $itemId;
        $resData['cntItems'] = count($_SESSION['cart']);
        $resData['success'] = 1;
    }else {
        $resData['success'] = 0;
    }

    echo json_encode($resData);   
}

/**
 * функция удаления товара из корзины
 * 
 * @param integer id GET параметр - id удаляемого продукта
 * @return json информация об операции (успех, количество продуктов в корзине) 
 */
function removefromcartAction() {
    $itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
    if(! $itemId)
        exit();
    $resData = array();
    $key = array_search($itemId, $_SESSION['cart']);
    if($key !== false) {
        unset($_SESSION['cart'][$key]);
        $resData['success'] = 1;
        $resData['cntItems'] = count($_SESSION['cart']);
    }else {
        $resData['success'] = 0;
    }
    echo json_encode($resData);
}

/**
 * формирование главной страницы корзины
 * 
 * @link /cart/
 * @param object $smarty
 */
function indexAction($smarty) {
    $itemIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $rsCategories = getAllMainCatsWithChildren();
    $rsProducts = getProductsFromArray($itemIds);
    
    $smarty->assign('pageTitle', 'Корзина');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);
    
    loadTemplate($smarty, 'header');
    loadTemplate($smarty, 'cart');
    loadTemplate($smarty, 'footer');
}

/**
 * Формирование страницы заказа
 */
function orderAction($smarty) {
    // получаем массив id продуктов корзины
    $itemsIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
    
    // если корзина пуста - редирект на страницу корзины
    if(! $itemsIds) {
        redirect('/cart/');
        return;
    }
    //d($_POST);
    // получаем из массива POST количество покупаемых товаров
    $itemsCnt = array();
    foreach ($itemsIds as $item) {
        // ключ для массива POST
        $postVar = 'itemCnt_' . $item;
        
        // создаем элемент массива количества покупаемых товаров
        // ключ - ID товара, значение - количество
        $itemsCnt[$item] = isset($_POST[$postVar]) ? $_POST[$postVar] : null;
    }
    
    // получаем список продуктов по массиву корзины
    $rsProducts = getProductsFromArray($itemsIds);
    //d($rsProducts, 0);
    //d($itemsCnt);
    $i = 0;
    foreach ($rsProducts as &$item) {
        $item['cnt'] = isset($itemsCnt[$item['id']]) ? $itemsCnt[$item['id']] : 0;
        if($item['cnt']) {
            // получаем стоимость
            $item['realPrice'] = $item['price'] * $item['cnt'];
        }else {
            // если товар есть, но его количество == 0, то удаляем
            unset($rsProducts[$i]);
        }
        $i++;
    }
    
    if(! $rsProducts) {
        echo "Корзина пуста";
        return;
    }
    
    // полученный массив пихаем в сессию
    $_SESSION['saleCart'] = $rsProducts;
    
    $rsCategories = getAllMainCatsWithChildren();
    
    // если пользоваетль не авторизовался, то показываем ему для этого поля, а авторизовался, то скрываем
    if(! isset($_SESSION['user'])) {
        $smarty->assign('hideLoginBox', 1);
    }
    
    $smarty->assign('pageTitle', 'Заказ');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);
    
    loadTemplate($smarty, "header");
    loadTemplate($smarty, "order");
    loadTemplate($smarty, "footer");
}

/**
 * AJAX - функция 
 * 
 * @param array $_SESSION['saleCart'] массив покупаемых продуктов
 * @return json информация о результате выполнения
 */
function saveorderAction() {
    $cart = isset($_SESSION['saleCart']) ? $_SESSION['saleCart'] : null;
    
    // если ничего в корзине нет
    if(! $cart) {
        $resData['success'] = 0;
        $resData['message'] = "Нет товаров для заказа";
        
        echo json_encode($resData);
        return;
    }
    
    $name   = isset($_POST['name'])? $_POST['name'] : null;
    $phone  = isset($_POST['phone'])? $_POST['phone'] : null;
    $adress = isset($_POST['adress'])? $_POST['adress'] : null;
    
    // создаем новый заказ и получаем его id
    $orderId = makeNewOrder($name, $phone, $adress);
    
    if(! $orderId) {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка создания заказа";
        echo json_encode($resData);
        return;
    }
    
    // сохраняем товары для созданного заказа
    $res = setPurchaseForOrder($orderId, $cart);
    
    if($res) {
        $resData['success'] = 1;
        $resData['message'] = "Заказ сохранен";
        unset($_SESSION['cart']);
        unset($_SESSION['saleCart']);
    }else {
        $resData['success'] = 0;
        $resData['message'] = 'Ошибка сохранения заказа № ' . $orderId;
    }
    
    echo json_encode($resData);
}