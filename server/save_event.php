<?php
require_once("db.php");
$imageName = 'event_' . $eventId . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['image']['tmp_name'], '../img/' . $imageName);
$date = $_POST['date'];
$time = $_POST['time'];
$place = $_POST['place'];
$organizer = $_POST['organizer'];
$description = $_POST['description'];
$sql = "insert i"
?>