<?php
    include 'db.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    if(!empty($_POST['new_password'])){
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        $stmt->execute();
    }
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK){
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
        $stmt->bind_param("ss", basename($target_file), $username);
        $stmt->execute();
    }
    header("Location: account.php");
?>