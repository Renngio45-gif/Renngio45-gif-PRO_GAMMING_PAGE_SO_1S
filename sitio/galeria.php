<?php
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Galería';
$pagina_activa = 'galeria';

$elementos = galeria();

$categorias = [
    'todos'   => 'Todo',
    'taller'  => 'El taller',
    'equipos' => 'Equipos armados',
    'eventos' => 'Eventos',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Galería</h1>
        <p class="seccion__subtitulo">
            Fotos del taller, equipos que hemos armado y momentos con la comunidad.
            Hay también un video del armado paso a paso. Haz clic para ampliar.
        </p>
    </div>
</section>

<section class="seccion" style="padding-top:0;">
    <div class="contenedor">

        <!-- Filtros (los maneja principal.js, sin recargar la pagina) -->
        <div class="galeria__filtros">
            <?php foreach ($categorias as $clave => $texto): ?>
                <button class="galeria__filtro <?= $clave === 'todos' ? 'activo' : '' ?>"
                        data-filtro="<?= $clave ?>">
                    <?= $texto ?>
                </button>
            <?php endforeach; ?>
        </div>

        <?php
        // Etiqueta corta de cada categoria para el rotulo de la tarjeta
        $rotulos = ['taller' => 'Taller', 'equipos' => 'Equipos', 'eventos' => 'Eventos'];
        ?>
        <div class="galeria__cuadricula">
            <?php foreach ($elementos as $i => $item): ?>
                <?php
                    $rutaCompleta = __DIR__ . '/img/' . $item['archivo'];
                    $existe  = is_file($rutaCompleta);
                    $esVideo = $item['tipo'] === 'video';
                ?>
                <figure class="galeria__item aparece<?= $esVideo ? ' galeria__item--ancho' : '' ?>"
                        data-categoria="<?= e($item['categoria']) ?>"
                        data-tipo="<?= e($item['tipo']) ?>"
                        data-titulo="<?= e($item['titulo']) ?>"
                        <?= $existe ? 'data-fuente="img/' . e($item['archivo']) . '"' : '' ?>>

                    <?php if ($existe && $esVideo): ?>
                        <video src="img/<?= e($item['archivo']) ?>" muted preload="metadata"></video>
                    <?php elseif ($existe): ?>
                        <img src="img/<?= e($item['archivo']) ?>" alt="<?= e($item['titulo']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="galeria__marcador"><?= ilustracion_para($item['archivo']) ?></div>
                    <?php endif; ?>

                    <?php if ($esVideo): ?>
                        <span class="galeria__reproducir" aria-hidden="true">▶</span>
                    <?php endif; ?>

                    <!-- Rotulo: numero de registro y categoria, como en la ficha
                         de un armado. Da a entender que la galeria es un
                         catalogo ordenado y no un monton de fotos sueltas. -->
                    <span class="galeria__marca" aria-hidden="true">
                        <span class="galeria__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="galeria__cat"><?= e($rotulos[$item['categoria']] ?? $item['categoria']) ?></span>
                    </span>

                    <figcaption class="galeria__leyenda">
                        <?= e($item['titulo']) ?>
                        <?php if ($esVideo): ?><small>Video &middot; haz clic para reproducir</small><?php endif; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Visor a pantalla completa -->
<div class="visor" id="visor" role="dialog" aria-modal="true" aria-label="Vista ampliada">
    <button class="visor__cerrar" aria-label="Cerrar">&times;</button>
    <div class="visor__contenido"></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
