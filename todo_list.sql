-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/05/2024 às 18:16
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `todo_list`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `subtasks`
--

INSERT INTO `subtasks` (`id`, `task_id`, `title`, `completed`) VALUES
(1, 2, 'Teste', 1),
(2, 7, 'Teste 5', 1),
(3, 7, 'Teste 5.1', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `priority` enum('Baixa','Média','Alta','Urgente') DEFAULT 'Média'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `username`, `completed`, `created_at`, `due_date`, `priority`) VALUES
(2, 'Teste', 'Teste', 'Anderson', 1, '2024-05-05 01:10:36', NULL, 'Média'),
(3, 'Teste 2', 'Teste 2', 'Anderson', 1, '2024-05-05 01:16:04', NULL, 'Média'),
(4, 'Teste 3', 'Teste 3', 'Anderson', 1, '2024-05-05 01:17:36', '2024-05-11', 'Média'),
(5, 'Teste 3', 'Teste 3', 'Anderson', 0, '2024-05-05 01:28:12', '2024-05-11', 'Baixa'),
(6, 'Teste 4', 'Teste 4', 'Anderson', 0, '2024-05-05 01:31:54', '0000-00-00', 'Média'),
(7, 'Teste 5', 'Teste 5', 'Anderson', 0, '2024-05-05 01:32:13', '2024-05-25', 'Alta'),
(8, 'Teste 6', 'Teste 6', 'Anderson', 1, '2024-05-05 01:35:28', '2024-06-01', 'Urgente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `password`, `profile_picture`) VALUES
(1, 'Anderson', 'Anderson', 'Barbosa', '$2y$10$trtv8AAqr90jSBmheJe7wOfiSaiopPvS9oKxSQOGiUYiQxfkv5atO', '84004761.jpg'),
(2, 'Anderson', 'Anderson', 'Barbosa', '$2y$10$0T.WVsFM3pNyKF/LWogvAOKEIAo3g.0RCPH/Fv9RyWw.KADNhrqgq', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Índices de tabela `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
