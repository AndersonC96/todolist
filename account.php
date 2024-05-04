<?php
    include 'db.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <h1 class="text-xl font-bold mb-4">Minha Conta</h1>
            <form action="account_update.php" method="POST" enctype="multipart/form-data">
                <input type="password" name="new_password" placeholder="Nova Senha" class="border rounded px-2 py-1 mb-2 block w-full">
                <label class="block mb-2">Foto de Perfil:</label>
                <input type="file" name="profile_picture" class="border rounded px-2 py-1 mb-2 block w-full">
                <?php if ($user['profile_picture']): ?>
                <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="mb-4">
                <?php endif; ?>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Atualizar</button>
            </form>
        </div>
    </body>
</html>