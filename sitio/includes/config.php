<?php
/**
 * Configuracion general del sitio.
 * Todo lo que el grupo necesite cambiar (nombre, contacto, credenciales)
 * esta en este archivo. Ningun otro archivo trae datos "quemados".
 */

// --- Identidad del sitio -------------------------------------------
define('SITIO_NOMBRE',  'NivelUp');
define('SITIO_LEMA',    'Taller y tienda de PC gamer');
define('SITIO_DESC',    'Armamos computadores a la medida, instalamos el sistema operativo y los dejamos listos para jugar. Sin cobrar de mas por piezas que no necesitas.');

// --- Datos de contacto ---------------------------------------------
define('CONTACTO_CORREO',   'hola@nivelup.local');
define('CONTACTO_TELEFONO', '+593 (6) 272 1983');
define('CONTACTO_WHATSAPP', '593990000000'); // solo digitos, con indicativo
define('CONTACTO_DIRECCION','PUCE Esmeraldas · Espejo y Subida a Santa Cruz, Esmeraldas');
define('CONTACTO_HORARIO',  'Lunes a sabado, 9:00 a 19:00');

// Coordenadas del punto que marca el mapa de la pagina de contacto.
// APROXIMADAS: verificar en openstreetmap.org (clic derecho sobre el punto
// exacto -> "Mostrar direccion") y reemplazar aqui los dos numeros.
define('MAPA_LAT',  0.97440);
define('MAPA_LON', -79.65280);

// --- Redes sociales -------------------------------------------------
// La clave debe coincidir con el nombre del icono en includes/redes.php
$REDES = [
    'instagram' => 'https://instagram.com/',
    'tiktok'    => 'https://tiktok.com/',
    'youtube'   => 'https://youtube.com/',
    'discord'   => 'https://discord.com/',
    'facebook'  => 'https://facebook.com/',
    'github'    => 'https://github.com/',
];

// Nombre legible de cada red, para el texto alternativo del enlace
$REDES_NOMBRE = [
    'instagram' => 'Instagram', 'tiktok'   => 'TikTok',
    'youtube'   => 'YouTube',   'discord'  => 'Discord',
    'facebook'  => 'Facebook',  'github'   => 'GitHub',
];

// --- Base de datos ---------------------------------------------------
define('BD_HOST',   'localhost');
define('BD_NOMBRE', 'nivelup');
define('BD_USUARIO','nivelup_app');
define('BD_CLAVE',  'CambiarEstaClave2026');

// --- Entorno ---------------------------------------------------------
// En el servidor de entrega dejar en false para no mostrar errores al visitante.
define('MODO_DESARROLLO', true);

if (MODO_DESARROLLO) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

date_default_timezone_set('America/Bogota');
