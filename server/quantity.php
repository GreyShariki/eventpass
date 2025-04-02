<?php
require_once("db.php");
header('Content-Type: application/json');
$eventId = $_POST['event_id'];
$sql = "UPDATE events SET quantity = quantity + 1 WHERE id = '$eventId'";
if ($res = $conn->query($sql)){
    header("location:../index.php");
};
?>