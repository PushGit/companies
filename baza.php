<?php
$link  = mysqli_connect('localhost', 'root', '435123451', 'companies');
if (!$link) {
    die('Ошибка соединения: ' . mysql_error());
}
echo 'Успешно соединились';
?>