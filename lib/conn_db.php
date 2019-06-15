<?php

$conMy = mysqli_connect('u0zbt18wwjva9e0v.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'yks3dxejjlf35eia', 'ctak6qjhsadi495o');
if (!$conMy) {
    die('Não foi possível conectar: ' . mysqli_error());
}

mysqli_set_charset($conMy, 'utf8');
mysqli_select_db($conMy, 'zyzjmym9foub9xvj');

$qmode = "SET SESSION sql_mode = 'PIPES_AS_CONCAT'";
mysqli_query($conMy, $qmode);


?>