<?php
require_once("db.php");
$username = $_POST["username"];
$password = $_POST["password"];
$sql = "SELECT * FROM admins where login = '$username' and password = '$password'";
try {
    $res = $conn->query($sql);
    if ($res->num_rows > 0){
        session_start();
        $_SESSION["logged"] = true;
        header('location:../adminpanel.php');
    } else {
        echo "Ошибка при входе";
    };
} catch (Exception $e) {
    echo "QQQQQQQQ";
};

?>