<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <h1 class="text-xl font-bold mb-4">Login</h1>
            <form action="login_process.php" method="POST">
                <input type="text" name="username" placeholder="Username" class="border rounded px-2 py-1 mb-2 block w-full" required>
                <input type="password" name="password" placeholder="Password" class="border rounded px-2 py-1 mb-2 block w-full" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
            </form>
            <a href="register.php" class="text-blue-500 mt-4 inline-block">Não tem uma conta? Registre-se.</a>
        </div>
    </body>
</html>