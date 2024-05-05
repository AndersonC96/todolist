<?php
    include 'db.php';
    session_start();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, priority, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $due_date, $priority, $username);
    $stmt->execute();
    header("Location: index.php");
?>