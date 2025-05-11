<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    error_log("Error: No se pudo cargar el archivo .env. " . $e->getMessage());
}

define('SGBD', $_ENV['DB_SGBD'] ?? 'mysql');
define('HOST', $_ENV['DB_HOST'] ?? 'mariadb');
define('DB', $_ENV['DB_DATABASE'] ?? 'medlink'); // Nombre de tu base de datos actual
define('USER', $_ENV['DB_USERNAME'] ?? 'medlink'); // Usuario de tu base de datos actual
define('PASSWORD', $_ENV['DB_PASSWORD'] ?? '4584'); // ¡Mueve esto a .env!

// ----- Definición para el Directorio de Subidas (usando .env es mejor) -----
define('UPLOADDIR', $_ENV['UPLOAD_DIR'] ?? '/var/www/html/uploads/');

// ----- Definiciones para PHPMailer (usando .env es mejor) -----
define('MAILHOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com'); // Ajustado a tu valor
define('MAILSMTP', $_ENV['MAIL_DRIVER'] ?? 'smtp.gmail.com'); // Puede que MAILHOST y MAILSMTP sean lo mismo o uno sea el driver
define('MAILPORT', $_ENV['MAIL_PORT'] ?? '465');
define('MAILUSER', $_ENV['MAIL_USERNAME'] ?? 'emilioch458@gmail.com');
define('MAILPASSWORD', $_ENV['MAIL_PASSWORD'] ?? 'wwwqjvfgfvuixobq'); // ¡MUY IMPORTANTE: Mueve esto a .env!

// ----- Definiciones para Mercado Pago -----
define('MP_ACCESS_TOKEN', $_ENV['MP_ACCESS_TOKEN'] ?? null);
define('MP_PUBLIC_KEY', $_ENV['MP_PUBLIC_KEY'] ?? null);
define('MP_ENVIRONMENT', $_ENV['MP_ENVIRONMENT'] ?? 'sandbox');

// Opcional: Verificar si las credenciales de Mercado Pago se cargaron
if (!MP_ACCESS_TOKEN) {
    error_log("ADVERTENCIA: MP_ACCESS_TOKEN no está definida. Revisa tu archivo .env y config.php.");
}

?>