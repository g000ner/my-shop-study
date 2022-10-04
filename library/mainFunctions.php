<?php
/**
 * основные функции
 */

/**
 *  формирование запрашиваемой страницы
 * 
 * @param string $controllerName имя контроллера
 * @param string $actionName имя выполняемой функции обработки страницы
 */
function loadPage($smarty, $controllerName, $actionName = 'index') {
    include_once PathPrefix . $controllerName . PathPostfix;
    $function = $actionName . 'Action';
    $function($smarty);
}

function loadTemplate($smarty, $templateName) {
    $smarty->display($templateName . TemplatePostfix);
}
/**
 * функция отладки отслеживае работу программы вывода значения переменной $value
 * 
 * @param variant $value  переменная для вывода ее на страницу
 */
function d($value = null, $die = 1) {
    echo 'Debug: <br/> <pre>';
    print_r($value);
    echo '</pre>';
    
    if($die) die;
}

/**
 * преобразование результата функции выборки в ассоциативный массив
 * 
 * @param recordset $rs
 * @return array $smartyRs
 */
function createSmartyRsArray($rs) {
    if(! $rs)
        return false;
    $smartyRs = array();
    while($row = mysql_fetch_assoc($rs)) {
        $smartyRs[] = $row;
    }
    return $smartyRs;
}

/**
 * 
 * @param string $url адрес для перенаправления
 */
function redirect($url) {
    if(! $url) $url = '/';
    header("Location: {$url}");
    exit();
}