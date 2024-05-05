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
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE username = ? AND completed = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $tasks = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tarefas Concluídas</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="./uploads/favicon.png"/>
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
    <body class="bg-gray-100 dark:bg-gray-900 flex dark:text-white">
        <div class="sidebar bg-gray-800 dark:bg-gray-900 text-white p-4 sidebar-closed">
            <div class="flex items-center mb-4">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="w-12 h-12 rounded-full">
                <span class="ml-4"><?php echo $username; ?></span>
            </div>
            <button onclick="window.location.href='index.php'" class="custom-button bg-green-500 mb-4">Home</button>
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
            <h1 class="text-xl font-bold mb-4">Tarefas Concluídas</h1>
            <ul>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                <li class="task-item mb-4">
                    <h3 class="text-lg font-bold"><?php echo htmlspecialchars($task['title']); ?></h3>
                    <hr>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <form action="undo_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" class="bg-yellow-500 text-white p-2 rounded mb-2">Refazer</button>
                    </form>
                    <div class="task-footer">
                        <span><strong>Criada em:</strong> <?php echo date('d/m/Y', strtotime($task['created_at'])); ?></span>
                        <span><strong>Data de Término:</strong> <?php echo $task['due_date'] ? date('d/m/Y', strtotime($task['due_date'])) : 'Não definida'; ?></span>
                        <span class="inline-block rounded px-2 py-1 text-xs font-bold <?php echo $task['priority'] == 'Baixa' ? 'badge-baixa' : ($task['priority'] == 'Média' ? 'badge-media' : ($task['priority'] == 'Alta' ? 'badge-alta' : 'badge-urgente')); ?>"><?php echo $task['priority']; ?></span>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </body>
</html>