-- ============================================================
-- TopBlex — Esquema SQL completo
-- Generado para coincidir exactamente con las migraciones Laravel
-- Pega este script en MySQL Workbench o phpMyAdmin
-- ============================================================

CREATE DATABASE IF NOT EXISTS `topblex`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `topblex`;

SET FOREIGN_KEY_CHECKS = 0;

-- =========================================
-- LARAVEL INTERNALS
-- =========================================

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 1. USERS
-- =========================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `phone` VARCHAR(255) NULL,
    `city` VARCHAR(255) NULL,
    `address` VARCHAR(255) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 2. CATEGORIES
-- =========================================
CREATE TABLE IF NOT EXISTS `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categories_slug_unique` (`slug`),
    KEY `categories_parent_id_foreign` (`parent_id`),
    CONSTRAINT `categories_parent_id_foreign`
        FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 3. COLLECTIONS
-- =========================================
CREATE TABLE IF NOT EXISTS `collections` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `imagen` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `collections_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 4. PRODUCTS
-- =========================================
CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `short_description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `sku` VARCHAR(255) NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `category_id` BIGINT UNSIGNED NULL,
    `collection_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_slug_unique` (`slug`),
    UNIQUE KEY `products_sku_unique` (`sku`),
    KEY `products_category_id_foreign` (`category_id`),
    KEY `products_collection_id_foreign` (`collection_id`),
    CONSTRAINT `products_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `products_collection_id_foreign`
        FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 5. PRODUCT IMAGES
-- =========================================
CREATE TABLE IF NOT EXISTS `product_images` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `alt` VARCHAR(255) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `es_principal` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `product_images_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 6. COLORS
-- =========================================
CREATE TABLE IF NOT EXISTS `colors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NULL,
    `slug` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `colors_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 7. SIZES
-- =========================================
CREATE TABLE IF NOT EXISTS `sizes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `abreviatura` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 8. PRODUCT VARIANTS
-- =========================================
CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `color_id` BIGINT UNSIGNED NULL,
    `size_id` BIGINT UNSIGNED NULL,
    `sku` VARCHAR(255) NULL,
    `price` DECIMAL(10,2) NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `product_variants_product_id_foreign` (`product_id`),
    KEY `product_variants_color_id_foreign` (`color_id`),
    KEY `product_variants_size_id_foreign` (`size_id`),
    CONSTRAINT `product_variants_product_id_foreign`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `product_variants_color_id_foreign`
        FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `product_variants_size_id_foreign`
        FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 9. CARTS
-- =========================================
CREATE TABLE IF NOT EXISTS `carts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `session_id` VARCHAR(255) NULL,
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `carts_user_id_foreign` (`user_id`),
    KEY `carts_session_id_index` (`session_id`),
    CONSTRAINT `carts_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 10. FAVORITES
-- =========================================
CREATE TABLE IF NOT EXISTS `favorites` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 11. ADDRESSES
-- =========================================
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 12. CART ITEMS
-- =========================================
CREATE TABLE IF NOT EXISTS `cart_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 13. ORDERS
-- =========================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(255) NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `currency` VARCHAR(10) NOT NULL DEFAULT 'EUR',
    `shipping_address` TEXT NULL,
    `notes` TEXT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `orders_code_unique` (`code`),
    KEY `orders_user_id_foreign` (`user_id`),
    CONSTRAINT `orders_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 14. ORDER ITEMS
-- =========================================
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `order_id` BIGINT UNSIGNED NULL,
    `product_id` BIGINT UNSIGNED NULL,
    `product_name` VARCHAR(255) NULL,
    `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `quantity` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`),
    KEY `order_items_order_id_foreign` (`order_id`),
    KEY `order_items_product_id_foreign` (`product_id`),
    CONSTRAINT `order_items_order_id_foreign`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `order_items_product_id_foreign`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 15. PAYMENTS
-- =========================================
CREATE TABLE IF NOT EXISTS `payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `order_id` BIGINT UNSIGNED NULL,
    `method` VARCHAR(255) NOT NULL DEFAULT 'card',
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
    `reference` VARCHAR(255) NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `paid_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `payments_order_id_foreign` (`order_id`),
    CONSTRAINT `payments_order_id_foreign`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 16. FAQS
-- =========================================
CREATE TABLE IF NOT EXISTS `faqs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `pregunta` VARCHAR(255) NULL,
    `respuesta` TEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 17. RETURN REQUESTS
-- =========================================
CREATE TABLE IF NOT EXISTS `return_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 18. BLOG POSTS
-- =========================================
CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 19. CONTACT MESSAGES
-- =========================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NULL,
    `email` VARCHAR(255) NULL,
    `subject` VARCHAR(255) NULL,
    `message` TEXT NULL,
    PRIMARY KEY (`id`),
    KEY `contact_messages_user_id_foreign` (`user_id`),
    CONSTRAINT `contact_messages_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 20. NEWSLETTER SUBSCRIBERS
