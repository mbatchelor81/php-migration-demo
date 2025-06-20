CREATE TABLE IF NOT EXISTS menu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(8,2) NOT NULL
);

INSERT INTO menu (name, price) VALUES
  ('Margherita Pizza', 12.00),
  ('Caesar Salad', 8.50),
  ('Spaghetti Bolognese', 14.25),
  ('Garlic Bread', 5.00)
ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price);
