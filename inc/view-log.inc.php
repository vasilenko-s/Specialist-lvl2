<?php
// Если файл существует, получаем данные в виде масива строк
if (is_file("log/".PATH_LOG)) {
    $lines = file("log/".PATH_LOG);

    echo "<ol>";
    // В цикле выводим списком данные
    foreach ($lines as $line) {
        list ($dt, $ref, $page) = explode("|", $line);
        $dt = date("d-m-Y H:i:s", $dt);
        //Синтаксис HEREDOC
        echo <<<OUT
        <li>            
            [$dt] : $ref -> $page
        </li>
OUT;
    }
    echo "</ol>";
}


