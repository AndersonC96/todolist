<?php
    include 'db.php';
    session_start();
    if (!isset($_SESSION['username'])){
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
        <title>Atualizar Cadastro</title>
        <link rel="icon" type="image/png" href="./uploads/favicon.png"/>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            .dark .dark\:bg-gray-900{
                background-color: #1a202c;
            }
            .dark .dark\:bg-gray-800{
                background-color: #2d3748;
            }
            .dark .dark\:text-white{
                color: #ffffff;
            }
            .sidebar{
                width: 250px;
                height: 100vh;
                transition: transform 0.3s;
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
            }
            .sidebar-closed{
                transform: translateX(-250px);
            }
            .content{
                transition: margin-left 0.3s;
                margin-left: 250px;
            }
            .content-margin-left{
                margin-left: 0px;
            }
            .custom-button{
                width: 100%;
                padding: 0.5rem;
                margin-bottom: 0.5rem;
                border-radius: 0.375rem;
                color: white;
            }
            .form-input{
                border: 1px solid #ccc;
                padding: 8px;
                margin-bottom: 8px;
                width: 100%;
                border-radius: 4px;
            }
            .dark .form-input{
                background-color: #2d3748;
                color: white;
                border-color: #4b5563;
            }
            .form-label{
                display: block;
                margin-bottom: 4px;
            }
            .form-button{
                background-color: #4299e1;
                color: white;
                padding: 8px 16px;
                border-radius: 4px;
                border: none;
                cursor: pointer;
            }
            .form-button:hover{
                background-color: #2b6cb0;
            }
            .profile-picture{
                width: 100px;
                border-radius: 50%;
                margin-bottom: 8px;
            }
            .dark .form-button{
                background-color: #2b6cb0;
            }
            .toggle-container{
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
            }
            .toggle-button{
                width: 50px;
                height: 25px;
                border-radius: 50px;
                background-color: #a0f4ff;
                position: relative;
                cursor: pointer;
            }
            .toggle-ball{
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background-color: white;
                position: absolute;
                top: 1.5px;
                left: 1.5px;
                transition: transform 0.3s;
            }
            .dark .toggle-ball{
                transform: translateX(25px);
            }
        </style>
        <script>
            function toggleSidebar(){
                const sidebar = document.querySelector('.sidebar');
                const content = document.querySelector('.content');
                const isClosed = sidebar.classList.toggle('sidebar-closed');
                content.classList.toggle('content-margin-left', isClosed);
            }
            function toggleTheme(){
                const htmlElement = document.documentElement;
                const theme = htmlElement.classList.toggle('dark') ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }
            document.addEventListener('DOMContentLoaded', () =>{
                const savedTheme = localStorage.getItem('theme');
                if(savedTheme === 'dark'){
                    document.documentElement.classList.add('dark');
                }
            });
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 dark:text-white">
        <div class="sidebar bg-gray-800 dark:bg-gray-900 text-white p-4 sidebar-closed">
            <div class="flex items-center mb-4">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="w-12 h-12 rounded-full">
                <span class="ml-4"><?php echo $username; ?></span>
            </div>
            <button onclick="window.location.href='account.php'" class="custom-button bg-indigo-500 mb-4">Minha Conta</button>
            <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-gray-100 text-2xl">&times;</button>
            <form action="logout.php" method="POST">
                <button type="submit" class="custom-button bg-red-500">Sair</button>
            </form>
            <div class="mt-4 text-center">
                <div class="toggle-container">
                    <span class="icon">🌞</span>
                    <div class="toggle-button" onclick="toggleTheme()">
                        <div class="toggle-ball"></div>
                    </div>
                    <span class="icon">🌜</span>
                </div>
            </div>
        </div>
        <div class="content flex-grow p-4 dark:text-white">
            <button onclick="toggleSidebar()" class="mb-4 text-gray-800 dark:text-white text-2xl">&#9776;</button>
            <div class="container">
                <h1 class="text-xl font-bold mb-4">Minha Conta</h1>
                <form action="account_update.php" method="POST" enctype="multipart/form-data">
                    <label for="first_name" class="form-label">Nome</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="form-input" <?php if ($isReadOnly) echo 'readonly'; ?>>
                    <label for="last_name" class="form-label">Sobrenome</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="form-input" <?php if ($isReadOnly) echo 'readonly'; ?>>
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-input" readonly>
                    <label for="new_password" class="form-label">Nova Senha</label>
                    <input type="password" name="new_password" id="new_password" class="form-input">
                    <label for="profile_picture" class="form-label">Foto de Perfil</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-input">
                    <?php if ($user['profile_picture']): ?>
                    <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">
                    <?php endif; ?>
                    <button type="submit" class="form-button mt-4">Atualizar</button>
                </form>
            </div>
        </div>
    </body>
</html>