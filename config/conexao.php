<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "sci3d_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

?>