-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.30 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных test
CREATE DATABASE IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `test`;

-- Дамп структуры для таблица test.deliveries
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `delivery_number` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_id` int unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `sum` int unsigned NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_deliveries` (`product_id`),
  CONSTRAINT `product_deliveries` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы test.deliveries: ~8 rows (приблизительно)
REPLACE INTO `deliveries` (`id`, `delivery_number`, `product_id`, `quantity`, `sum`, `date`, `created_at`, `updated_at`) VALUES
	(1, '1', 1, 300, 500, '2021-01-01', '2023-02-17 17:17:37', '2023-02-17 17:18:42'),
	(2, 't-500', 2, 10, 6000, '2021-01-02', '2023-02-17 17:58:34', '2023-02-17 17:58:34'),
	(3, '12-ТР-777', 3, 100, 500, '2021-01-13', '2023-02-17 17:59:57', '2023-02-17 18:00:16'),
	(4, '12-ТР-778', 3, 50, 300, '2021-01-14', '2023-02-17 18:01:05', '2023-02-17 18:01:05'),
	(5, '12-ТР-779', 3, 77, 539, '2021-01-20', '2023-02-17 18:01:05', '2023-02-17 18:02:43'),
	(6, '12-ТР-887', 3, 32, 176, '2021-01-30', '2023-02-17 18:01:05', '2023-02-17 18:03:27'),
	(7, '12-ТР-977', 3, 94, 554, '2021-02-01', '2023-02-17 18:01:05', '2023-02-17 18:04:12'),
	(8, '12-ТР-979', 3, 200, 1000, '2021-02-05', '2023-02-17 18:01:05', '2023-02-17 18:05:20');

-- Дамп структуры для таблица test.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы test.products: ~2 rows (приблизительно)
REPLACE INTO `products` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'колбаса', '2023-02-17 17:13:27', '2023-02-17 17:13:27'),
	(2, 'пармезан', '2023-02-17 17:15:00', '2023-02-17 17:15:00'),
	(3, 'левый носок', '2023-02-17 17:15:19', '2023-02-17 17:15:19');

-- Дамп структуры для таблица test.sendings
CREATE TABLE IF NOT EXISTS `sendings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `product_id` int unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `price` int unsigned NOT NULL,
  `sum` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `products_sendings` (`product_id`),
  CONSTRAINT `products_sendings` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы test.sendings: ~14 rows (приблизительно)
REPLACE INTO `sendings` (`id`, `date`, `product_id`, `quantity`, `price`, `sum`, `created_at`, `updated_at`) VALUES
	(1, '2021-01-13', 3, 1, 250, 250, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(2, '2021-01-14', 3, 1, 183, 183, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(3, '2021-01-15', 3, 2, 183, 366, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(4, '2021-01-16', 3, 3, 183, 549, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(5, '2021-01-17', 3, 5, 183, 915, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(6, '2021-01-18', 3, 8, 183, 1464, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(7, '2021-01-19', 3, 13, 183, 2379, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(8, '2021-01-20', 3, 21, 179, 3759, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(9, '2021-01-21', 3, 34, 179, 6086, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(10, '2021-01-22', 3, 55, 179, 9845, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(11, '2021-01-23', 3, 89, 179, 15931, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(12, '2021-01-24', 3, 144, 179, 25776, '2023-02-18 23:35:30', '2023-02-18 23:35:30'),
	(13, '2021-01-30', 3, 233, 113, 26329, '2023-02-18 23:35:30', '2023-02-18 23:35:30');

-- Дамп структуры для таблица test.stock
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '0',
  `sum` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  CONSTRAINT `product_stock` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы test.stock: ~3 rows (приблизительно)
REPLACE INTO `stock` (`id`, `product_id`, `quantity`, `sum`) VALUES
	(2, 1, 300, 5000),
	(3, 2, 10, 6000),
	(14, 3, 353, 423);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
