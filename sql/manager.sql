-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2020 at 04:46 PM
-- Server version: 5.7.31
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manager`
--
CREATE DATABASE IF NOT EXISTS `manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `manager`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `Add budget`$$
CREATE DEFINER=`siit`@`localhost` PROCEDURE `Add budget` (IN `user_id` INT, IN `category` VARCHAR(255), IN `maximum` FLOAT, IN `frequency` VARCHAR(255), IN `from_date` DATE, IN `to_date` DATE)  NO SQL
BEGIN
	IF from_date IS NULL THEN
    	IF (frequency LIKE "WEEKLY") THEN
            SET @T = DATE_ADD(NOW(), INTERVAL 1 WEEK);
        ELSEIF (frequency LIKE "MONTHLY") THEN
            SET @T = DATE_ADD(NOW(), INTERVAL 1 MONTH);
        ELSEIF (frequency LIKE "DAILY") THEN
            SET @T = DATE_ADD(NOW(), INTERVAL 1 DAY);
        END IF;
    	INSERT INTO `budget`(`user_id`, `category`, `maximum`, `frequency`, `start_time`, `end_time`) VALUES (user_id, category,  maximum, frequency, NOW(), @T);
    ELSE
  		INSERT INTO `budget`(`user_id`, `category`, `maximum`, `frequency`, `start_time`, `end_time`) VALUES (user_id, category,  maximum, frequency, from_date, to_date);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `Add transaction`$$
CREATE DEFINER=`siit`@`localhost` PROCEDURE `Add transaction` (IN `user_id` INT, IN `wallet_id` INT, IN `title` VARCHAR(255), IN `category` VARCHAR(255), IN `amount` FLOAT, IN `description` VARCHAR(255), IN `recurring` BOOLEAN, IN `recur_freq` VARCHAR(10), IN `recur_times` INT)  NO SQL
BEGIN
	IF recurring THEN
    	SET @T = NOW();
        SET @times = recur_times;
        SET @i = 0;
        my_loop: LOOP
        	IF @i > @times THEN
            	LEAVE my_loop;
            END IF;
            
            INSERT INTO transaction (transaction.user_id, transaction.wallet_id, transaction.title, transaction.category, transaction.amount, transaction.description, transaction.time_created) VALUES (user_id, wallet_id, title, category, amount, description, @T);
            
            SET @i = @i + 1;
            IF (recur_freq LIKE "WEEKLY") THEN
                SET @T = DATE_ADD(@T, INTERVAL 1 WEEK);
            ELSEIF (recur_freq LIKE "MONTHLY") THEN
                SET @T = DATE_ADD(@T, INTERVAL 1 MONTH);
            ELSEIF (recur_freq LIKE "DAILY") THEN
                SET @T = DATE_ADD(@T, INTERVAL 1 DAY);
            END IF;
        END LOOP;
    ELSE 
    	INSERT INTO transaction (transaction.user_id, transaction.wallet_id, transaction.title, transaction.category, transaction.amount, transaction.description) VALUES (user_id, wallet_id, title, category, amount, description);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `maximum` float NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `budget`:
--   `user_id`
--       `user` -> `id`
--

--
-- Triggers `budget`
--
DROP TRIGGER IF EXISTS `Prevent multiple category per user`;
DELIMITER $$
CREATE TRIGGER `Prevent multiple category per user` BEFORE INSERT ON `budget` FOR EACH ROW BEGIN
	SET @c = (SELECT COUNT(category) FROM budget WHERE category = NEW.category);
    IF @c > 0 THEN
    	SIGNAL SQLSTATE '45000'
          SET MESSAGE_TEXT = 'Budget with chosen category already exists!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relative` float NOT NULL,
  `time_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `currency`:
--

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `relative`, `time_modified`) VALUES
(1, 'USD', 1, '2020-11-18 15:06:01'),
(2, 'THB', 0.0331241, '2020-11-18 15:06:01');

--
-- Triggers `currency`
--
DROP TRIGGER IF EXISTS `Update currency modified time`;
DELIMITER $$
CREATE TRIGGER `Update currency modified time` BEFORE UPDATE ON `currency` FOR EACH ROW SET new.time_modified := NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `description` varchar(255) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `transaction`:
--   `user_id`
--       `user` -> `id`
--   `wallet_id`
--       `wallet` -> `id`
--

--
-- Triggers `transaction`
--
DROP TRIGGER IF EXISTS `Add expense`;
DELIMITER $$
CREATE TRIGGER `Add expense` AFTER INSERT ON `transaction` FOR EACH ROW UPDATE wallet SET amount = amount - NEW.amount WHERE id = NEW.wallet_id and user_id = NEW.user_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Delete transaction`;
DELIMITER $$
CREATE TRIGGER `Delete transaction` AFTER DELETE ON `transaction` FOR EACH ROW UPDATE wallet SET amount = amount + OLD.amount WHERE user_id = OLD.user_id AND id = OLD.wallet_id
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Update expense`;
DELIMITER $$
CREATE TRIGGER `Update expense` AFTER UPDATE ON `transaction` FOR EACH ROW BEGIN 
    UPDATE wallet SET amount = amount + OLD.amount WHERE id = OLD.wallet_id and user_id = NEW.user_id;
    UPDATE wallet SET amount = amount - NEW.amount WHERE id = NEW.wallet_id and user_id = NEW.user_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `user`:
--   `currency_id`
--       `currency` -> `id`
--

--
-- Triggers `user`
--
DROP TRIGGER IF EXISTS `Add default wallet`;
DELIMITER $$
CREATE TRIGGER `Add default wallet` AFTER INSERT ON `user` FOR EACH ROW INSERT INTO wallet (user_id, currency_id) VALUES (NEW.id, NEW.currency_id)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Cash',
  `amount` float NOT NULL DEFAULT '0',
  `currency_id` int(11) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `wallet`:
--   `user_id`
--       `user` -> `id`
--   `currency_id`
--       `currency` -> `id`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_id` (`wallet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`);

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `wallet_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
