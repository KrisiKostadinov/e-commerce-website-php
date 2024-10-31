CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `phone_number` VARCHAR(20),
    `address` VARCHAR(255),
    `city` VARCHAR(100),
    `state` VARCHAR(100),
    `postal_code` VARCHAR(20),
    `country` VARCHAR(100),
    `role` VARCHAR(10) DEFAULT 'user',
    `email_confirmation_token` VARCHAR(255) NOT NULL,
    `is_email_confirmed` VARCHAR(1) DEFAULT '0',
    `password_reset_token` VARCHAR(255) NULL,
    `token_expiry` INT(11) DEFAULT NULL,
    `password_changed_date` DATETIME DEFAULT NULL,
    `options` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status` ENUM('active', 'inactive', 'banned') DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `permalink` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `image` JSON NULL,
    `parent_id` INT DEFAULT NULL,
    `options` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status` ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `short_description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL,
    `regular_price` DECIMAL(10, 2) NOT NULL,
    `sale_price` DECIMAL(10, 2) DEFAULT NULL,
    `stock_quantity` INT DEFAULT NULL,
    `show_stock` ENUM('yes', 'no') DEFAULT 'no',
    `sku` VARCHAR(100) UNIQUE,
    `category_id` INT NOT NULL,
    `image` JSON DEFAULT NULL,
    `additional_images` JSON DEFAULT NULL,
    `attributes` JSON DEFAULT NULL,
    `options` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status` ENUM('publish', 'draft', 'pending', 'private') DEFAULT 'publish',
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `total` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded') DEFAULT 'pending',
    `payment_method` VARCHAR(100) DEFAULT 'credit_card',
    `shipping_address` VARCHAR(255) NOT NULL,
    `billing_address` VARCHAR(255) NOT NULL,
    `shipping_cost` DECIMAL(10, 2) DEFAULT 0.00,
    `items` JSON NOT NULL,
    `options` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
