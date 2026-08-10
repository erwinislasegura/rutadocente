-- Convierte el formulario público único en un gestor de múltiples formularios.
-- Ejecutar dentro de la base de datos seleccionada en phpMyAdmin.
-- Puede importarse nuevamente: cada cambio comprueba primero si ya existe.

ALTER TABLE public_form_settings MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_settings' AND COLUMN_NAME='name')=0,
 'ALTER TABLE public_form_settings ADD COLUMN name VARCHAR(160) NULL AFTER id',
 'SELECT ''La columna name ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_settings' AND COLUMN_NAME='slug')=0,
 'ALTER TABLE public_form_settings ADD COLUMN slug VARCHAR(120) NULL AFTER name',
 'SELECT ''La columna slug ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_settings' AND COLUMN_NAME='created_at')=0,
 'ALTER TABLE public_form_settings ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
 'SELECT ''La columna created_at ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

UPDATE public_form_settings
SET name=COALESCE(NULLIF(name,''),title),
    slug=COALESCE(NULLIF(slug,''),IF(id=1,'inscripcion',CONCAT('formulario-',id)));

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_settings' AND INDEX_NAME='uq_public_form_slug')=0,
 'ALTER TABLE public_form_settings ADD UNIQUE KEY uq_public_form_slug(slug)',
 'SELECT ''El índice uq_public_form_slug ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_fields' AND COLUMN_NAME='form_id')=0,
 'ALTER TABLE public_form_fields ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id',
 'SELECT ''La columna public_form_fields.form_id ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_fields' AND INDEX_NAME='idx_public_form_fields_form')=0,
 'ALTER TABLE public_form_fields ADD INDEX idx_public_form_fields_form(form_id)',
 'SELECT ''El índice idx_public_form_fields_form ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_submissions' AND COLUMN_NAME='form_id')=0,
 'ALTER TABLE public_form_submissions ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id',
 'SELECT ''La columna public_form_submissions.form_id ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_submissions' AND INDEX_NAME='idx_public_form_submissions_form')=0,
 'ALTER TABLE public_form_submissions ADD INDEX idx_public_form_submissions_form(form_id)',
 'SELECT ''El índice idx_public_form_submissions_form ya existe'''
);
PREPARE migration_statement FROM @migration_sql; EXECUTE migration_statement; DEALLOCATE PREPARE migration_statement;
