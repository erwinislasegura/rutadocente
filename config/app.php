<?php
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$detectedBaseUrl = preg_replace('#/(?:public/)?index\.php$#', '', $scriptName) ?? '';

return [
    'name' => 'Ruta Docente',
    'site_url' => rtrim((string) (getenv('APP_URL') ?: 'https://rutadocente.com'), '/'),
    // Detecta automáticamente instalaciones como /rutadocente y también
    // funciona cuando el dominio apunta directamente a la carpeta public.
    'base_url' => rtrim((string) (getenv('APP_BASE_URL') ?: $detectedBaseUrl), '/'),
    'upload_dir' => dirname(__DIR__) . '/storage/uploads',
    'max_upload_mb' => 15,
    'allowed_extensions' => ['pdf','doc','docx','xls','xlsx','ppt','pptx','zip'],
    'form_upload_dir' => dirname(__DIR__) . '/storage/form-submissions',
    'form_max_upload_mb' => 10,
    'form_allowed_extensions' => ['jpg','jpeg','png','webp','pdf'],
    'form_allowed_mime_types' => ['image/jpeg','image/png','image/webp','application/pdf'],
    'mail_from_address' => (string) (getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@rutadocente.com'),
    'mail_from_name' => (string) (getenv('MAIL_FROM_NAME') ?: 'Ruta Docente'),
    'mail_reply_to' => (string) (getenv('MAIL_REPLY_TO') ?: 'aulaentretenida0@gmail.com'),
];
