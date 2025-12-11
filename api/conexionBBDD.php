<?php

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');
$port = getenv('DB_PORT');

if (!$host) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "periodicos";
    $port = 3306;
}

$link = mysqli_init();

mysqli_ssl_set($link, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($link, $host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Error de conexión (" . mysqli_connect_errno() . "): " . mysqli_connect_error());
}

$link->query("SET NAMES 'utf8'");

?>