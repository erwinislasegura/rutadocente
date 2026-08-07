-- Módulo de formulario público y recepción de comprobantes.
-- Ejecutar una vez sobre una instalación existente de Ruta Docente.
USE rutadocente;

CREATE TABLE IF NOT EXISTS public_form_settings(
 id TINYINT UNSIGNED PRIMARY KEY,
 eyebrow VARCHAR(80) NOT NULL,
 title VARCHAR(180) NOT NULL,
 intro TEXT,
 information_title VARCHAR(180),
 information_body TEXT,
 status ENUM('open','closed') NOT NULL DEFAULT 'open',
 submit_label VARCHAR(80) NOT NULL DEFAULT 'Enviar inscripción',
 success_title VARCHAR(180) NOT NULL DEFAULT '¡Inscripción recibida!',
 success_message TEXT,
 consent_text TEXT,
 bank_enabled TINYINT(1) NOT NULL DEFAULT 1,
 bank_title VARCHAR(180),
 bank_amount VARCHAR(80),
 bank_holder VARCHAR(180),
 bank_rut VARCHAR(40),
 bank_name VARCHAR(120),
 bank_account_type VARCHAR(100),
 bank_account_number VARCHAR(100),
 bank_email VARCHAR(180),
 bank_instructions TEXT,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS public_form_fields(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 field_key VARCHAR(80) NULL UNIQUE,
 label VARCHAR(180) NOT NULL,
 field_type ENUM('text','email','tel','number','date','textarea','select','radio','checkbox','checkbox_group','file') NOT NULL,
 placeholder VARCHAR(180),
 help_text VARCHAR(500),
 options_json JSON NULL,
 required TINYINT(1) NOT NULL DEFAULT 0,
 active TINYINT(1) NOT NULL DEFAULT 1,
 sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
 max_selections TINYINT UNSIGNED NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS public_form_submissions(
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 contact_name VARCHAR(180),
 contact_email VARCHAR(180),
 answers_json JSON NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_public_form_created(created_at),
 INDEX idx_public_form_email(contact_email)
);

INSERT INTO public_form_settings(id,eyebrow,title,intro,information_title,information_body,status,submit_label,success_title,success_message,consent_text,bank_enabled,bank_title,bank_amount,bank_holder,bank_rut,bank_name,bank_account_type,bank_account_number,bank_email,bank_instructions) VALUES(
 1,
 'INTENSIVO PORTAFOLIO DOCENTE 2026',
 'Vacaciones de invierno',
 'Talleres actualizados según las orientaciones del proceso 2026, con ejemplos prácticos, análisis de evidencias y estrategias para responder a nivel Competente y Destacado.',
 'Talleres incluidos',
 'Clase Grabada 2026\nTrabajo Colaborativo 2026\nCaracterización y Contextualización de Estudiantes\nReflexión Pedagógica y Socioemocional\n\nDirigido a todos los docentes y asignaturas. Modalidad intensiva durante vacaciones de invierno. Incluye material de apoyo, orientaciones, ejemplos y recursos complementarios.',
 'open','Enviar inscripción','¡Recibimos tu inscripción!','Revisaremos tus datos y comprobante. Te contactaremos al correo ingresado para confirmar tu cupo.','Declaro que los datos ingresados son correctos y autorizo su uso para gestionar esta inscripción.',
 0,'Datos para realizar la transferencia','$25.000','','','','','','',
 'Configura la cuenta bancaria desde el panel de administración antes de activar este bloque. El cupo se confirma una vez validado el pago.'
) ON DUPLICATE KEY UPDATE id=id;

INSERT INTO public_form_fields(field_key,label,field_type,placeholder,help_text,options_json,required,active,sort_order,max_selections) VALUES
 ('full_name','Nombre','text','Tu nombre completo','',NULL,1,1,10,0),
 ('email','Correo electrónico','email','nombre@correo.cl','Usaremos este correo para confirmar tu inscripción.',NULL,1,1,20,0),
 ('workshops','Marca los talleres que son de tu interés','checkbox_group','','Puedes priorizar hasta 3 opciones.','["Taller trabajo colaborativo (lunes 22-06-2026, 11:00 hrs)","Taller Caracterización y contextualización (miércoles 24-06-2026, 11:00 hrs)","Taller Reflexión Socioemocional (viernes 26-06-2026, 11:00 hrs)","Taller Clase Grabada (sábado 20-06-2026, 15:30 hrs)"]',1,1,30,3),
 ('receipt','Comprobante de transferencia','file','','Formatos JPG, PNG, WEBP o PDF. Tamaño máximo: 10 MB.',NULL,1,1,40,0)
ON DUPLICATE KEY UPDATE field_key=VALUES(field_key);
