<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/funciones.php';

// El slug llega por la URL: articulo.php?slug=jugar-en-linux
$slug = $_GET['slug'] ?? '';

$nota = consultar_una(
    'SELECT * FROM publicaciones WHERE slug = ? AND visible = 1',
    [$slug]
);

if ($nota === null) {
    http_response_code(404);
    $pagina_titulo = 'Publicación no encontrada';
    $pagina_activa = 'blog';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="seccion">
        <div class="contenedor" style="text-align:center; max-width:600px;">
            <h1 class="seccion__titulo">No encontramos esa publicación</h1>
            <p class="seccion__subtitulo">Puede que el enlace esté mal escrito o que la hayamos retirado.</p>
            <a href="blog.php" class="boton boton--primario">Volver al blog</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pagina_titulo = $nota['titulo'];
$pagina_activa = 'blog';

// Otras publicaciones para el pie del articulo
$relacionadas = consultar(
    'SELECT titulo, slug, categoria, publicado_en FROM publicaciones
     WHERE visible = 1 AND id <> ? ORDER BY publicado_en DESC LIMIT 3',
    [$nota['id']]
);

require_once __DIR__ . '/includes/header.php';
?>

<article class="seccion">
    <div class="contenedor articulo">
        <header class="articulo__cabecera">
            <div class="entrada__meta" style="justify-content:center; margin-bottom:.8rem;">
                <span class="categoria"><?= e($nota['categoria']) ?></span>
                <span><?= fecha_larga($nota['publicado_en']) ?></span>
                <span><?= e($nota['autor']) ?></span>
            </div>
            <h1><?= e($nota['titulo']) ?></h1>
            <p style="color:var(--texto-suave); font-size:1.08rem;"><?= e($nota['resumen']) ?></p>
        </header>

        <div class="articulo__imagen" style="margin-bottom:2rem;">
            <?= imagen($nota['imagen'], $nota['titulo'], '📰') ?>
        </div>

        <div class="articulo__cuerpo">
            <?php
            // El contenido se guarda como texto plano con parrafos separados por
            // linea en blanco. Se escapa y luego se convierte cada bloque en <p>.
            foreach (preg_split('/\n\s*\n/', trim($nota['contenido'])) as $parrafo) {
                echo '<p>' . e(trim($parrafo)) . '</p>';
            }
            ?>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="blog.php" class="boton boton--contorno">← Volver al blog</a>
        </p>
    </div>
</article>

<section class="seccion seccion--alterna">
    <div class="contenedor">
        <h2 class="seccion__titulo">Otras publicaciones</h2>
        <div class="tarjetas" style="margin-top:2rem;">
            <?php foreach ($relacionadas as $otra): ?>
                <article class="tarjeta aparece">
                    <div class="entrada__meta" style="margin-bottom:.5rem;">
                        <span class="categoria"><?= e($otra['categoria']) ?></span>
                        <span><?= fecha_larga($otra['publicado_en']) ?></span>
                    </div>
                    <h3><?= e($otra['titulo']) ?></h3>
                    <p style="margin-top:.7rem;">
                        <a href="articulo.php?slug=<?= urlencode($otra['slug']) ?>">Leer →</a>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