-- =========================================
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================
-- DATOS INICIALES
-- =========================================

INSERT INTO `categories` (`name`, `slug`, `description`, `parent_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Hombre', 'hombre', 'Category principal de hombre', NULL, 1, 0, NOW(), NOW()),
('Mujer',  'mujer',  'Category principal de mujer',  NULL, 1, 0, NOW(), NOW());

INSERT INTO `categories` (`name`, `slug`, `description`, `parent_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Chandal',    'hombre-chandal',    'Chandal para hombre',    1, 1, 1, NOW(), NOW()),
('Vaqueros',   'hombre-vaqueros',   'Vaqueros para hombre',   1, 1, 1, NOW(), NOW()),
('Zapatos',    'hombre-zapatos',    'Zapatos para hombre',    1, 1, 1, NOW(), NOW()),
('Accesorios', 'hombre-accesorios', 'Accesorios para hombre', 1, 1, 1, NOW(), NOW()),
('Chandal',    'mujer-chandal',    'Chandal para mujer',    2, 1, 1, NOW(), NOW()),
('Vaqueros',   'mujer-vaqueros',   'Vaqueros para mujer',   2, 1, 1, NOW(), NOW()),
('Zapatos',    'mujer-zapatos',    'Zapatos para mujer',    2, 1, 1, NOW(), NOW()),
('Accesorios', 'mujer-accesorios', 'Accesorios para mujer', 2, 1, 1, NOW(), NOW());

INSERT INTO `products` (`name`, `slug`, `description`, `short_description`, `price`, `sku`, `stock`, `is_visible`, `is_featured`, `category_id`, `created_at`, `updated_at`) VALUES
('Chandal Urban Hombre',    'chandal-urban-hombre',    'Product premium de chandal para hombre.',    'Chandal Urban Hombre',    79.90, 'TBX-CHANDAL-URBAN-HOMBRE',    24, 1, 1, 3,  NOW(), NOW()),
('Chandal Tech Hombre',     'chandal-tech-hombre',     'Product premium de chandal para hombre.',    'Chandal Tech Hombre',     89.90, 'TBX-CHANDAL-TECH-HOMBRE',     18, 1, 0, 3,  NOW(), NOW()),
('Vaqueros Slim Hombre',    'vaqueros-slim-hombre',    'Product premium de vaqueros para hombre.',   'Vaqueros Slim Hombre',    59.90, 'TBX-VAQUEROS-SLIM-HOMBRE',    30, 1, 1, 4,  NOW(), NOW()),
('Vaqueros Relax Hombre',   'vaqueros-relax-hombre',   'Product premium de vaqueros para hombre.',   'Vaqueros Relax Hombre',   64.90, 'TBX-VAQUEROS-RELAX-HOMBRE',   22, 1, 0, 4,  NOW(), NOW()),
('Zapatos Runner Hombre',   'zapatos-runner-hombre',   'Product premium de zapatos para hombre.',    'Zapatos Runner Hombre',   99.90, 'TBX-ZAPATOS-RUNNER-HOMBRE',   16, 1, 1, 5,  NOW(), NOW()),
('Zapatos Casual Hombre',   'zapatos-casual-hombre',   'Product premium de zapatos para hombre.',    'Zapatos Casual Hombre',   84.90, 'TBX-ZAPATOS-CASUAL-HOMBRE',   21, 1, 0, 5,  NOW(), NOW()),
('Accesorio Gorra Hombre',  'accesorio-gorra-hombre',  'Product premium de accesorios para hombre.', 'Accesorio Gorra Hombre',  24.90, 'TBX-ACCESORIO-GORRA-HOMBRE',  35, 1, 0, 6,  NOW(), NOW()),
('Accesorio Mochila Hombre','accesorio-mochila-hombre','Product premium de accesorios para hombre.', 'Accesorio Mochila Hombre',49.90, 'TBX-ACCESORIO-MOCHILA-HOMBRE',14, 1, 1, 6,  NOW(), NOW()),
('Chandal Urban Mujer',     'chandal-urban-mujer',     'Product premium de chandal para mujer.',     'Chandal Urban Mujer',     76.90, 'TBX-CHANDAL-URBAN-MUJER',     26, 1, 1, 7,  NOW(), NOW()),
('Chandal Soft Mujer',      'chandal-soft-mujer',      'Product premium de chandal para mujer.',     'Chandal Soft Mujer',      82.90, 'TBX-CHANDAL-SOFT-MUJER',      19, 1, 0, 7,  NOW(), NOW()),
('Vaqueros Skinny Mujer',   'vaqueros-skinny-mujer',   'Product premium de vaqueros para mujer.',    'Vaqueros Skinny Mujer',   62.90, 'TBX-VAQUEROS-SKINNY-MUJER',   28, 1, 1, 8,  NOW(), NOW()),
('Vaqueros Wide Mujer',     'vaqueros-wide-mujer',     'Product premium de vaqueros para mujer.',    'Vaqueros Wide Mujer',     66.90, 'TBX-VAQUEROS-WIDE-MUJER',     20, 1, 0, 8,  NOW(), NOW()),
('Zapatos Runner Mujer',    'zapatos-runner-mujer',    'Product premium de zapatos para mujer.',     'Zapatos Runner Mujer',    94.90, 'TBX-ZAPATOS-RUNNER-MUJER',    17, 1, 1, 9,  NOW(), NOW()),
('Zapatos Casual Mujer',    'zapatos-casual-mujer',    'Product premium de zapatos para mujer.',     'Zapatos Casual Mujer',    88.90, 'TBX-ZAPATOS-CASUAL-MUJER',    15, 1, 0, 9,  NOW(), NOW()),
('Accesorio Bolso Mujer',   'accesorio-bolso-mujer',   'Product premium de accesorios para mujer.',  'Accesorio Bolso Mujer',   54.90, 'TBX-ACCESORIO-BOLSO-MUJER',   13, 1, 1, 10, NOW(), NOW()),
('Accesorio Cinturon Mujer','accesorio-cinturon-mujer','Product premium de accesorios para mujer.',  'Accesorio Cinturon Mujer',22.90, 'TBX-ACCESORIO-CINTURON-MUJER',27, 1, 0, 10, NOW(), NOW());

INSERT INTO `product_images` (`product_id`, `url`, `alt`, `sort_order`, `es_principal`, `created_at`, `updated_at`) VALUES
(1,  'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=1200&q=80',  'Chandal Urban Hombre',    0, 1, NOW(), NOW()),
(2,  'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=1200&q=80',  'Chandal Tech Hombre',     0, 1, NOW(), NOW()),
(3,  'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=1200&q=80',     'Vaqueros Slim Hombre',    0, 1, NOW(), NOW()),
(4,  'https://images.unsplash.com/photo-1604176424472-9d2f9f3f94a5?auto=format&fit=crop&w=1200&q=80', 'Vaqueros Relax Hombre',   0, 1, NOW(), NOW()),
(5,  'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',    'Zapatos Runner Hombre',   0, 1, NOW(), NOW()),
(6,  'https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=1200&q=80', 'Zapatos Casual Hombre',   0, 1, NOW(), NOW()),
(7,  'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=1200&q=80', 'Accesorio Gorra Hombre',  0, 1, NOW(), NOW()),
(8,  'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'Accesorio Mochila Hombre',0, 1, NOW(), NOW()),
(9,  'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80', 'Chandal Urban Mujer',     0, 1, NOW(), NOW()),
(10, 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=80', 'Chandal Soft Mujer',      0, 1, NOW(), NOW()),
(11, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80', 'Vaqueros Skinny Mujer',   0, 1, NOW(), NOW()),
(12, 'https://images.unsplash.com/photo-1551232864-3f0890e580d9?auto=format&fit=crop&w=1200&q=80',    'Vaqueros Wide Mujer',     0, 1, NOW(), NOW()),
(13, 'https://images.unsplash.com/photo-1463100099107-aa0980c362e6?auto=format&fit=crop&w=1200&q=80', 'Zapatos Runner Mujer',    0, 1, NOW(), NOW()),
(14, 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80', 'Zapatos Casual Mujer',    0, 1, NOW(), NOW()),
(15, 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=1200&q=80', 'Accesorio Bolso Mujer',   0, 1, NOW(), NOW()),
(16, 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=1200&q=80', 'Accesorio Cinturon Mujer',0, 1, NOW(), NOW());

INSERT INTO `faqs` (`pregunta`, `respuesta`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('Cuanto tarda el envio?',       'Los orders nacionales tardan entre 24 y 72 horas laborables segun la city.', 1, 1, NOW(), NOW()),
('Puedo devolver un producto?',  'Si, tienes 30 dias desde la recepcion para solicitar devolucion si el producto esta en buen status.', 2, 1, NOW(), NOW()),
('Como se calcula la talla?',    'En cada producto tienes una guia de talla recomendada. Si dudas, contactanos y te asesoramos.', 3, 1, NOW(), NOW()),
('Que methods de pago aceptan?', 'Aceptamos card, transferencia y methods digitales disponibles en el checkout.', 4, 1, NOW(), NOW());
