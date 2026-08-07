<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Inicio';
$pagina_activa = 'inicio';

// Contenido traido de MySQL
$destacados   = consultar('SELECT * FROM productos WHERE activo = 1 AND destacado = 1 ORDER BY id LIMIT 4');
$ultimasNotas = consultar('SELECT * FROM publicaciones WHERE visible = 1 ORDER BY publicado_en DESC LIMIT 3');

$laminas = [
    ['archivo' => 'carrusel/build.jpg',   'icono' => '🖥️', 'texto' => 'Cada equipo se arma a mano y se prueba antes de salir'],
    ['archivo' => 'carrusel/taller.jpg',  'icono' => '🔧', 'texto' => 'Mesa de trabajo: aquí pasa toda la magia'],
    ['archivo' => 'carrusel/interior.jpg','icono' => '🧵', 'texto' => 'Cableado ordenado por dentro, no solo por fuera'],
    ['archivo' => 'carrusel/entrega.jpg', 'icono' => '📦', 'texto' => 'Se entrega encendido, actualizado y listo para jugar'],
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ============================ BANNER PRINCIPAL ==================== -->
<section class="hero">
    <div class="contenedor hero__interior">
        <div>
            <span class="hero__etiqueta">Armado, instalado y probado</span>
            <h1>Tu próxima PC, armada <span class="resaltado">para lo que de verdad juegas</span>.</h1>
            <p>
                Dinos cuánto tienes y a qué juegas. Con eso armamos el equipo, le instalamos
                el sistema operativo y te lo entregamos encendido y listo. Si te sobra
                presupuesto, te lo decimos.
            </p>
            <div class="hero__botones">
                <a href="contacto.php" class="boton boton--primario">Cotiza tu equipo gratis</a>
                <a href="productos.php" class="boton boton--contorno">Ver los equipos</a>
            </div>
        </div>

        <div class="terminal aparece">
            <div class="terminal__barra"><span></span><span></span><span></span></div>
<pre><span class="cmd">$ neofetch</span>
Equipo:  NivelUp Nivel 2
Sistema: Windows 11 + Ubuntu 24.04
Memoria: 32 GB
Video:   8 GB dedicados
Disco:   SSD 1 TB

<span class="cmd">$ prueba --fps</span>
<span class="ok">✔ 165 cuadros por segundo en 1080p</span>
<span class="ok">✔ temperatura estable: 62 °C</span>
<span class="ok">✔ listo para entregar</span></pre>
        </div>
    </div>
</section>

<!-- ============================ CIFRAS ============================== -->
<section class="seccion">
    <div class="contenedor cifras">
        <div class="cifra aparece">
            <div class="cifra__numero" data-hasta="640" data-sufijo="+">0</div>
            <div class="cifra__texto">Equipos armados</div>
        </div>
        <div class="cifra aparece">
            <div class="cifra__numero" data-hasta="3" data-sufijo=" días">0</div>
            <div class="cifra__texto">Tiempo promedio de entrega</div>
        </div>
        <div class="cifra aparece">
            <div class="cifra__numero" data-hasta="12" data-sufijo=" meses">0</div>
            <div class="cifra__texto">Garantía en el armado</div>
        </div>
        <div class="cifra aparece">
            <div class="cifra__numero" data-hasta="100" data-sufijo="%">0</div>
            <div class="cifra__texto">Probados antes de entregar</div>
        </div>
    </div>
</section>

<!-- ============================ SERVICIOS =========================== -->
<section class="seccion seccion--alterna" id="servicios">
    <div class="contenedor">
        <h2 class="seccion__titulo">Lo que hacemos</h2>
        <p class="seccion__subtitulo">
            Desde armar el equipo desde cero hasta rescatarte los archivos cuando
            algo sale mal.
        </p>

        <div class="tarjetas">
            <?php foreach (servicios() as $servicio): ?>
                <article class="tarjeta aparece">
                    <span class="tarjeta__fantasma"><?= icono($servicio['icono'], 150) ?></span>
                    <div class="tarjeta__icono"><?= icono($servicio['icono']) ?></div>
                    <h3><?= e($servicio['titulo']) ?></h3>
                    <p><?= e($servicio['texto']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <p style="text-align:center; margin-top:2.4rem;">
            <a href="servicios.php" class="boton boton--contorno">Ver el detalle de cada servicio</a>
        </p>
    </div>
</section>

<!-- ============================ CARRUSEL ============================ -->
<section class="seccion">
    <div class="contenedor">
        <h2 class="seccion__titulo">El taller por dentro</h2>
        <p class="seccion__subtitulo">Así se ve un equipo antes de llegar a tu escritorio.</p>

        <div class="carrusel aparece">
            <div class="carrusel__pista">
                <?php foreach ($laminas as $lamina): ?>
                    <figure class="carrusel__lamina">
                        <?php if (is_file(__DIR__ . '/img/' . $lamina['archivo'])): ?>
                            <img src="img/<?= e($lamina['archivo']) ?>" alt="<?= e($lamina['texto']) ?>">
                        <?php else: ?>
                            <div class="carrusel__marcador"><?= ilustracion_para($lamina['archivo']) ?></div>
                        <?php endif; ?>
                        <figcaption><?= e($lamina['texto']) ?></figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>

            <button class="carrusel__flecha carrusel__flecha--izq" aria-label="Imagen anterior">‹</button>
            <button class="carrusel__flecha carrusel__flecha--der" aria-label="Imagen siguiente">›</button>

            <div class="carrusel__puntos">
                <?php foreach ($laminas as $i => $lamina): ?>
                    <button class="carrusel__punto <?= $i === 0 ? 'activo' : '' ?>"
                            aria-label="Ir a la imagen <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================ PRODUCTOS =========================== -->
<section class="seccion seccion--alterna">
    <div class="contenedor">
        <h2 class="seccion__titulo">Los más pedidos</h2>
        <p class="seccion__subtitulo">
            Cuatro de los ocho productos del catálogo. Los precios ya incluyen IVA
            y la instalación del sistema operativo.
        </p>

        <div class="tarjetas">
            <?php foreach ($destacados as $producto): ?>
                <article class="producto producto--destacado aparece">
                    <div class="producto__imagen"><?= imagen($producto['imagen'], $producto['nombre'], '🛡️') ?></div>
                    <div class="producto__cuerpo">
                        <span class="producto__categoria"><?= e($producto['categoria']) ?></span>
                        <h3><?= e($producto['nombre']) ?></h3>
                        <p><?= e(mb_substr($producto['descripcion'], 0, 110)) ?>…</p>
                        <div class="producto__precio">
                            <?= precio((float)$producto['precio']) ?>
                            <small><?= e($producto['periodo']) ?></small>
                        </div>
                        <a href="contacto.php?asunto=<?= urlencode($producto['nombre']) ?>" class="boton boton--primario">Lo quiero</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <p style="text-align:center; margin-top:2.4rem;">
            <a href="productos.php" class="boton boton--contorno">Ver el catálogo completo</a>
        </p>
    </div>
</section>

<!-- ============================ BLOG ================================ -->
<section class="seccion">
    <div class="contenedor">
        <h2 class="seccion__titulo">Del blog</h2>
        <p class="seccion__subtitulo">Las preguntas que más nos hacen en el mostrador, explicadas con calma.</p>

        <div class="tarjetas">
            <?php foreach ($ultimasNotas as $nota): ?>
                <article class="entrada aparece">
                    <div class="entrada__imagen"><?= imagen($nota['imagen'], $nota['titulo'], '📰') ?></div>
                    <div class="entrada__cuerpo">
                        <div class="entrada__meta">
                            <span class="categoria"><?= e($nota['categoria']) ?></span>
                            <span><?= fecha_larga($nota['publicado_en']) ?></span>
                        </div>
                        <h3><?= e($nota['titulo']) ?></h3>
                        <p><?= e(mb_substr($nota['resumen'], 0, 130)) ?>…</p>
                        <a href="articulo.php?slug=<?= urlencode($nota['slug']) ?>">Leer la publicación →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================ LLAMADA FINAL ======================= -->
<section class="seccion seccion--alterna">
    <div class="contenedor" style="text-align:center; max-width:720px;">
        <h2 class="seccion__titulo">¿No sabes por dónde empezar?</h2>
        <p class="seccion__subtitulo">
            Es lo más normal del mundo. Escríbenos con dos datos —presupuesto y a qué
            juegas— y te armamos dos o tres opciones. Cotizar no cuesta nada.
        </p>
        <a href="contacto.php" class="boton boton--primario">Cotizar mi equipo</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
