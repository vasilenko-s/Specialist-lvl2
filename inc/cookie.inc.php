<?php
// Переменная для подсчета количества посещений
$visitCounter=0;

// Проверяем пришли ли куки по имени visitCounter от пользователя
if (isset($_COOKIE["visitCounter"]))
// Если куки пришли то сохраняем их в переменную
    $visitCounter=$_COOKIE["visitCounter"];

//Увеличиваем количество посещений на 1
$visitCounter++;

// Переменная для хранения даты последнего посещения
$lastVisit='';

// Проверяем пришли ли куки по имени lastVisit от пользователя
if (isset($_COOKIE["lastVisit"]))
// Если куки пришли, то сохраняем их значение в переменную
// $lastVisit отформатировав ВЫВОД ДАТЫ
   $lastVisit=date("d-m-Y H:i:s", $_COOKIE["lastVisit"]);

//Условие, на установление куки только раз в день
if(date('d-m-Y', $_COOKIE['lastVisit']) != date('d-m-Y')) {
// Устанавливаем куки visitCounter = $visitCounter
// 0x7FFFFFFF - "на всю жизнь"
    setcookie('visitCounter', $visitCounter, 0x7FFFFFFF);

//Устанавливаем куки 'lastVisit' = текущей временной метке (timestamp)
    setcookie('lastVisit', time(), 0x7FFFFFFF);
}
