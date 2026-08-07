<?php
/**
 * Cabecera comun a todas las paginas.
 * Antes de incluir este archivo, definir:
 *   $pagina_titulo  -> titulo de la pestana
 *   $pagina_activa  -> clave del menu a resaltar (inicio, nosotros, ...)
 */
require_once __DIR__ . '/config.php';

$pagina_titulo = $pagina_titulo ?? SITIO_NOMBRE;
$pagina_activa = $pagina_activa ?? '';

$menu = [
    'inicio'    => ['Inicio',    'index.php'],
    'nosotros'  => ['Nosotros',  'nosotros.php'],
    'servicios' => ['Servicios', 'servicios.php'],
    'productos' => ['Productos', 'productos.php'],
    'galeria'   => ['Galería',   'galeria.php'],
    'blog'      => ['Blog',      'blog.php'],
    'faq'       => ['FAQ',       'faq.php'],
    'contacto'  => ['Contacto',  'contacto.php'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars(SITIO_DESC) ?>">
    <title><?= htmlspecialchars($pagina_titulo) ?> | <?= SITIO_NOMBRE ?></title>
    <?php
    /* Version de los archivos estaticos.
       Se toma la fecha de modificacion del archivo y se agrega a la URL. Al
       cambiar el CSS o el JS cambia el numero, y el navegador se ve obligado
       a descargarlos de nuevo en vez de usar la copia vieja que tenia en
       cache. Sin esto, al actualizar el sitio los visitantes siguen viendo
       los estilos anteriores. */
    function version_de(string $relativo): string
    {
        $ruta = __DIR__ . '/../' . $relativo;
        return is_file($ruta) ? (string) filemtime($ruta) : '1';
    }
    ?>
    <link rel="stylesheet" href="css/estilos.css?v=<?= version_de('css/estilos.css') ?>">
    <script>
        // Aplica el tema guardado antes de pintar la pagina (evita el "destello")
        (function () {
            var tema = localStorage.getItem('tema');
            if (tema === 'oscuro' || (tema === null && matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.dataset.tema = 'oscuro';
            }
        })();
    </script>
</head>
<body>

<header class="navbar" id="navbar">
    <div class="contenedor navbar__interior">
        <a class="navbar__logo" href="index.php">
            <span class="navbar__logo-icono" aria-hidden="true">
                <!-- Control de videojuegos con la cruceta en forma de flecha
                     hacia arriba: el "subir de nivel" que da nombre al sitio. -->
                <svg viewBox="0 0 32 32" width="28" height="28" fill="none"
                     stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 10h12a6 6 0 0 1 5.9 7l-1 5.6A3.6 3.6 0 0 1 21.4 24
                             l-1.7-2.4h-7.4L10.6 24a3.6 3.6 0 0 1-5.5-1.4l-1-5.6
                             A6 6 0 0 1 10 10Z"/>
                    <path d="M11.6 17.4h4.2M13.7 15.3v4.2" />
                    <circle cx="21.6" cy="15.9" r="1.1" fill="currentColor" stroke="none"/>
                    <circle cx="23.7" cy="18.4" r="1.1" fill="currentColor" stroke="none"/>
                    <path d="M16 3.4 20.2 8h-8.4L16 3.4Z" fill="currentColor" stroke="none"/>
                </svg>
            </span>
            <span class="navbar__logo-texto">Nivel<span class="navbar__logo-up">Up</span></span>
        </a>

        <nav class="navbar__menu" id="menu" aria-label="Navegación principal">
            <ul>
                <?php foreach ($menu as $clave => [$texto, $enlace]): ?>
                    <li>
                        <a href="<?= $enlace ?>"
                           class="<?= $clave === $pagina_activa ? 'activo' : '' ?>"
                           <?= $clave === $pagina_activa ? 'aria-current="page"' : '' ?>>
                            <?= $texto ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="navbar__acciones">
            <button class="boton-tema" id="boton-tema" aria-label="Cambiar entre modo claro y oscuro">
                <span class="boton-tema__sol" aria-hidden="true">☀</span>
                <span class="boton-tema__luna" aria-hidden="true">🌙</span>
            </button>
            <button class="navbar__hamburguesa" id="hamburguesa" aria-label="Abrir menú" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<?php
/* Aviso visible solo en desarrollo: si falta MySQL, el sitio se muestra
   igual pero productos y blog salen vacios. Sirve para no confundirse
   pensando que el contenido se perdio. */
if (MODO_DESARROLLO && function_exists('bd_disponible') && !bd_disponible()): ?>
    <div class="aviso-desarrollo">
        Sin conexión a MySQL: las secciones de productos y blog se verán vacías.
        El resto del sitio funciona con normalidad.
    </div>
<?php endif; ?>

<main>
