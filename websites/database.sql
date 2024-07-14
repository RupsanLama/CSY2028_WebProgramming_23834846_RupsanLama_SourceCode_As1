-- Adminer 4.8.1 MySQL 11.2.2-MariaDB-1:11.2.2+maria~ubu2204 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `administrators`;
CREATE TABLE `administrators` (
  `admin_id` int(2) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `administrators` (`admin_id`, `admin_name`, `email`, `password`) VALUES
(57,	'rupsan',	'rupsan@gmail.com',	'$2y$10$yj2iSENyhO9RqXK.3gZx6Oh3ZdXetw94UV81ZN.WMteG1xOrWS.Iq');

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE `auctions` (
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `end_date` date NOT NULL,
  `created_date` date NOT NULL,
  `category_id` int(3) NOT NULL,
  `auction_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(2) NOT NULL,
  PRIMARY KEY (`auction_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auctions` (`title`, `description`, `end_date`, `created_date`, `category_id`, `auction_id`, `user_id`) VALUES
('Bugatti Chiron',	'Sale Sale Sale',	'2024-04-04',	'2024-03-14',	146,	1125,	15),
('1972 Chevrolet Camaro SS',	'A classic muscle car restored to its original glory. This Camaro SS features a powerful V8 engine and iconic styling.',	'2024-04-30',	'2024-03-15',	146,	1126,	15),
('2018 Tesla Model S',	'An electric luxury sedan known for its sleek design and cutting-edge technology. This Model S offers impressive performance and a spacious interior.',	'2024-05-01',	'2024-03-15',	142,	1127,	15),
('2005 Ford Mustang GT',	'A modern interpretation of an American classic, this Mustang GT boasts a powerful V8 engine and aggressive styling cues.',	'2024-05-01',	'2024-03-15',	143,	1128,	15),
('2022 Toyota Prius Hybrid',	' A fuel-efficient hybrid car perfect for eco-conscious drivers. This Prius combines practicality with advanced hybrid technology.',	'2024-04-18',	'2024-03-15',	147,	1129,	15),
('2016 BMW 3 Series',	'A luxury compact sedan renowned for its sporty handling and upscale interior. This BMW 3 Series offers a perfect blend of performance and comfort.',	'2024-04-30',	'2024-03-15',	144,	1130,	15),
('2019 Audi Q5',	'A versatile and luxurious SUV offering a smooth ride and upscale interior. This Audi Q5 is well-equipped for both urban commutes and weekend adventures.',	'2024-04-26',	'2024-03-15',	141,	1131,	15),
('2017 Mercedes-Benz C-Class Coupe',	'A stylish and luxurious coupe featuring refined craftsmanship and powerful performance. This Mercedes-Benz C-Class Coupe combines elegance with sportiness.',	'2024-05-03',	'2024-03-15',	143,	1132,	15),
('2015 Jeep Wrangler Unlimited',	'An iconic off-road SUV designed for rugged adventures. This Jeep Wrangler Unlimited offers impressive capability and open-air driving experience.',	'2024-05-15',	'2024-03-15',	145,	1133,	15),
('2021 Toyota Camry',	'A reliable and spacious sedan known for its fuel efficiency and comfortable ride. The Toyota Camry offers a blend of practicality and refinement.',	'2024-05-04',	'2024-03-15',	144,	1134,	15);

DROP TABLE IF EXISTS `bids`;
CREATE TABLE `bids` (
  `bid_id` int(3) NOT NULL AUTO_INCREMENT,
  `bid_amount` int(25) NOT NULL,
  `auction_id` int(4) NOT NULL,
  PRIMARY KEY (`bid_id`),
  KEY `auction_id` (`auction_id`),
  CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_name` varchar(25) NOT NULL,
  `category_id` int(3) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`category_name`, `category_id`) VALUES
('Estate',	141),
('Electric',	142),
('Coupe',	143),
('Saloon',	144),
('4x4',	145),
('Sports',	146),
('Hybrid',	147);

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `review_id` int(3) NOT NULL AUTO_INCREMENT,
  `review_description` varchar(255) NOT NULL,
  `auction_id` int(4) NOT NULL,
  `user_id` int(2) NOT NULL,
  `posted_date` date NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`),
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(2) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(15,	'ghising',	'sanjeevghisinglama@gmail.com',	'$2y$10$vcEKnIhwA8SIN5bWfRyjHe39udf/LRzm.MghyH5zEl4HvMlZXg/ey'),
(16,	'bbb',	'la@gmail.com',	'$2y$10$phLEFzQxhjgV0P6x2IR3h.x01YMFR9a50u/z7aPvQKxBDR.goCgPu');

-- 2024-07-14 08:54:24