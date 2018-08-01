<?php
/*Секьюрная библиотека*/

//Создание файла пользователей
const FILE_NAME=".htpasswd";

//Генерация стойкого хеша пароля
function getHash($password){
    $hash = password_hash($password, PASSWORD_BCRYPT);
    return $hash;
}

//Проверяет пароль
function checkHash($password, $hash){
    return password_verify($password, $hash);
}

//Cоздает новую запись в файле пользователей
function saveUser($login, $hash){
    $str = "$login:$hash\n";
    if(file_put_contents(FILE_NAME, $str, FILE_APPEND))
        return true;
    else
        return false;
}

//Проверяет наличие пользователя в файле пользователей
function userExists($login){
    if(!is_file(FILE_NAME))
        return false;
    //возвращает массив строк
    $users = file(FILE_NAME);
    foreach($users as $user){
        //проверяет наличие подстроки в строке
        if(strpos($user, $login.':') !== false)
            return $user;
    }
    return false;
}

//Завершает сессию
function logOut(){
    session_destroy();
    header('Location: secure/login.php');
    exit;
}


