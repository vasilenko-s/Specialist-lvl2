<?php
	// Добавление товара в корзину
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

//Назначаем количество добавляемого товара равным 1
$count+=1;

// Получаем идентификатор добавляемого в корзину товара
$id = clearInt($_GET['id']);

//Сохраняем товар в корзине
if ($id) {
    add2Basket($id);
}

//Переадресация на страницу каталога товаров
header("Location: catalog.php");

