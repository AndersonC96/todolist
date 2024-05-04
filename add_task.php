<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Adicionar Tarefa</title>
        <link rel="icon" type="image/png" href="./uploads/favicon.png"/>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100 p-4">
        <div class="max-w-md mx-auto bg-white p-4 rounded shadow">
            <h1 class="text-2xl mb-4">Adicionar Nova Tarefa</h1>
            <form action="save_task.php" method="POST">
                <label class="block mb-2">Título</label>
                <input type="text" name="title" class="w-full p-2 border rounded mb-4" required>
                <label class="block mb-2">Descrição</label>
                <textarea name="description" class="w-full p-2 border rounded mb-4"></textarea>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Adicionar Tarefa</button>
            </form>
        </div>
    </body>
</html>