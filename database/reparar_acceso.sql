-- Ruta Docente: reparación de los accesos iniciales.
-- Ejecutar una sola vez en la base de datos configurada en config/database.local.php.

START TRANSACTION;

INSERT INTO roles (name)
VALUES ('administrador'), ('docente')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO subjects (name)
VALUES ('Matemática')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO users (
    first_name, last_name, email, phone, role_id, subject_id,
    password, test_enabled, tabulator_enabled, active
)
VALUES (
    'Administrador', 'Ruta Docente', 'admin@rutadocente.cl', '+56 9 7577 8434',
    (SELECT id FROM roles WHERE name = 'administrador' LIMIT 1), NULL,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', 1, 1, 1
)
ON DUPLICATE KEY UPDATE
    role_id = VALUES(role_id),
    password = VALUES(password),
    active = 1;

INSERT INTO users (
    first_name, last_name, email, phone, role_id, subject_id,
    password, test_enabled, tabulator_enabled, active
)
VALUES (
    'Docente', 'Demostración', 'docente@rutadocente.cl', '+56 9 0000 0000',
    (SELECT id FROM roles WHERE name = 'docente' LIMIT 1),
    (SELECT id FROM subjects WHERE name = 'Matemática' LIMIT 1),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', 1, 1, 1
)
ON DUPLICATE KEY UPDATE
    role_id = VALUES(role_id),
    subject_id = VALUES(subject_id),
    password = VALUES(password),
    test_enabled = 1,
    tabulator_enabled = 1,
    active = 1;

COMMIT;

SELECT email, active
FROM users
WHERE email IN ('admin@rutadocente.cl', 'docente@rutadocente.cl');
