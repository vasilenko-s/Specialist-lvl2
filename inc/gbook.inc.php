<?php
/* Основные настройки */
const DB_HOST="eu-cdbr-west-02.cleardb.net";
const DB_LOGIN="ba9546ea66f12f";
const DB_PASSWORD="c80d8f41";
const DB_NAME="heroku_e2cc62cd85afeeb";

// Соединение и выбор базы данных
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

// Отслеживаем ошибки при соединении
if( !$link ){
    echo 'Ошибка: '
        . mysqli_connect_errno()
        . ':'
        . mysqli_connect_error();
}
/* Основные настройки */

/* Сохранение записи в БД */

//Проверяем была ли отправлена форма и фильтруем полученые данные
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name =trim(strip_tags($_POST['name']));
    $email=trim(strip_tags($_POST['email']));
    $msg=trim(strip_tags($_POST['msg']));

//формируем запрос на вставку данных в таблицу
$sql = "INSERT INTO msgs (name, email, msg)
        VALUES ('$name','$email', '$msg')";

// Посылаем запрос на вставку
mysqli_query($link, $sql);

// перезапрашиваем эту же страницу
header("Location:".$_SERVER['REQUEST_URI']);
}

/* Сохранение записи в БД */

/* Удаление записи из БД */

// Проверяем что запрос был методом GET
if($_SERVER['REQUEST_METHOD'] == 'GET'){
 //Принимаем и фильтруем данные
    $del=abs((int)$_GET['del']);

 //Формируем запрос на удаление данных
    $sql="DELETE FROM msgs WHERE id = $del";

 // Выполняем запрос
    mysqli_query($link, $sql);

}

/* Удаление записи из БД */
?>
<h3>Оставьте запись в нашей Гостевой книге</h3>

<form method="post" action="<?= $_SERVER['REQUEST_URI']?>">
<label for="name">Имя:</label> <br /><input type="text" name="name" id="name" /><br />
<label for="email">Email:</label> <br /><input type="text" name="email" id="email" /><br />
<label for="msg">Сообщение</label>  <br /><textarea name="msg" id="msg""> </textarea><br />

<br />

<input type="submit" value="Отправить!" />

</form>
<?php

/* Вывод записей из БД */

//Формируем запрос на выборку (в обратном порядке)
$sql="SELECT id, name, email, msg, UNIX_TIMESTAMP(datetime) as dt
      FROM msgs
      ORDER BY id DESC";

// Выполняем запрос
$result = mysqli_query($link, $sql);

// Закрываем соединение с сервером бд
mysqli_close($link);

//Получаем количество записай результата выборке
$rows_count = mysqli_num_rows($result);
echo "<p>Всего записей в гостевой книге: $rows_count</p>";

//Используя цикл выводи информацию о сообщениях, авторах и т.д.
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    // Берем первый ряд в выборке
    //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $dt = date("d-m-Y H:i:s", $row["dt"]);
    $serv = $_SERVER['SERVER_NAME'];
    //Выводим форматированые записи
    echo <<<HEREDOC
    <p>
    <a href="mailto:{$row['email']}">{$row['name']}</a>
     в  {$dt} написал<br />{$row['msg']}
    </p>
    <p align="right">
    <a href="http://{$serv}/index.php?id=gbook&del={$row['id']}">
            Удалить</a>
    </p>
HEREDOC;
}

/* Вывод записей из БД */

?>
