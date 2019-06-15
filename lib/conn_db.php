<?php

$conMy = mysqli_connect('localhost', 'root', '');
if (!$conMy) {
    die('Não foi possível conectar: ' . mysqli_error());
}

mysqli_set_charset($conMy, 'utf8');
mysqli_select_db($conMy, 'pema_data');

$qmode = "SET SESSION sql_mode = 'PIPES_AS_CONCAT'";
mysqli_query($conMy, $qmode);


?>