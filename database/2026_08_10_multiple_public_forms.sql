-- Convierte el formulario público único en un gestor de múltiples formularios.
USE rutadocente;

ALTER TABLE public_form_settings
 MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT,
 ADD COLUMN name VARCHAR(160) NULL AFTER id,
 ADD COLUMN slug VARCHAR(120) NULL AFTER name,
 ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP BEFORE updated_at;

UPDATE public_form_settings
SET name=COALESCE(NULLIF(name,''),title),
    slug=COALESCE(NULLIF(slug,''),IF(id=1,'inscripcion',CONCAT('formulario-',id)));

ALTER TABLE public_form_settings ADD UNIQUE KEY uq_public_form_slug(slug);
ALTER TABLE public_form_fields ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_public_form_fields_form(form_id);
ALTER TABLE public_form_submissions ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_public_form_submissions_form(form_id);
