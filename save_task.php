<?php
    include 'db.php';
    session_start();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $username);
    $stmt->execute();
    header("Location: index.php");
?>
