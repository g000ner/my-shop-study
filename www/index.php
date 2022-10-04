<?php
//стартуем сессию и если в ней нет мссива козины - создаем его
session_start();
if(! isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

include_once '../config/config.php'; //инициализация настроек
include_once '../config/db.php';     //инициализация базы данных
include_once '../library/mainFunctions.php'; //основные функции


//определяем, с каким контроллером будем работать
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) : "Index";

//определяем, с какой функцией будем работать
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// если в сессии есть данные об атворизованном пользователе, то добавляем их в шаблон
if(isset($_SESSION['user'])) {
    $smarty->assign('arUser', $_SESSION['user']);
}

//инициализируем переменную шаблонизатора, хранящую количество элементов в корзине
$smarty->assign('cartCntItems', count($_SESSION['cart']));

loadPage($smarty, $controllerName, $actionName);
