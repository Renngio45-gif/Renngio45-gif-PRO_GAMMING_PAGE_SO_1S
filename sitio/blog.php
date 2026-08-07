<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Blog';
$pagina_activa = 'blog';

$publicaciones = consultar(
    'SELECT * FROM publicaciones WHERE visible = 1 ORDER BY publicado_en DESC'
);

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Blog</h1>
        <p class="seccion__subtitulo">
            Las preguntas que más nos hacen en el mostrador, respondidas con calma
            y sin tecnicismos innecesarios.
        </p>
    </div>
</section>

<section class="seccion" style="padding-top:0;">
    <div class="contenedor">
        <div class="tarjetas">
            <?php foreach ($publicaciones as $nota): ?>
                <article class="entrada aparece">
                    <div class="entrada__imagen"><?= imagen($nota['imagen'], $nota['titulo'], '📰') ?></div>
                    <div class="entrada__cuerpo">
                        <div class="entrada__meta">
                            <span class="categoria"><?= e($nota['categoria']) ?></span>
                            <span><?= fecha_larga($nota['publicado_en']) ?></span>
                        </div>
                        <h3><?= e($nota['titulo']) ?></h3>
                        <p><?= e($nota['resumen']) ?></p>
                        <a href="articulo.php?slug=<?= urlencode($nota['slug']) ?>" class="boton boton--contorno"
                           style="align-self:flex-start; padding:.5rem 1.2rem; font-size:.9rem;">
                            Leer completo
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
