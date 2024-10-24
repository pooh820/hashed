-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2024 年 10 月 23 日 09:57
-- 伺服器版本： 10.5.22-MariaDB-log
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `player`
--

-- --------------------------------------------------------

--
-- 資料表結構 `strname`
--

CREATE TABLE `strname` (
  `id` int(11) NOT NULL,
  `ename` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_id` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  `deleted_id` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `strname`
--

INSERT INTO `strname` (`id`, `ename`, `name`, `update_time`, `update_id`, `deleted_time`, `deleted_id`) VALUES
(1, '220.134.60.136', 'test2', '2024-10-11 14:53:37', '', '2024-10-23 16:11:41', 'leo'),
(2, '192.168.60.1', 'test5', '2024-10-22 14:06:48', 'test', NULL, NULL),
(3, '192.168.60.2', 'test7', '2024-10-22 13:54:58', 'test', NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'test', '$2y$10$ywgigZqD79Mp3lWnjpfi0eJMxBjsrUJ6R031bTDK/gn4TFvVXlBna', '2024-10-21 17:36:52'),
(2, 'leo', '$2y$10$El8CYHpD.fZjMkO1flw0BO9acGgNxiF6zG0yiLe.E8IvVbCwaMjNC', '2024-10-22 15:12:31'),
(3, 'BBB', '$2y$10$c8Z65vHYUs4bCnGfwwCKd.PvCYG2Su4xKLPL73wCkUK/6K9J28n46', '2024-10-22 18:06:28');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `strname`
--
ALTER TABLE `strname`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `strname`
--
ALTER TABLE `strname`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
