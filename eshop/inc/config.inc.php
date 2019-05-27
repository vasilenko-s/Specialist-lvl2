<?php
//Объявление констант, переменных
// и установка соединения с сервером базы данных
const DB_HOST="eu-cdbr-west-02.cleardb.net";
const DB_LOGIN="ba9546ea66f12f";
const DB_PASSWORD="c80d8f41";
const DB_NAME="heroku_e2cc62cd85afeeb";
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
