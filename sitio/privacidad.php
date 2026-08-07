<?php
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Política de privacidad';
$pagina_activa = '';

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor articulo">
        <header class="articulo__cabecera">
            <h1>Política de privacidad</h1>
            <p style="color:var(--texto-suave);">Última actualización: <?= fecha_larga(date('Y-m-d')) ?></p>
        </header>

        <div class="articulo__cuerpo">
            <h3>Qué datos recogemos</h3>
            <p>
                Solo los que escribes en el formulario de contacto: nombre, correo,
                teléfono, empresa, asunto y mensaje. El servidor también guarda la
                dirección IP desde la que se envió y la fecha, para frenar los envíos
                automáticos de robots.
            </p>

            <h3>Para qué los usamos</h3>
            <p>
                Para responder tu consulta y, si la cosa avanza, para pasarte la
                cotización. No enviamos publicidad que no hayas pedido ni le damos tus
                datos a nadie más.
            </p>

            <h3>Dónde se almacenan</h3>
            <p>
                En una base de datos MySQL alojada en nuestro servidor, con acceso
                restringido al personal que atiende las solicitudes. El usuario de la
                aplicación web tiene permisos limitados a las operaciones estrictamente
                necesarias.
            </p>

            <h3>Cuánto tiempo los conservamos</h3>
            <p>
                Los mensajes que no terminan en una compra se borran a los doce meses.
                Los de clientes se conservan mientras dure la garantía y el tiempo que
                exija la ley para la facturación.
            </p>

            <h3>Tus derechos</h3>
            <p>
                Puedes pedir en cualquier momento que te mostremos, corrijamos o
                borremos tus datos escribiendo a <?= e(CONTACTO_CORREO) ?>.
                Respondemos dentro de los diez días hábiles siguientes.
            </p>

            <h3>Cookies</h3>
            <p>
                Este sitio no usa cookies de seguimiento ni herramientas de analítica
                de terceros. Lo único que se guarda en tu navegador es si prefieres el
                modo claro u oscuro, y esa información nunca sale de tu equipo.
            </p>

            <h3>Aviso académico</h3>
            <p>
                Este sitio forma parte de un proyecto académico de la asignatura de
                Sistemas Operativos. La empresa aquí presentada es ficticia y los datos
                de contacto no corresponden a una organización real.
            </p>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="index.php" class="boton boton--contorno">← Volver al inicio</a>
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
