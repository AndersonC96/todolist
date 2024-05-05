<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subtask_id'])){
        $subtaskId = $_POST['subtask_id'];
        $stmt = $conn->prepare("UPDATE subtasks SET completed = 0 WHERE id = ?");
        $stmt->bind_param("i", $subtaskId);
        if($stmt->execute()){
            header("Location: index.php");
        }else{
            echo "Erro ao desfazer a subtarefa.";
        }
        $stmt->close();
    }else{
        header("Location: index.php");
    }
?>