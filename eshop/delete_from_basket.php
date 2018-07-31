<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

	//Получаем идентификатор удаляемого товара
    $id = clearInt($_GET['id']);
    //Сохраняем товар в корзине
    if ($id) {
        deleteItemFromBasket($id);
    }
    //Переадресация пользователя на страницу корзины
    header("Location: basket.php");