-- Adminer 4.8.1 MySQL 11.6.2-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET NAMES utf8mb4;

CREATE DATABASE `traffic` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `traffic`;

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `full_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `model` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `image_path` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL,
  `plate_number` varchar(45) NOT NULL,
  `locations_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_id` (`locations_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`locations_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `violators`;
CREATE TABLE `violators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicles_id` int(11) NOT NULL,
  `vehicles_locations_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_id` (`vehicles_id`),
  KEY `vehicles_locations_id` (`vehicles_locations_id`),
  CONSTRAINT `violators_ibfk_1` FOREIGN KEY (`vehicles_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `violators_ibfk_2` FOREIGN KEY (`vehicles_locations_id`) REFERENCES `vehicles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


-- 2025-02-26 18:40:40
