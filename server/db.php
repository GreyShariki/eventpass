<?php
$dbname = "eventpass";
$password = "";
$username = "root";
$server = "localhost";
try {
    $conn = new mysqli($server, $username, $password, $dbname);
} catch (\Throwable $th) {
    echo "Ошибка подключения";
};

?>