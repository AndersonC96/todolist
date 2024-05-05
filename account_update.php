<?php
    include 'db.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    $new_password = $_POST['new_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    if(!empty($first_name) || !empty($last_name)){
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE username = ? AND (first_name = '' OR last_name = '')");
        $stmt->bind_param("sss", $first_name, $last_name, $username);
        $stmt->execute();
    }
    if(!empty($new_password)){
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashedPassword, $username);
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