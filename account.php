<?php
    include 'db.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT username, first_name, last_name, profile_picture FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $profilePicture = $user['profile_picture'] ? 'uploads/' . $user['profile_picture'] : 'default.jpg';
    $stmt->close();
    $isReadOnly = !empty($user['first_name']) || !empty($user['last_name']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="icon" type="image/png" href="./uploads/favicon.png"/>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Minha Conta</h1>
        <form action="account_update.php" method="POST" enctype="multipart/form-data">
            <label for="first_name" class="block mb-2">Nome</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="border rounded px-2 py-1 mb-4 block w-full" <?php if ($isReadOnly) echo 'readonly'; ?>>

            <label for="last_name" class="block mb-2">Sobrenome</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="border rounded px-2 py-1 mb-4 block w-full" <?php if ($isReadOnly) echo 'readonly'; ?>>

            <label for="last_name" class="block mb-2">Usuário</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['username']); ?>" class="border rounded px-2 py-1 mb-4 block w-full" <?php if ($isReadOnly) echo 'readonly'; ?>>
            
            <label class="block mb-2">Nova Senha</label>
            <input type="password" name="new_password" class="border rounded px-2 py-1 mb-4 block w-full">
            
            <label class="block mb-2">Foto de Perfil:</label>
            <input type="file" name="profile_picture" class="border rounded px-2 py-1 mb-2 block w-full">
            
            <?php if ($user['profile_picture']): ?>
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="mb-4">
            <?php endif; ?>
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Atualizar</button>
        </form>
    </div>
</body>
</html>
