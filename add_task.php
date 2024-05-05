<?php
    session_start();
    include 'db.php';
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $profilePicture = $user['profile_picture'] ? 'uploads/' . $user['profile_picture'] : 'default.jpg';
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Tarefa</title>
    <link rel="icon" type="image/png" href="./uploads/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .dark .dark\:bg-gray-900 {
            background-color: #1a202c;
        }
        .dark .dark\:bg-gray-800 {
            background-color: #2d3748;
        }
        .dark .dark\:text-white {
            color: #ffffff;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            transition: transform 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar-closed {
            transform: translateX(-250px);
        }

        .content {
            transition: margin-left 0.3s;
            margin-left: 250px;
        }

        .content-margin-left {
            margin-left: 0px;
        }

        .custom-button {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem;
            color: white;
        }

        .form-input {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 8px;
            width: 100%;
            border-radius: 4px;
            color: black;
        }

        .dark .form-input {
            background-color: #2d3748;
            color: white; /* Define a cor branca para o texto no tema escuro */
            border-color: #4b5563;
        }

        input[type="date"] {
            color: black; /* Define a cor preta para a entrada de data */
        }

        .form-label {
            display: block;
            margin-bottom: 4px;
        }

        .form-button {
            background-color: #4299e1;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .form-button:hover {
            background-color: #2b6cb0;
        }

        .dark .form-button {
            background-color: #2b6cb0;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .toggle-button {
            width: 50px;
            height: 25px;
            border-radius: 50px;
            background-color: #a0f4ff;
            position: relative;
            cursor: pointer;
        }

        .toggle-ball {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: white;
            position: absolute;
            top: 1.5px;
            left: 1.5px;
            transition: transform 0.3s;
        }

        .dark .toggle-ball {
            transform: translateX(25px);
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            const isClosed = sidebar.classList.toggle('sidebar-closed');
            content.classList.toggle('content-margin-left', isClosed);
        }

        function toggleTheme() {
            const htmlElement = document.documentElement;
            const theme = htmlElement.classList.toggle('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
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
        <button onclick="window.location.href='add_task.php'" class="custom-button bg-green-500">Adicionar Tarefa</button>
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
        <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h1 class="text-2xl mb-4 dark:text-white">Adicionar Nova Tarefa</h1>
            <form action="save_task.php" method="POST">
                <label class="block mb-2 dark:text-white">Título</label>
                <input type="text" name="title" class="form-input w-full p-2 border rounded mb-4" required>
                <label class="block mb-2 dark:text-white">Descrição</label>
                <textarea name="description" class="form-input w-full p-2 border rounded mb-4"></textarea>
                <label class="block mb-2 dark:text-white">Data de Término</label>
                <input type="date" name="due_date" class="form-input w-full p-2 border rounded mb-4">
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Adicionar Tarefa</button>
            </form>
        </div>
    </div>
</body>
</html>
