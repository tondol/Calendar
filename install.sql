CREATE TABLE IF NOT EXISTS `schedules` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `day` INT NOT NULL,
  `body` TEXT,
  `createdAt` DATETIME,
  KEY `idx_date` (`year`, `month`, `day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
