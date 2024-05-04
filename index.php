<?php
    include 'db.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <div>
                <a href="account.php" class="bg-green-500 text-white px-4 py-2 rounded">Minha Conta</a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Sair</a>
            </div>
            <h1 class="text-xl font-bold mb-4">To-Do List</h1>
            <form action="add.php" method="POST">
                <input type="text" name="title" class="border rounded px-2 py-1" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Task</button>
            </form>
            <ul class="mt-4">
                <?php
                    $result = $conn->query("SELECT * FROM tasks");
                    while($row = $result->fetch_assoc()){
                        echo '<li class="flex justify-between items-center bg-white p-2 mb-2 rounded shadow">';
                        echo '<span>' . $row['title'] . '</span>';
                        echo '<form action="delete.php" method="POST" class="ml-4">';
                        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                        echo '<button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>';
                        echo '</form>';
                        echo '</li>';
                    }
                ?>
            </ul>
        </div>
    </body>
</html>