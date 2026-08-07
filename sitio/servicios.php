<?php
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Servicios';
$pagina_activa = 'servicios';

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Servicios</h1>
        <p class="seccion__subtitulo">
            Seis cosas que hacemos en el taller. Puedes pedir una sola o todas,
            y no hace falta que nos hayas comprado el equipo aquí.
        </p>
    </div>
</section>

<section class="seccion seccion--alterna" style="padding-top:0;">
    <div class="contenedor" style="padding-top:3rem;">
        <div class="tarjetas">
            <?php foreach (servicios() as $servicio): ?>
                <article class="tarjeta aparece">
                    <span class="tarjeta__fantasma"><?= icono($servicio['icono'], 150) ?></span>
                    <div class="tarjeta__icono"><?= icono($servicio['icono']) ?></div>
                    <h3><?= e($servicio['titulo']) ?></h3>
                    <p style="margin-bottom:1rem;"><?= e($servicio['texto']) ?></p>

                    <ul style="list-style:none; display:grid; gap:.45rem; margin-bottom:1.2rem;">
                        <?php foreach ($servicio['detalle'] as $punto): ?>
                            <li style="display:flex; gap:.55rem; align-items:flex-start; font-size:.9rem; color:var(--texto-suave);">
                                <span style="color:var(--acento); font-weight:800;">✓</span>
                                <?= e($punto) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="contacto.php?asunto=<?= urlencode($servicio['titulo']) ?>"
                       class="boton boton--contorno" style="padding:.5rem 1.2rem; font-size:.9rem;">
                        Pedir este servicio
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================ COMO TRABAJAMOS ===================== -->
<section class="seccion">
    <div class="contenedor">
        <h2 class="seccion__titulo">Cómo funciona</h2>
        <p class="seccion__subtitulo">Cuatro pasos, siempre los mismos, para que sepas qué esperar.</p>

        <div class="tarjetas">
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">01</div>
                <h3>Nos cuentas</h3>
                <p>Cuánto tienes y a qué juegas. Con eso basta para empezar. Si no sabes de piezas, mejor: no tendrás ideas fijas que corregir.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">02</div>
                <h3>Te damos opciones</h3>
                <p>Dos o tres armados con precio cerrado, explicando en qué se diferencian. Sin costo y sin que te comprometas a nada.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">03</div>
                <h3>Armamos y probamos</h3>
                <p>Montaje, instalación del sistema operativo y pruebas de temperatura y rendimiento. Nada sale del taller sin pasar por ahí.</p>
            </article>
            <article class="tarjeta aparece">
                <div class="tarjeta__icono">04</div>
                <h3>Te lo explicamos</h3>
                <p>Al entregarlo te mostramos cómo revisar temperaturas, actualizar controladores y limpiarlo. Quince minutos que te ahorran varias visitas.</p>
            </article>
        </div>
    </div>
</section>

<section class="seccion seccion--alterna">
    <div class="contenedor" style="text-align:center; max-width:700px;">
        <h2 class="seccion__titulo">Empieza por preguntar</h2>
        <p class="seccion__subtitulo">La cotización no cuesta nada y te queda la información aunque no nos compres.</p>
        <a href="contacto.php" class="boton boton--primario">Pedir cotización</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
