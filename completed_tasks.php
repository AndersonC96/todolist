<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
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
            .badge-baixa{
                background-color: #d1fae5;
                color: #065f46;
            }
            .badge-media{
                background-color: #bf9701;
                color: #ffffff;
            }
            .badge-alta{
                background-color: #ff2a00;
                color: #ffffff;
            }
            .badge-urgente{
                background-color: #ff0000;
                color: #ffffff;
            }
            .task-footer{
                margin-top: 0.5rem;
                border-top: 1px solid #e5e7eb;
                padding-top: 0.5rem;
                display: flex;
                justify-content: space-between;
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
        <div class="content flex-grow p-4 dark:text-white">
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