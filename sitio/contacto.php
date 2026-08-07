<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Contacto';
$pagina_activa = 'contacto';

$errores  = [];
$enviado  = isset($_GET['enviado']);
$valores  = ['nombre' => '', 'correo' => '', 'telefono' => '', 'empresa' => '', 'asunto' => '', 'mensaje' => ''];

// Si llega desde "Cotizar" en productos/servicios, precargamos el asunto
if (isset($_GET['asunto'])) {
    $valores['asunto'] = 'Cotización: ' . $_GET['asunto'];
}

$asuntos = [
    'Cotizar un equipo',
    'Armado a la medida',
    'Instalación del sistema operativo',
    'Arranque dual Windows y Linux',
    'Mantenimiento y limpieza',
    'Recuperación de archivos',
    'Garantía o soporte',
    'Compra de un producto',
    'Otro',
];

/* =====================================================================
   Procesamiento del formulario
   La validacion de JavaScript mejora la experiencia, pero no es una
   defensa: cualquiera puede desactivarla. Por eso se valida otra vez
   aqui, en el servidor, antes de tocar la base de datos.
   ===================================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($valores as $campo => $_) {
        $valores[$campo] = trim($_POST[$campo] ?? '');
    }

    // Trampa antispam: es un campo oculto que un humano nunca llena
    $trampa = trim($_POST['sitio_web'] ?? '');

    if (mb_strlen($valores['nombre']) < 3) {
        $errores['nombre'] = 'Escriba su nombre completo.';
    }
    if (!filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = 'El correo electrónico no es válido.';
    }
    if ($valores['telefono'] !== '' && !preg_match('/^[\d\s()+-]{7,20}$/', $valores['telefono'])) {
        $errores['telefono'] = 'El teléfono tiene un formato incorrecto.';
    }
    if ($valores['asunto'] === '') {
        $errores['asunto'] = 'Seleccione un asunto.';
    }
    if (mb_strlen($valores['mensaje']) < 20) {
        $errores['mensaje'] = 'El mensaje debe tener al menos 20 caracteres.';
    }
    if (mb_strlen($valores['mensaje']) > 2000) {
        $errores['mensaje'] = 'El mensaje no puede superar los 2000 caracteres.';
    }

    if (empty($errores) && $trampa === '' && !bd_disponible()) {
        $errores['general'] = MODO_DESARROLLO
            ? 'No hay conexión con MySQL, así que el mensaje no se guardó. ' . bd_error()
            : 'No pudimos registrar su mensaje. Intente de nuevo en unos minutos.';
    } elseif (empty($errores) && $trampa === '') {
        try {
            $sentencia = bd()->prepare(
                'INSERT INTO mensajes (nombre, correo, telefono, empresa, asunto, mensaje, ip_origen)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $sentencia->execute([
                $valores['nombre'],
                $valores['correo'],
                $valores['telefono'] !== '' ? $valores['telefono'] : null,
                $valores['empresa'] !== '' ? $valores['empresa'] : null,
                $valores['asunto'],
                $valores['mensaje'],
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

            // Patron POST-Redirect-GET: evita que al recargar se envie dos veces
            header('Location: contacto.php?enviado=1#formulario');
            exit;

        } catch (PDOException $e) {
            $errores['general'] = MODO_DESARROLLO
                ? 'Error al guardar: ' . $e->getMessage()
                : 'No pudimos registrar su mensaje. Intente de nuevo en unos minutos.';
        }
    } elseif ($trampa !== '') {
        // Se descarta en silencio: el bot cree que fue enviado
        header('Location: contacto.php?enviado=1#formulario');
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Contacto</h1>
        <p class="seccion__subtitulo">
            Cuéntanos qué necesitas y te respondemos el mismo día. Si es algo
            urgente, mejor llámanos o escríbenos por WhatsApp.
        </p>
    </div>
</section>

<section class="seccion" style="padding-top:0;" id="formulario">
    <div class="contenedor contacto__rejilla">

        <!-- -------------------- Datos de contacto -------------------- -->
        <div class="contacto__datos">
            <div class="contacto__dato aparece">
                <span class="icono">📍</span>
                <div>
                    <strong>Dirección</strong>
                    <span><?= e(CONTACTO_DIRECCION) ?></span>
                </div>
            </div>
            <div class="contacto__dato aparece">
                <span class="icono">📞</span>
                <div>
                    <strong>Teléfono</strong>
                    <span><a href="tel:<?= preg_replace('/[^+\d]/', '', CONTACTO_TELEFONO) ?>"><?= e(CONTACTO_TELEFONO) ?></a></span>
                </div>
            </div>
            <div class="contacto__dato aparece">
                <span class="icono">✉️</span>
                <div>
                    <strong>Correo electrónico</strong>
                    <span><a href="mailto:<?= e(CONTACTO_CORREO) ?>"><?= e(CONTACTO_CORREO) ?></a></span>
                </div>
            </div>
            <div class="contacto__dato aparece">
                <span class="icono">🕒</span>
                <div>
                    <strong>Horario de atención</strong>
                    <span><?= e(CONTACTO_HORARIO) ?></span>
                </div>
            </div>

            <div class="contacto__dato aparece" style="display:block;">
                <strong style="margin-bottom:.6rem; display:block;">Síganos</strong>
                <div class="footer__redes" style="margin-top:0;">
                    <?php
                    require_once __DIR__ . '/includes/redes.php';
                    foreach ($REDES as $red => $url):
                        $nombre = $REDES_NOMBRE[$red] ?? ucfirst($red);
                    ?>
                        <a href="<?= e($url) ?>" target="_blank" rel="noopener"
                           aria-label="<?= e($nombre) ?>" title="<?= e($nombre) ?>">
                            <?= icono_red($red) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mapa de ubicacion -----------------------------------------
                 El recuadro (bbox) se arma con sprintf y el formato %F, que
                 siempre usa punto decimal sin importar el idioma del
                 servidor. Con la coma de otros idiomas la URL sale rota y
                 OpenStreetMap muestra medio continente en vez del punto. -->
            <?php
            $izq  = sprintf('%F', MAPA_LON - 0.008);
            $abajo= sprintf('%F', MAPA_LAT - 0.005);
            $der  = sprintf('%F', MAPA_LON + 0.008);
            $arr  = sprintf('%F', MAPA_LAT + 0.005);
            $mLat = sprintf('%F', MAPA_LAT);
            $mLon = sprintf('%F', MAPA_LON);

            $urlMapa = 'https://www.openstreetmap.org/export/embed.html'
                     . '?bbox=' . rawurlencode("$izq,$abajo,$der,$arr")
                     . '&amp;layer=mapnik'
                     . '&amp;marker=' . rawurlencode("$mLat,$mLon");
            ?>
            <div class="mapa aparece">
                <iframe
                    title="Ubicación de <?= SITIO_NOMBRE ?>"
                    loading="lazy"
                    src="<?= $urlMapa ?>">
                </iframe>
            </div>
            <p style="font-size:.8rem; margin-top:.5rem;">
                <a href="https://www.openstreetmap.org/?mlat=<?= $mLat ?>&amp;mlon=<?= $mLon ?>#map=17/<?= $mLat ?>/<?= $mLon ?>"
                   target="_blank" rel="noopener">Abrir el mapa en una pestaña nueva</a>
            </p>
        </div>

        <!-- -------------------- Formulario --------------------------- -->
        <form class="formulario" id="formulario-contacto" method="POST" action="contacto.php#formulario" novalidate>

            <?php if ($enviado): ?>
                <div class="aviso aviso--exito">
                    Mensaje recibido. Te respondemos al correo que nos dejaste.
                </div>
            <?php endif; ?>

            <?php if (isset($errores['general'])): ?>
                <div class="aviso aviso--error"><?= e($errores['general']) ?></div>
            <?php endif; ?>

            <div class="campo--doble">
                <div class="campo <?= isset($errores['nombre']) ? 'invalido' : '' ?>">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" value="<?= e($valores['nombre']) ?>" required>
                    <span class="error-texto"><?= e($errores['nombre'] ?? '') ?></span>
                </div>

                <div class="campo <?= isset($errores['correo']) ? 'invalido' : '' ?>">
                    <label for="correo">Correo electrónico *</label>
                    <input type="email" id="correo" name="correo" value="<?= e($valores['correo']) ?>" required>
                    <span class="error-texto"><?= e($errores['correo'] ?? '') ?></span>
                </div>
            </div>

            <div class="campo--doble">
                <div class="campo <?= isset($errores['telefono']) ? 'invalido' : '' ?>">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" value="<?= e($valores['telefono']) ?>">
                    <span class="error-texto"><?= e($errores['telefono'] ?? '') ?></span>
                </div>

                <div class="campo">
                    <label for="empresa">Empresa</label>
                    <input type="text" id="empresa" name="empresa" value="<?= e($valores['empresa']) ?>">
                </div>
            </div>

            <div class="campo <?= isset($errores['asunto']) ? 'invalido' : '' ?>">
                <label for="asunto">Asunto *</label>
                <select id="asunto" name="asunto" required>
                    <option value="">Seleccione una opción</option>
                    <?php
                    // Si viene un asunto precargado que no esta en la lista, se agrega
                    $opciones = $asuntos;
                    if ($valores['asunto'] !== '' && !in_array($valores['asunto'], $opciones, true)) {
                        array_unshift($opciones, $valores['asunto']);
                    }
                    foreach ($opciones as $opcion): ?>
                        <option value="<?= e($opcion) ?>" <?= $valores['asunto'] === $opcion ? 'selected' : '' ?>>
                            <?= e($opcion) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-texto"><?= e($errores['asunto'] ?? '') ?></span>
            </div>

            <div class="campo <?= isset($errores['mensaje']) ? 'invalido' : '' ?>">
                <label for="mensaje">Mensaje *</label>
                <textarea id="mensaje" name="mensaje" rows="6" required><?= e($valores['mensaje']) ?></textarea>
                <div style="display:flex; justify-content:space-between; gap:1rem;">
                    <span class="error-texto"><?= e($errores['mensaje'] ?? '') ?></span>
                    <span id="contador-mensaje" style="font-size:.8rem; color:var(--texto-suave); margin-left:auto;">0 / 2000</span>
                </div>
            </div>

            <!-- Trampa antispam: oculta para las personas, visible para los bots -->
            <div style="position:absolute; left:-9999px;" aria-hidden="true">
                <label for="sitio_web">No llenar este campo</label>
                <input type="text" id="sitio_web" name="sitio_web" tabindex="-1" autocomplete="off">
            </div>

            <button type="submit" class="boton boton--primario">Enviar mensaje</button>

            <p style="font-size:.82rem; color:var(--texto-suave);">
                Los campos marcados con * son obligatorios. Tus datos se usan solo para
                responder esta consulta, según nuestra
                <a href="privacidad.php">política de privacidad</a>.
            </p>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
