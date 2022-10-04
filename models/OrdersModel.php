<?php
/**
 * модель для табоицы заказов (orders)
 * 
 */

/**
 * создать новый заказ
 * 
 * @param string $name имя пользователя
 * @param string $phone телефон
 * @param string $adress адрес
 * 
 * @return integer id заказа
 */
function makeNewOrder($name, $phone, $adress) {
    $userId = $_SESSION['user']['id'];
    $comment = "id пользователя: {$userId} </br>"
            . "Имя : {$name} </br>"
            . "Телефон : {$phone} </br>"
            . "Адрес : {$adress}";
            
    $dateCreated = date("Y.m.d H:i:s");
    $userIp = $_SERVER['REMOTE_ADDR'];
    
    $sql = "INSERT INTO orders (`user_id`, `date_created`, `date_payment`, `status`, `comment`, `user_ip`) VALUES ('{$userId}', '{$dateCreated}', null, '0', '{$comment}', '{$userIp}')";
    
    $rs = mysql_query($sql);
    
    if($rs) {
        $sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";
        $rs = mysql_query($sql);
        $rs = createSmartyRsArray($rs);
        
        if(isset($rs[0])) {
            return $rs[0]['id'];
        }
    }
    
    return false;
}

/**
 * Получить заказы пользователя с id с продуктами
 * 
 * @param integer $userId id пользователя
 * @return array массив заказов с привязкой к товарам
 */
function getOrdersWithProductsByUser($userId) {
    $userId = intval($userId);
    $sql = "SELECT * FROM orders WHERE user_id  = '{$userId}' ORDER BY id DESC";
    $rs = mysql_query($sql);
    
    $smartyRs = array();
    while ($row = mysql_fetch_assoc($rs)) {
        $rsChildren = getPurchaseForOrder($row['id']); // получаем покупки по заказу
        if($rsChildren) { // если есть покупки, то сохраняем их в массив
            $row['children'] = $rsChildren;
            $smartyRs[] = $row;
        }
    }
    
    return $smartyRs;
}