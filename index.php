<?php
    session_start();
    include 'db.php';
    if (!isset($_SESSION['username'])){
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
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE username = ? AND completed = 0");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $tasks = $stmt->get_result();
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
            <button onclick="window.location.href='add_task.php'" class="custom-button bg-green-500">Adicionar Tarefa</button>
            <input type="text" placeholder="Buscar..." class="w-full p-2 mb-4 bg-gray-700 rounded">
            <ul class="space-y-2 mb-4">
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Hoje</a></li>
                <li><a href="#" class="block p-2 hover:bg-gray-700 rounded">Em Breve</a></li>
                <a href="completed_tasks.php" class="block p-2 hover:bg-gray-700 rounded">Tarefas Concluídas</a>
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
            <h1 class="text-xl font-bold mb-4">Tarefas de Hoje</h1>
            <ul>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                <li class="task-item mb-4">
                    <h3 class="text-lg font-bold"><?php echo htmlspecialchars($task['title']); ?></h3>
                    <hr>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <form action="finish_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" class="bg-green-500 text-white p-2 rounded mb-2">Finalizar Tarefa</button>
                    </form>
                    <form action="undo_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" class="bg-yellow-500 text-white p-2 rounded mb-2">Refazer</button>
                    </form>
                    <h4 class="text-md font-bold mt-2">Subtarefas</h4>
                    <ul>
                        <?php
                            $subtaskStmt = $conn->prepare("SELECT * FROM subtasks WHERE task_id = ?");
                            $subtaskStmt->bind_param("i", $task['id']);
                            $subtaskStmt->execute();
                            $subtasks = $subtaskStmt->get_result();
                            while($subtask = $subtasks->fetch_assoc()):
                        ?>
                        <li class="task-item mb-2">
                            <?php echo htmlspecialchars($subtask['title']); ?>
                            <form action="finish_subtask.php" method="POST" class="inline">
                                <input type="hidden" name="subtask_id" value="<?php echo $subtask['id']; ?>">
                                <button type="submit" class="bg-blue-500 text-white p-1 rounded ml-2">Finalizar</button>
                            </form>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <form action="add_subtask.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="text" name="subtask_title" placeholder="Nova Subtarefa" class="w-full p-2 border rounded mb-2" style="color:black;">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Adicionar Subtarefa</button>
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