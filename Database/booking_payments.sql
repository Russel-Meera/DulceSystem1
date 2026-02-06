CREATE TABLE booking_payments (
  payment_id INT(11) NOT NULL AUTO_INCREMENT,
  booking_id INT(11) NOT NULL,
  client_id INT(11) NOT NULL,
  payment_method_id INT(11) NOT NULL,
  status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  receipt_image VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (payment_id),
  KEY idx_booking_payments_booking (booking_id),
  KEY idx_booking_payments_client (client_id),
  KEY idx_booking_payments_method (payment_method_id),
  CONSTRAINT fk_booking_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings (booking_id) ON DELETE CASCADE,
  CONSTRAINT fk_booking_payments_client FOREIGN KEY (client_id) REFERENCES users (user_id) ON DELETE CASCADE,
  CONSTRAINT fk_booking_payments_method FOREIGN KEY (payment_method_id) REFERENCES payment_methods (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
