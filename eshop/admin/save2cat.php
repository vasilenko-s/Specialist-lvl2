<?php
	// подключение библиотек
	require "secure/session.inc.php";
	require "../inc/lib.inc.php";
    require "../inc/config.inc.php";
echo "!!";
// Получаем и фильтруем данные из формы add2cat.php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim(strip_tags($_POST['title']));
    $author = trim(strip_tags($_POST['author']));
    $pubyear = abs((int)$_POST['pubyear']);
    $price = abs((int)$_POST['price']);
}

//Вызываем функцию для добавления товара
if(!addItemToCatalog($title, $author, $pubyear, $price)){
    echo 'Произошла ошибка при добавлении товара в каталог';
}else{
    header("Location: add2cat.php");
    exit;
}

