<?php
$dt =time();
$page = $_SERVER['REQUEST_URI'];
$ref = $_SERVER['HTTP_REFERER'];
define('PATH_NAME', 'log');

// путь перемещения пользователя по сайту
$path = "$dt | $ref | $page <br> \n";

//Можно создать директорию, если ее нет
if(!is_dir(PATH_NAME)) mkdir(PATH_NAME);

// Для того, чтобы можно было записывать и удалять
chmod(PATH_NAME, 0777);

//записываем строку в файл в папку log
file_put_contents(PATH_NAME."/".PATH_LOG, $path, FILE_APPEND);



