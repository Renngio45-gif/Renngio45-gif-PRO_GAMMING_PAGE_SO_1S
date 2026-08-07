<?php
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Nosotros';
$pagina_activa = 'nosotros';

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Quiénes somos</h1>
        <p class="seccion__subtitulo">
            NivelUp empezó en un cuarto, armando computadores para amigos que no
            querían que les vendieran cualquier cosa. Seguimos con la misma idea:
            explicar bien qué estás comprando y por qué.
        </p>
    </div>
</section>

<!-- ============================ MISION Y VISION ===================== -->
<section class="seccion seccion--alterna">
    <div class="contenedor">
        <div class="tarjetas">
            <article class="tarjeta aparece">
                <span class="tarjeta__fantasma"><?= icono('mision', 150) ?></span>
                <div class="tarjeta__icono"><?= icono('mision') ?></div>
                <h3>Misión</h3>
                <p>
                    Armar computadores que se ajusten a lo que cada persona necesita
                    y a lo que puede pagar, explicando en palabras sencillas qué hace
                    cada pieza. Que nadie salga de aquí con algo que no entiende.
                </p>
            </article>

            <article class="tarjeta aparece">
                <span class="tarjeta__fantasma"><?= icono('vision', 150) ?></span>
                <div class="tarjeta__icono"><?= icono('vision') ?></div>
                <h3>Visión</h3>
                <p>
                    Ser en 2030 el taller de referencia para gamers y estudiantes
                    en Esmeraldas, con una comunidad que vuelve no por los precios,
                    sino porque aquí le dijeron la verdad.
                </p>
            </article>

            <article class="tarjeta aparece">
                <span class="tarjeta__fantasma"><?= icono('valores', 150) ?></span>
                <div class="tarjeta__icono"><?= icono('valores') ?></div>
                <h3>Valores</h3>
                <p>
                    No vendemos de más. Si con menos presupuesto logras lo mismo, te
                    lo decimos aunque perdamos la venta. Y enseñamos cómo funciona el
                    equipo, para que no dependas de nosotros por cada detalle.
                </p>
            </article>
        </div>
    </div>
</section>

<!-- ============================ OBJETIVOS =========================== -->
<section class="seccion">
    <div class="contenedor">
        <h2 class="seccion__titulo">Nuestros objetivos</h2>
        <p class="seccion__subtitulo">Lo que nos propusimos para este año y cómo lo medimos.</p>

        <div class="tarjetas">
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">1</div>
                <h3>Entregar en tres días</h3>
                <p>Mantener el promedio de entrega en tres días hábiles cuando las piezas están en bodega, y avisar el plazo real antes de que el cliente pague.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">2</div>
                <h3>Probar el 100% de los equipos</h3>
                <p>Ningún computador sale sin haber corrido pruebas de temperatura y rendimiento. Entregamos el reporte junto con la factura.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">3</div>
                <h3>Enseñar lo básico</h3>
                <p>Que cada cliente se vaya sabiendo limpiar su equipo, revisar la temperatura y actualizar los controladores. Son quince minutos que ahorran muchas visitas.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">4</div>
                <h3>Cotizar sin costo, siempre</h3>
                <p>Armar dos o tres opciones para quien pregunte, compre o no compre. Es la mejor forma que conocemos de que vuelvan.</p>
            </article>
        </div>
    </div>
</section>

<!-- ============================ EQUIPO ============================== -->
<section class="seccion seccion--alterna">
    <div class="contenedor">
        <h2 class="seccion__titulo">El equipo</h2>
        <p class="seccion__subtitulo">
            Integrantes del proyecto y responsabilidad de cada uno en el desarrollo
            y despliegue del sitio.
        </p>

        <div class="tarjetas">
            <?php foreach (equipo() as $persona): ?>
                <article class="tarjeta aparece" style="text-align:center;">
                    <div class="tarjeta__icono" style="margin-inline:auto; width:74px; height:74px; border-radius:50%; font-size:1.4rem; font-weight:800;">
                        <?= e($persona['inicial']) ?>
                    </div>
                    <h3><?= e($persona['nombre']) ?></h3>
                    <p style="color:var(--acento); font-weight:700; font-size:.88rem; margin-bottom:.5rem;">
                        <?= e($persona['rol']) ?>
                    </p>
                    <p><?= e($persona['texto']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================ CTA ================================= -->
<section class="seccion">
    <div class="contenedor" style="text-align:center; max-width:700px;">
        <h2 class="seccion__titulo">¿Armamos el tuyo?</h2>
        <p class="seccion__subtitulo">Cuéntanos qué juegas y cuánto tienes. Del resto nos encargamos.</p>
        <a href="contacto.php" class="boton boton--primario">Escríbenos</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
