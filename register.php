<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
        <style>
            /* Estilizando o botão de alternância */
            .toggle-container {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 1rem; /* Espaço acima */
            }

            .toggle-container .icon {
                font-size: 1.5rem;
            }

            .toggle-button {
                width: 50px;
                height: 25px;
                border-radius: 50px;
                background-color: #a0f4ff;
                position: relative;
                cursor: pointer;
            }

            .toggle-ball {
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background-color: white;
                position: absolute;
                top: 1.5px;
                left: 1.5px;
                transition: transform 0.3s;
            }

            .dark .toggle-ball {
                transform: translateX(25px);
            }

            /* Estilos personalizados para toda a página */
            .dark .dark\:bg-gray-800 {
                background-color: #2d3748;
            }

            .dark .dark\:text-white {
                color: #ffffff;
            }

            .dark .dark\:text-blue-300 {
                color: #63b3ed;
            }

            /* Estilo personalizado para o card */
            .card {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 16px;
                text-align: center;
                width: 100%; /* Adicionando uma largura padrão */
                max-width: 400px; /* Tamanho máximo para tornar o card menor */
            }

            .dark .card {
                background-color: #2d3748;
                color: #e2e8f0;
            }

            /* Garantindo que o texto nos inputs seja preto */
            input {
                color: black; /* define a cor do texto para preto */
                background-color: white; /* mantém a cor de fundo branca */
                border: 1px solid #d1d5db; /* cor padrão da borda */
            }

            .dark input {
                color: black; /* define a cor do texto para preto no modo escuro */
                background-color: white; /* mantendo cor de fundo branca no modo escuro */
            }

        </style>
        <script>
            function toggleTheme() {
                const htmlElement = document.documentElement;
                const theme = htmlElement.classList.toggle('dark') ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            });
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-800">
        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="container mx-auto p-4 card">
                <h1 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Criar conta</h1>
                <form action="register_process.php" method="POST" class="flex flex-col gap-2">
                    <input type="text" name="username" placeholder="Usuário" class="border rounded px-2 py-1 block w-full" required>
                    <input type="password" name="password" placeholder="Senha" class="border rounded px-2 py-1 block w-full" required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 mt-2">Criar conta</button>
                </form>
                <a href="login.php" class="text-blue-500 dark:text-blue-300 mt-4 inline-block">Já tem uma conta? Faça login</a>
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
