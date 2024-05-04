<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <h1 class="text-xl font-bold mb-4">Criar conta</h1>
            <form action="register_process.php" method="POST">
                <input type="text" name="username" placeholder="Usuário" class="border rounded px-2 py-1 mb-2 block w-full" required>
                <input type="password" name="password" placeholder="Senha" class="border rounded px-2 py-1 mb-2 block w-full" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Criar conta</button>
            </form>
            <a href="login.php" class="text-blue-500 mt-4 inline-block">Já tem uma conta? Faça login</a>
        </div>
    </body>
</html>