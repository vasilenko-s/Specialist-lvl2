<?php
//Фильтрация строки
function clearInt($num){
    return abs((int)$num);
}

//Фильтрация числа
function clearStr($str){
    return trim(strip_tags($str));
}

/*Создание функции добавления товара в каталог*/
function addItemToCatalog($title, $author,$pubyear, $price){
    global $link;

    // Формируем подготовленный запрос
    $sql = 'INSERT INTO catalog (title, author, pubyear, price)
    VALUES (?, ?, ?, ?)';
    if (!$stmt = mysqli_prepare($link, $sql))
        return false;
    //Исполняем подготовленный запрос
    mysqli_stmt_bind_param($stmt, "ssii", $title, $author,
        $pubyear, $price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return true;

}

/*Создание функции выборки товара из каталога */
function selectAllItems(): array{
    global $link;
    // Формируем запрос на выборку
    $sql = 'SELECT id, title, author, pubyear, price 
            FROM catalog';
    //Исполняем подготовленный запрос
    if(!$result = mysqli_query($link, $sql))
        return false;
    // Возвращаем все строки в виде массива
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Освобождаем память занятую запросами
    mysqli_free_result($result);
    return $items;
}

/*Сохранение корзины с товарами в куки*/
function saveBasket()
{
    global $basket;
    //кодируем переменную для коректной передачи
    $basket = base64_encode(serialize($basket));
    // записываем в "вечные" куки переменную $basket
    setcookie('basket', $basket, 0x7FFFFFFF);
}

/* Создает переменную корзины товара */
function basketInit(){
    global $basket, $count;
    if(!isset($_COOKIE['basket'])){
        // Новый заказ с новым идентификатором заказа
        $basket = ['orderid' => uniqid()];
        saveBasket();
    }else{
        // Если был заказ, считываем его с куки в переменную
        $basket = unserialize(base64_decode($_COOKIE['basket']));
        $count = count($basket) - 1;
    }
}

/*Добавляет товар в корзину пользователя*/
function add2Basket(int $id) {
    global $basket;
    $basket[$id] = 1;
    saveBasket();
}

/*Возвращает всю пользовательскую корзину в виде асоц. мас.*/
function myBasket(){
    global $link, $basket;
    //Получаем все идентификаторы
    $goods = array_keys($basket);
    //Отбрасывам идентификатор покупки
    array_shift($goods);
    if(!$goods)
        return false;
    //Объединяем идентификаторы в строку
    $ids = implode(",", $goods);
    //Составляем запрос
    $sql = "SELECT id, author, title, pubyear, price
FROM catalog WHERE id IN ($ids)";
    //Выполняем запрос
    if(!$result = mysqli_query($link, $sql))
        return false;
    $items = result2Array($result);
    //освобождает память занятую запросом
    mysqli_free_result($result);
    return $items;

}

/*Дополняет ассоциативный массив товаров количеством*/
function result2Array($data){
    global $basket;
    $arr = [];
    while($row = mysqli_fetch_assoc($data)){
        $row['quantity'] = $basket[$row['id']];
        $arr[] = $row;
    }
    return $arr;
}

/*Удаление товара из корзины*/
function deleteItemFromBasket(int $id){
    global $basket;
    //Удаляем необходимый элемент массива $basket
    unset($basket[$id]);
    //Пересохраняем корзину в куки
    saveBasket();
}






