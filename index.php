<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['username'])){
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
        <title>To-Do List</title>
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
            .dark .dark\:text-blue-300{
                color: #63b3ed;
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

            .theme-button{
                background-color: #f59e0b; /* amarelo */
            }

            .task-item{
                padding: 0.5rem;
                border-radius: 0.375rem;
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: background-color 0.3s;
            }

            .task-item:hover{
                background-color: #f0f4f8;
            }

            .dark .task-item{
                background-color: #374151;
                color: white;
            }

            .dark .task-item:hover{
                background-color: #4b5563;
            }

            .toggle-container{
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            .toggle-container .icon{
                font-size: 1.5rem;
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
            function requestNotificationPermission(){
                if(Notification.permission === 'granted'){
                    showNotification();
                }else if(Notification.permission !== 'denied'){
                    Notification.requestPermission().then(permission =>{
                        if(permission === 'granted'){
                            showNotification();
                        }
                    });
                }
            }
            function showNotification(){
                new Notification('Nova notificação!',{
                    body: 'O tempo para finalizar uma tarefa está acabando.',
                    icon: 'uploads/notification.png'
                });
            }
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 flex dark:text-white">
        <div class="sidebar bg-gray-800 dark:bg-gray-900 text-white p-4 sidebar-closed">
            <div class="flex items-center mb-4">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="w-12 h-12 rounded-full">
                <span class="ml-4"><?php echo $username; ?></span>
            </div>
            <button onclick="window.location.href='my_account.php'" class="custom-button bg-indigo-500 mb-4">Minha Conta</button>
            <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-gray-100 text-2xl">&times;</button>
            <button onclick="window.location.href='add_task.php'" class="custom-button bg-green-500">Adicionar Tarefa</button>
            <input type="text" placeholder="Buscar..." class="w-full p-2 mb-4 bg-gray-700 rounded">
            <ul class="space-y-2 mb-4">
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Hoje</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Em Breve</a></li>
            </ul>
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
            <h1 class="text-xl font-bold mb-4">Hoje</h1>
            <ul>
                <li class="task-item">Tarefa 1</li>
            </ul>
        </div>
    </body>
</html>
