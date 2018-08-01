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

/*Создание функции добавления заказа в бд, таблицу orders*/
function saveOrder($datetime){
    global $link, $basket;
    $goods = myBasket();
    //инициализируем объект запроса
    $stmt = mysqli_stmt_init($link) ;
    $sql = 'INSERT INTO orders (
                        title,
                        author,
                        pubyear,
                        price,
                        quantity,
                        orderid,
                        datetime)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';
    //второй вариант подготовки запроса
    if (!mysqli_stmt_prepare($stmt, $sql))
        return false;
    foreach($goods as $item){
        mysqli_stmt_bind_param($stmt, "ssiiisi",
            $item['title'], $item['author'],
            $item['pubyear'], $item['price'],
            $item['quantity'],
            $basket['orderid'],
            $datetime);
        //Выполняем запрос
        mysqli_stmt_execute($stmt);
    }
    //закрываем подготовленный запрос
    mysqli_stmt_close($stmt);
    //удаляем куки пользователя
    setcookie("basket","", 1);
    return true;
}

/*Функция выборки всех заказов*/
function getOrders(){
    global $link;
    if(!is_file(ORDERS_LOG))
        return false;
    /* Получаем в виде массива персональные данные пользователей из файла */
    $orders = file(ORDERS_LOG);
    /* Массив, который будет возвращен функцией */
    $allorders = [];
    foreach ($orders as $order) {
        list($name, $email, $phone, $address, $orderid, $date) = explode("|",
            $order);
        /* Промежуточный массив для хранения информации о конкретном заказе */
        $orderinfo = [];
        /* Сохранение информацию о конкретном пользователе */
        $orderinfo["name"] = $name;
        $orderinfo["email"] = $email;
        $orderinfo["phone"] = $phone;
        $orderinfo["address"] = $address;
        $orderinfo["orderid"] = $orderid;
        $orderinfo["date"] = $date;
        /* SQL-запрос на выборку из таблицы orders всех товаров для конкретного
        покупателя */
        $sql = "SELECT title, author, pubyear, price, quantity
                FROM orders
                WHERE orderid = '$orderid' AND datetime = $date";
        /* Получение результата выборки */
        if(!$result = mysqli_query($link, $sql))
            return false;
        //Получаем все строки в ассоциативный массив
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //Освобождаем память занятую запросом
        mysqli_free_result($result);
        /* Сохранение результата в промежуточном массиве */
        $orderinfo["goods"] = $items;
        /* Добавление промежуточного массива в возвращаемый массив */
        $allorders[] = $orderinfo;
    }
    return $allorders;
}






