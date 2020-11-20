-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2020 at 02:47 AM
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

DROP PROCEDURE IF EXISTS `Get all budget category info`$$
CREATE DEFINER=`siit`@`localhost` PROCEDURE `Get all budget category info` (IN `user_id` INT)  NO SQL
BEGIN
    DECLARE i INT;
    
    DROP TEMPORARY TABLE IF EXISTS `tmp`;
    CREATE TEMPORARY TABLE `tmp` ( `budget_id` INT NOT NULL , `used` FLOAT, `maximum` FLOAT NOT NULL ) ENGINE = InnoDB;
    
    SET @relative_main = (SELECT c.relative FROM currency c, user u WHERE u.currency_id = c.id AND u.id = user_id);
    
    SET @n = (SELECT COUNT(*) FROM budget b WHERE b.user_id = user_id AND b.start_time < NOW() AND NOW() < b.end_time);
    SET i=0;
    WHILE i<@n DO 
        SET @b = (SELECT b.id FROM budget b WHERE b.user_id = user_id AND b.start_time < NOW() AND NOW() < b.end_time LIMIT 1 OFFSET i);
        
    	CALL `Get budget category info`(@b, @t);
        INSERT INTO `tmp` VALUES (@b, @t, (SELECT maximum FROM budget WHERE id = @b)/@relative_main);
        
        SET i = i + 1;
    END WHILE;
    
    SELECT * FROM `tmp`;
    DROP TEMPORARY TABLE IF EXISTS `tmp`;
END$$

DROP PROCEDURE IF EXISTS `Get budget category info`$$
CREATE DEFINER=`siit`@`localhost` PROCEDURE `Get budget category info` (IN `budget_id` INT, OUT `used` FLOAT)  NO SQL
BEGIN
    # be careful. do one check that NOW() > start_time
	SET @relative_main = (SELECT c.relative FROM currency c, user u, budget b WHERE u.currency_id = c.id AND b.user_id = u.id AND b.id = budget_id);
    
    #find correct start and stop time depending on frequency
    SET @frequency = (SELECT frequency FROM budget WHERE id = budget_id);
    IF @frequency = 'DAILY' THEN
    	SET @freq_t = 1;
    ELSEIF @frequency = 'WEEKLY' THEN
    	SET @freq_t = 7;
    ELSEIF @frequency = 'MONTHLY' THEN
    	SET @freq_t = 30;
    END IF;
    
    SET @seconds_since_start = (SELECT TIMESTAMPDIFF(SECOND, NOW(), (SELECT start_time FROM budget WHERE id = budget_id)) MOD (@freq_t*86400.0));
    
    #SELECT NOW();
    #SELECT @seconds_since_start;
    
    SET @start_time_cycle = (SELECT TIMESTAMPADD(SECOND, @seconds_since_start, NOW()));
    
    #SELECT @start_time_cycle;
    
    SET @end_time_cycle = (SELECT TIMESTAMPADD(DAY, @freq_t, @start_time_cycle));
    
    #SELECT @end_time_cycle;
    
	SELECT LEAST(@end_time_cycle, (SELECT end_time FROM budget WHERE id = budget_id)) INTO @end_time_cycle_clamp;
    
    #SELECT @end_time_cycle_clamp;
	
    SELECT COALESCE(SUM(total_main_currency), 0) INTO used FROM
    (
	SELECT t.wallet_id, SUM(t.amount), c.relative, (SUM(t.amount)*c.relative) AS total_usd,
    ((SUM(t.amount)*c.relative)/@relative_main) AS total_main_currency 
    FROM transaction t, wallet w, currency c, budget b 
    WHERE b.id = budget_id AND t.category = b.category AND w.id = t.wallet_id AND w.currency_id = c.id AND t.time_created BETWEEN @start_time_cycle AND @end_time_cycle_clamp
    GROUP BY t.wallet_id
    ) AS transaction_per_wallet;
    

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
	SET @c = (SELECT COUNT(category) FROM budget WHERE category = NEW.category AND NEW.start_time <= end_time);
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
(1, 'USD', 1, '2020-11-20 00:00:02'),
(2, 'THB', 0.0329162, '2020-11-20 00:00:02');

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
