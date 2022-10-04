/**
 * функция добавления товара в корзину
 * 
 * @param {integer} itemId id продукта
 * @returns в случае успеха обновятся данные на странице
 */
function addToCart(itemId) {
    console.log("js - addToCart()");
    console.log("/cart/addtocart/" + itemId + '/');
    $.ajax({
        type: 'POST',
        async: true,
        url: "/cart/addtocart/" + itemId + '/',
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                $('#cartCntItems').html(data['cntItems']);
                $('#addCart_' + itemId).hide();
                $('#removeCart_' + itemId).show();
            }
        }
    });
}

/**
 * функция удаления товара в корзину
 * 
 * @param {integer} itemId id продукта
 * @returns в случае успеха обновятся данные на странице
 */
function removeFromCart(itemId) {
    console.log("removeFromCart(" + itemId + ")");
    $.ajax({
        type: 'POST',
        async: true,
        url: "/cart/removefromcart/" + itemId + '/',
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                $('#cartCntItems').html(data['cntItems']);
                $('#removeCart_' + itemId).hide();
                $('#addCart_' + itemId).show();
            }
        }
    });
}

/**
 * функция подсчета стоимости товара
 * 
 * @param {integer} itemId id продукта
 */
function conversionPrice(itemId) {
    var newCnt = $('#itemCnt_' + itemId).val();
    var itemPrice = $('#itemPrice_' + itemId).attr('value');
    var itemRealPrice = newCnt * itemPrice;
    $('#itemRealPrice_' + itemId).html(itemRealPrice);
}

/**
 * регистрация пользователя
 */
function registerNewUser() {
    var postData = getData('#registerBox');
    
    $.ajax({
        type: 'POST',
        async: true,
        url: "/user/register/",
        data: postData,
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                alert("Регистрация прошла успешно");
                
                //> блок в левом столбце
                $('#userLink').attr('href', '/user/');
                $('#userLink').html(data['userName']);
                $('#userBox').show();
                
                $('#registerBox').hide();
                $('#loginBox').hide();
                $('#btnSaveOrder').show();
                //<
            }else {
                alert(data['message']);
            }
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus );
            console.log(jqXHR);
            console.log(errorThrown);
        }
    });
}

/**
 * Получение данных с формы
 */
function getData(obj_form) {
    var hData = {};
    $('input, textarea, select', obj_form).each(function() {
       if(this.name && this.name != '') {
           hData[this.name] = this.value;
           console.log('hData[' + this,name + '] = ' + hData[this.name]);
       } 
    });
    return hData;
}

/**
 * Авторизация пользователя
 */
function login() {
    var email = $('#loginEmail').val();
    var pwd   = $('#loginPwd').val();
    
    var postData = "email=" + email + "&pwd=" + pwd;
    
    $.ajax({
        type: 'POST',
        async: true,
        url: "/user/login/",
        data: postData,
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                $('#registerBox').hide();
                $('#loginBox').hide();
                
                $('#userLink').attr('href', '/user/');
                $('#userLink').html(data['displayName']);
                $('#userBox').show();

                $('#name').val(data['name']);
                $('#phone').val(data['phone']);
                $('#adress').val(data['adress']);

                $('#btnSaveOrder').show(); 
            }else {
                alert(data['message']);
            }
        }
    }); 
}

/**
 * Показать или спрятать блок регистрации
 */
function showRegisterBox() {
    if($('#registerBoxHidden').css('display') != 'block') {
        $('#registerBoxHidden').show();
    }else {
        $('#registerBoxHidden').hide();
    }
}

/**
 * Обновление данных пользователя
 * 
 */
function updateUserData() {
    var phone = $('#newPhone').val();
    var adress = $('#newAdress').val();
    var pwd1 = $('#newPwd1').val();
    var pwd2 = $('#newPwd2').val();
    var curPwd = $('#curPwd').val();
    var name = $('#newName').val();
    
    var postData = {
        phone: phone,
        adress: adress,
        pwd1: pwd1,
        pwd2: pwd2,
        curPwd: curPwd,
        name: name
    };
    console.log(postData);
    $.ajax({
        type: 'POST',
        async: true,
        url: "/user/update/",
        data: postData,
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                $('#userLink').html(data['userName']);
                alert(data['message']);
            }else {
                alert(data['message']);
            }
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus );
            console.log(jqXHR);
            console.log(errorThrown);
        }
    });
}

function saveOrder() {
    var postData = getData('form');

    $.ajax({
        type: 'POST',
        async: true,
        url: '/cart/saveorder/',
        data: postData,
        dataType: 'json',
        success: function(data) {
            if(data['success']) {
                alert(data['message']);
                document.location = '/';
            } else {
                alert(data['message']);
            }
        }
    });
}

/**
 * показывать или прятать данные о заказе
 */
function showProducts(id) {
    console.log("showProducts");    
    var objName = "#purchasesForOrderId_" + id;
    
    if($(objName).css('display') != 'table-row') {
        $(objName).show();
    }else {
        $(objName).hide();
    }
}