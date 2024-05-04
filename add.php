<?php
    include 'db.php';
    $title = $_POST['title'];
    $stmt = $conn->prepare("INSERT INTO tasks (title) VALUES (?)");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    header("Location: index.php");
?>