<?php
// config/constants.php
define('APP_NAME', 'MFY Boshqaruv Tizimi');
define('APP_VERSION', '3.0.0');
define('BASE_URL', 'http://localhost/uzb_gis_project');

// Xavfsizlik sozlamalari
define('SESSION_TIMEOUT', 3600); // 1 soat
define('MAX_LOGIN_ATTEMPTS', 5);
define('PASSWORD_MIN_LENGTH', 8);
define('CSRF_TOKEN_LIFETIME', 1800); // 30 daqiqa

// Rollar
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN', 'admin');
define('ROLE_OPERATOR', 'operator');
define('ROLE_USER', 'user');

// Fayl yo'llari
define('UPLOAD_PATH', dirname(__DIR__) . '/public/uploads/');
define('PROFILE_PICS_PATH', 'uploads/profiles/');