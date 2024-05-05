<?php
    include 'db.php';
    session_start();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, username) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $due_date, $username);
    $stmt->execute();
    header("Location: index.php");
?>