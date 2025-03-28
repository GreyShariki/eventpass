<?php
require_once("db.php");

$imageName = 'event_' . uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
move_uploaded_file($_FILES['image']['tmp_name'], '../img/' . $imageName);

$title = $_POST['title']; 
$date = $_POST['date'];
$time = $_POST['time'];
$place = $_POST['place'];
$organizer = $_POST['organizer'];
$description = $_POST['description'];

$sql = "INSERT INTO events (date, time, place, organizer, description, image, title) 
        VALUES ('$date', '$time', '$place', '$organizer', '$description', '$imageName', '$title')";

if ($conn->query($sql)) {
    $eventId = $conn->insert_id;
    echo "Ивент успешно создан<br>";
} else {
    echo "Ошибка при создании ивента: " . $conn->error;
    exit;
}

if (!empty($_POST['benefit']) && is_array($_POST['benefit'])) {
    foreach ($_POST['benefit'] as $key => $benefit) {
        $icon = $benefit['icon'];
        $text = $benefit['text'];
        if (!empty($time) && !empty($text)) {
            $sql = "INSERT INTO content ( icon, event_id, description) 
                    VALUES ('$icon', '$eventId', '$text')";
            $conn->query($sql);
        }
    }
    echo "Контент успешно добавлена<br>";
}
if (!empty($_POST['program']) && is_array($_POST['program'])) {
    foreach ($_POST['program'] as $key => $program) {
        $time = $program['time'];
        $icon = $program['icon'];
        $text = $program['text'];
        if (!empty($time) && !empty($text)) {
            $sql = "INSERT INTO program (event_id, time, icon, description) 
                    VALUES ('$eventId', '$time', '$icon', '$text')";
            $conn->query($sql);
        }
    }
    echo "Программа успешно добавлена<br>";
}

if (!empty($_POST['tickets']) && is_array($_POST['tickets'])) {
    foreach ($_POST['tickets'] as $key => $ticket) {
        $type = $ticket['type'];
        $name = $ticket['name'];
        $price = $ticket['price'];
        
        if (!empty($type) && !empty($name)) {
            $sql = "INSERT INTO tickets (event_id, color, name, price) 
                    VALUES ('$eventId', '$type', '$name', '$price')";
            $conn->query($sql);
            $ticketId = $conn->insert_id;
            
            if (!empty($ticket['benefits']) && is_array($ticket['benefits'])) {
                foreach ($ticket['benefits'] as $benefit) {
                    if (!empty($benefit)) {
                        $sql = "INSERT INTO tickets_ul (ticket_id, description) 
                                VALUES ('$ticketId', '$benefit')";
                        $conn->query($sql);
                    }
                }
            }
        }
    }
    echo "Билеты и их преимущества успешно добавлены<br>";
}
?>