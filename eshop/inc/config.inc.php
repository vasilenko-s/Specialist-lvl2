<?php
//Объявление констант, переменных
// и установка соединения с сервером базы данных
const DB_HOST="localhost";
const DB_LOGIN="root";
const DB_PASSWORD="1";
const DB_NAME="eshop";
const ORDERS_LOG="orders.log";

//для хранения корзины пользователя
$basket=[];
// для хранения количества товара в коризне
$count=0;

/*Установление соединения с сервером БД*/
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

if( !$link ){
    echo 'Ошибка: '
        . mysqli_connect_errno()
        . ':'
        . mysqli_connect_error();
}
/*Установление соединения с сервером БД*/

//Инициализация корзины
basketInit();
