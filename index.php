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
            .sidebar{
                width: 250px;
                transition: transform 0.3s;
            }
            .sidebar-closed{
                transform: translateX(-100%);
            }
            .content{
                transition: margin-left 0.3s;
            }
            .content-margin-left{
                margin-left: 250px;
            }
        </style>
        <script>
            function toggleSidebar(){
                document.querySelector('.sidebar').classList.toggle('sidebar-closed');
                document.querySelector('.content').classList.toggle('content-margin-left');
            }
            function toggleTheme(){
                const htmlElement = document.documentElement;
                const isDarkMode = htmlElement.classList.toggle('dark');
                localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
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
    <body class="bg-gray-100 dark:bg-gray-800 flex">
        <div class="sidebar bg-gray-800 text-white p-4 sidebar-closed">
            <div class="flex items-center mb-4">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="w-12 h-12 rounded-full">
                <span class="ml-4"><?php echo $username; ?></span>
            </div>
            <button onclick="requestNotificationPermission()" class="mb-4 w-full bg-blue-500 p-2 rounded">Ativar Notificações</button>
            <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-gray-100 text-2xl">&times;</button>
            <button type="submit" class="mb-4 w-full bg-green-500 p-2 rounded">Adicionar Tarefa</button>
            <input type="text" placeholder="Buscar..." class="w-full p-2 mb-4 bg-gray-700 rounded">
            <ul class="space-y-2 mb-4">
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Hoje</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Em Breve</a></li>
            </ul>
            <button onclick="toggleTheme()" class="w-full bg-yellow-500 p-2 rounded mb-4">Alterar Tema</button>
            <form action="logout.php" method="POST">
                <button type="submit" class="w-full bg-red-500 p-2 rounded">Sair</button>
            </form>
        </div>
        <div class="content flex-grow p-4 content-margin-left">
            <button onclick="toggleSidebar()" class="mb-4 text-gray-800 text-2xl">&#9776;</button>
            <h1 class="text-xl font-bold mb-4">Hoje</h1>
            <ul>
                <li class="mb-2 p-2 bg-white rounded shadow">Tarefa 1</li>
            </ul>
        </div>
    </body>
</html>