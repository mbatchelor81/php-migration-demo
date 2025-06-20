CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status ENUM('pending','ready') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL,
  ready_at DATETIME NULL
);
