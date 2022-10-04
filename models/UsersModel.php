<?php
/**
 * модель для работы с таблицей пользователей(users)
 */

/**
 * регистрация нового пользователя
 * 
 * @param type $email email
 * @param type $pwdMD5 пароль, зашифрованный в MD5
 * @param type $name имя пользователя
 * @param type $phome телефон
 * @param type $adress адрес
 * @return array массив данных пользователя
 */
function registerNewUser($email, $pwdMD5, $name, $phone, $adress) {
    $email  = htmlspecialchars(mysql_real_escape_string($email));
    $name   = htmlspecialchars(mysql_real_escape_string($name));
    $phone  = htmlspecialchars(mysql_real_escape_string($phone));
    $adress = htmlspecialchars(mysql_real_escape_string($adress));
    $sql = "INSERT INTO users (`email`, `pwd`, `name`, `phone`, `adress`) VALUES "
            . "('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$adress}')";
    $rs = mysql_query($sql);
    if($rs) {
        $sql = "SELECT * FROM users WHERE (`email` = '{$email}' and `pwd` = '{$pwdMD5}') LIMIT 1";
        $rs = mysql_query($sql);
        $rs = createSmartyRsArray($rs);
        if(isset($rs[0]))
            $rs['success'] = 1;
        else
            $rs['success'] = 0;
    } else {
        $rs['success'] = 0;
    }
    return $rs;
}

/**
 * проверка необходимых для регистрации данных
 * 
 * @param string $email email
 * @param string $pwd1 пароль
 * @param string $pwd2 повтор пароля
 * @return array массив в ошибками или null в случае успеха
 */
function checkRegisterParams($email, $pwd1, $pwd2) {
    $res = null;
    if(! $email) {
        $res['success'] = 0;
        $res['message'] = 'Введите email';
    }
    
    if(! $pwd1) {
        $res['success'] = 0;
        $res['message'] = 'Введите пароль';
    }
    
    if(! $pwd2) {
        $res['success'] = 0;
        $res['message'] = 'Введите повтор пароля';
    }
    
    if($pwd1 != $pwd2) {
        $res['success'] = 0;
        $res['message'] = 'Введенные пароли не совпадают';
    }
    
    return $res;
}

/**
 * проверка на сущствование пользователя с email
 * 
 * @param string $email email
 * @return array массив - строка из БД с данным email, либо пустой массив(пользователя такого нет)
 */
function checkUserEmail($email) {
    $email = mysql_real_escape_string($email);
    $sql = "SELECT id FROM users WHERE email = {$email}";
    $rs = mysql_query($sql);
    $rs = createSmartyRsArray($rs);
    return $rs;
}


/**
 * Атворизация пользователя
 * 
 * @param string $email почта(логин)
 * @param string $pwd пароль
 * @return array массив данных пользователя
 */
function loginUser($email, $pwd) {
    $email = htmlspecialchars(mysql_real_escape_string($email));
    $pwd   = md5($pwd);
    
    $sql = "SELECT * FROM users WHERE (`email` = '{$email}' and `pwd` = '{$pwd}') LIMIT 1";
    
    $rs = mysql_query($sql);
    
    $rs = createSmartyRsArray($rs);
    //d($rs);
    if(isset($rs[0])) {
        $rs['success'] = 1;
    }else {
        $rs['success'] = 0;
    }
    
    return $rs;
}

/**
 * Обновление данных пользователя
 * 
 * @param string $name имя 
 * @param string $phone телефон
 * @param string $adress адрес
 * @param string $pwd1 новый пароль
 * @param string $pwd2 повтор нового пароля
 * @param string $curPwd текущий пароль
 * 
 * @return boolean TRUE в случае успеха
 */
function updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwd) {
    $email = htmlspecialchars(mysql_real_escape_string($_SESSION['user']['email']));
    $name = htmlspecialchars(mysql_real_escape_string($name));
    $phone = htmlspecialchars(mysql_real_escape_string($phone));
    $adress = htmlspecialchars(mysql_real_escape_string($adress));
    
    $pwd1 = trim($pwd1);
    $pwd2 = trim($pwd2);
    
    $newPwd = null;
    if($pwd1 && ($pwd1 == $pwd2)) {
        $newPwd = md5($pwd1);
    }
    //d($newPwd, 0);
    $sql = "UPDATE users SET ";
    if($newPwd) {
        $sql .= "`pwd` = '{$newPwd}', ";
    }
    
    $sql .= "`name` = '{$name}', "
        . "`phone` = '{$phone}', "
        . "`adress` = '{$adress}' "
        . "WHERE `email` = '{$email}' AND `pwd` = '{$curPwd}' LIMIT 1";
        
    $rs = mysql_query($sql);
    
    return $rs;
}

/**
 * Получить данные заказа текущего пользователя
 * @return array массив заказов с ривязкой к товарам
 */
function getCurUserOrders() {
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
    $rs = getOrdersWithProductsByUser($userId);
    
    return $rs;
}