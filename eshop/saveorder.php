<?php
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

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
    //записываем строку в файл
    file_put_contents("admin/".ORDERS_LOG, $order, FILE_APPEND);

    /*Создание заказа*/
    saveOrder($dt);
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