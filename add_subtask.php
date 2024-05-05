<?php
    include 'db.php';
    session_start();
    $task_id = $_POST['task_id'];
    $subtask_title = $_POST['subtask_title'];
    $stmt = $conn->prepare("INSERT INTO subtasks (task_id, title) VALUES (?, ?)");
    $stmt->bind_param("is", $task_id, $subtask_title);
    $stmt->execute();
    header("Location: index.php");
?>