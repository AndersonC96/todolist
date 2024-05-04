<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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
            function toggleTheme(){
                const htmlElement = document.documentElement;
                const theme = htmlElement.classList.toggle('dark') ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }
            document.addEventListener('DOMContentLoaded', () => {
                const savedTheme = localStorage.getItem('theme');
                if(savedTheme === 'dark'){
                    document.documentElement.classList.add('dark');
                }
            });
        </script>
    </head>
    <body class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-800 transition-all duration-500">
        <div class="w-full max-w-md p-8 bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-center mb-6 text-gray-900 dark:text-white">Login</h1>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials'): ?>
                <div class="text-red-500 mb-4">Usuário ou senha inválidos!</div>
            <?php endif; ?>
            <form action="login_process.php" method="POST">
                <input type="text" name="username" id="username" placeholder="Usuário" required class="w-full p-2 border rounded mb-4 bg-gray-50 dark:bg-gray-700">
                <input type="password" name="password" id="password" placeholder="Senha" required class="w-full p-2 border rounded mb-4 bg-gray-50 dark:bg-gray-700">
                <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800">Login</button>
            </form>
            <div class="mt-4 text-center">
                <a href="register.php" class="text-blue-500 dark:text-blue-300 hover:underline">Não tem uma conta? Cadastre-se.</a>
            </div>
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
    </body>
</html>