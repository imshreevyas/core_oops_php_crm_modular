
CREATE DATABASE core_crm DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE customer(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191),
    phone VARCHAR(50),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    postal_code VARCHAR(30),
    source VARCHAR(100),     -- e.g., 'excel import', 'web'
    status VARCHAR(50) DEFAULT 'active',
    tags JSON NULL,          -- flexible labels
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_email (email),
    INDEX idx_phone (phone),
    INDEX idx_status (status)
)ENGINE=innoDB DEFAULT CHARSET=utf8mb4;