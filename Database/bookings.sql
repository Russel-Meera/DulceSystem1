CREATE TABLE `bookings` (
  `booking_id` INT(11) NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `chapel_id` INT(11) NOT NULL,
  `service_date` DATE NOT NULL,
  `service_time` TIME NOT NULL,
  `booking_status` ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL,
  `total_amount` DECIMAL(10,2) NULL,
  `payment_status` ENUM('unpaid','partial','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`booking_id`),
  KEY `idx_bookings_client_id` (`client_id`),
  KEY `idx_bookings_chapel_id` (`chapel_id`),
  CONSTRAINT `fk_bookings_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `fk_bookings_chapel` FOREIGN KEY (`chapel_id`) REFERENCES `chapel_services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
