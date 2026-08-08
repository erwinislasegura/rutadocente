-- Configuración central de Google Analytics 4.
-- La aplicación también crea esta tabla automáticamente al abrir el módulo.
USE rutadocente;
CREATE TABLE IF NOT EXISTS site_settings(
 setting_key VARCHAR(80) PRIMARY KEY,
 setting_value TEXT NOT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO site_settings(setting_key,setting_value)
VALUES('google_analytics','{"measurement_id":"","enabled":false}')
ON DUPLICATE KEY UPDATE setting_key=VALUES(setting_key);
