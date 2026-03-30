-- =====================================================
-- CodeCraft — Ejecutar íntegramente en phpMyAdmin o consola MySQL
-- Usuario: burguillosdylan@gmail.com | Password: Admin@2026
-- =====================================================

CREATE DATABASE IF NOT EXISTS codecraft_auth
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE codecraft_auth;

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
    updated_at              DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_active (is_active, is_verified)
) ENGINE=InnoDB;

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
    CONSTRAINT fk_otp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

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
    INDEX idx_severity_time (severity, created_at),
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

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
    CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (
    company_name,
    company_nit,
    full_name,
    email,
    password,
    role,
    department,
    is_verified,
    is_active
) VALUES (
    'CodeCraft',
    '900123456-1',
    'Dylan Burguillos',
    'burguillosdylan@gmail.com',
    PASSWORD('Admin@2026'),
    'admin',
    'Tecnología',
    1,
    1
);