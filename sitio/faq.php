<?php
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Preguntas frecuentes';
$pagina_activa = 'faq';

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Preguntas frecuentes</h1>
        <p class="seccion__subtitulo">
            Las dudas que más nos llegan antes de comprar. Si la tuya no está,
            escríbenos y te respondemos el mismo día.
        </p>
    </div>
</section>

<section class="seccion" style="padding-top:0;">
    <div class="contenedor">
        <div class="faq">
            <?php foreach (preguntas_frecuentes() as $i => [$pregunta, $respuesta]): ?>
                <div class="faq__item aparece">
                    <button class="faq__pregunta" aria-expanded="false">
                        <?= e($pregunta) ?>
                    </button>
                    <div class="faq__respuesta">
                        <p><?= e($respuesta) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="seccion seccion--alterna">
    <div class="contenedor" style="text-align:center; max-width:700px;">
        <h2 class="seccion__titulo">¿Te quedó una duda?</h2>
        <p class="seccion__subtitulo">Pregunta sin pena. Preferimos explicar diez veces que vender algo que no necesitas.</p>
        <a href="contacto.php" class="boton boton--primario">Hacer una pregunta</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
