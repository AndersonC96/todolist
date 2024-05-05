<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $subtaskId = $_POST['subtask_id'];
        $stmt = $conn->prepare("UPDATE subtasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $subtaskId);
        if($stmt->execute()){
            header("Location: index.php");
        }else{
            echo "Erro ao concluir a subtarefa.";
        }
        $stmt->close();
    }
?>