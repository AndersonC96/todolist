<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $taskId = $_POST['task_id'];
        $stmt = $conn->prepare("UPDATE tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        if($stmt->execute()){
            header("Location: index.php");
        }else{
            echo "Erro ao concluir a tarefa.";
        }
        $stmt->close();
    }
?>