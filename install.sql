-- =====================================================
-- Apex Solutions — Base de Datos
-- =====================================================

-- 1. Crear Base de Datos
CREATE DATABASE IF NOT EXISTS enterprise_auth
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE enterprise_auth;

-- 2. Tabla Usuarios
CREATE TABLE IF NOT EXISTS users (
    id                      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name            VARCHAR(150)  NOT NULL,
    company_nit             VARCHAR(30)   NOT NULL,
    full_name               VARCHAR(120)  NOT NULL,
    email                   VARCHAR(180)  NOT NULL UNIQUE,
    password                VARCHAR(255)  NOT NULL,
    role                    ENUM('admin','manager','employee') NOT NULL DEFAULT 'employee',
    department              VARCHAR(80)   DEFAULT NULL,
    is_active               TINYINT(1)    NOT NULL DEFAULT 1,
    is_verified             TINYINT(1)    NOT NULL DEFAULT 0,
    failed_login_attempts   INT UNSIGNED  NOT NULL DEFAULT 0,
    locked_until            DATETIME      DEFAULT NULL,
    last_login              DATETIME      DEFAULT NULL,
    created_at              DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- 3. Tabla Códigos OTP
CREATE TABLE IF NOT EXISTS otp_codes (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    code       CHAR(6)      NOT NULL,
    purpose    ENUM('verification','login') NOT NULL DEFAULT 'verification',
    is_used    TINYINT(1)   NOT NULL DEFAULT 0,
    attempts   TINYINT UNSIGNED NOT NULL DEFAULT 0,
    expires_at DATETIME     NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_purpose (user_id, purpose, is_used, expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabla Auditoría
CREATE TABLE IF NOT EXISTS audit_logs (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED DEFAULT NULL,
    action      VARCHAR(60)  NOT NULL,
    description TEXT         DEFAULT NULL,
    ip_address  VARCHAR(45)  DEFAULT NULL,
    user_agent  VARCHAR(255) DEFAULT NULL,
    severity    ENUM('info','warning','critical') NOT NULL DEFAULT 'info',
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Tabla Sesiones
CREATE TABLE IF NOT EXISTS user_sessions (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED NOT NULL,
    session_token  VARCHAR(64)  NOT NULL UNIQUE,
    ip_address     VARCHAR(45)  DEFAULT NULL,
    user_agent     VARCHAR(255) DEFAULT NULL,
    expires_at     DATETIME     NOT NULL,
    is_active      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (session_token, is_active, expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 6. Insertar Usuario Administrador Establecido
-- IMPORTANTE: El password 'Admin@2026' DEBE estar hasheado con BCRYPT
-- Use PHP's password_verify() compatible hash.
-- =====================================================
INSERT INTO users (
    company_name, company_nit, full_name, email, password, role, department, is_verified, is_active
) VALUES (
    'Apex Solutions',
    '900123456-1',
    'Dylan Burguillos',
    'burguillosdylan@gmail.com',
    '$2y$12$gJTBj2JxRWmNwKrA86uHMe3zMchznL3MOY6SBUMugFjfW1aLDCAh6', -- Admin@2026 BCRYPT Hash
    'admin',
    'Tecnología',
    1,
    1
);
