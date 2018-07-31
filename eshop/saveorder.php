<?php
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

   define('PATH_NAME', 'log');

	/*Создание файла с персональными данными пользователя*/

	//Получите из веб-формы и обработайте данные заказа
    $name = clearStr($_POST['name']);
    $email = clearStr($_POST['email']);
    $phone = clearStr($_POST['phone']);
    $address = clearStr($_POST['address']);
    //Получите идентификатор заказа
    $orderId = $basket['orderid'];
    // Получите дату/время заказа в виде временной метки (timestamp)
    $dt=time();
    //Строковая переменная для записи в файл
    $order="$name|$email|$phone|$address|$orderId|$dt \n";

    /* Записываем строку в файл*/
    //Создаем директорию, если ее нет
    if(!is_dir(PATH_NAME)) mkdir(PATH_NAME);
    // Все права
    chmod(PATH_NAME, 0777);
    //записываем строку
    file_put_contents(PATH_NAME."/".ORDERS_LOG, $order, FILE_APPEND);







?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Сохранение данных заказа</title>
</head>
<body>
	<p>Ваш заказ принят.</p>
	<p><a href="catalog.php">Вернуться в каталог товаров</a></p>
</body>
</html>