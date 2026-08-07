<?php require_once __DIR__ . '/config.php'; ?>
</main>

<footer class="footer">
    <div class="contenedor footer__columnas">
        <div class="footer__columna footer__marca">
            <p class="footer__logo"><?= SITIO_NOMBRE ?></p>
            <p><?= htmlspecialchars(SITIO_DESC) ?></p>
            <div class="footer__redes">
                <?php
                require_once __DIR__ . '/redes.php';
                global $REDES, $REDES_NOMBRE;
                foreach ($REDES as $red => $url):
                    $nombre = $REDES_NOMBRE[$red] ?? ucfirst($red);
                ?>
                    <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener"
                       aria-label="<?= htmlspecialchars($nombre) ?>" title="<?= htmlspecialchars($nombre) ?>">
                        <?= icono_red($red) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="footer__columna">
            <h3>Enlaces rápidos</h3>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="servicios.php">Servicios</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </div>

        <div class="footer__columna">
            <h3>Legal</h3>
            <ul>
                <li><a href="privacidad.php">Política de privacidad</a></li>
                <li><a href="faq.php">Preguntas frecuentes</a></li>
            </ul>
        </div>

        <div class="footer__columna">
            <h3>Contacto</h3>
            <ul class="footer__contacto">
                <li><?= CONTACTO_DIRECCION ?></li>
                <li><a href="tel:<?= preg_replace('/[^+\d]/', '', CONTACTO_TELEFONO) ?>"><?= CONTACTO_TELEFONO ?></a></li>
                <li><a href="mailto:<?= CONTACTO_CORREO ?>"><?= CONTACTO_CORREO ?></a></li>
                <li><?= CONTACTO_HORARIO ?></li>
            </ul>
        </div>
    </div>

    <div class="footer__inferior">
        <div class="contenedor">
            <p>&copy; <?= date('Y') ?> <?= SITIO_NOMBRE ?>. Todos los derechos reservados.</p>
            <p>Proyecto académico — Sistemas Operativos. Desplegado sobre Ubuntu Server con LAMP.</p>
        </div>
    </div>
</footer>

<a class="whatsapp-flotante"
   href="https://wa.me/<?= CONTACTO_WHATSAPP ?>?text=Hola%20<?= SITIO_NOMBRE ?>%2C%20quisiera%20más%20información"
   target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">
    <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor" aria-hidden="true">
        <path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.3-.7.8-.8 1-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4 0-.5.1-.7l.4-.5c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.9.9-1.1 2.2-.3 3.6a12 12 0 0 0 4.6 4.3c1.7.8 2.6.9 3.5.7.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2z"/>
    </svg>
</a>

<button class="volver-arriba" id="volver-arriba" aria-label="Volver al inicio de la página">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 19V5M5 12l7-7 7 7"/>
    </svg>
</button>

<script src="js/principal.js?v=<?= version_de('js/principal.js') ?>"></script>
</body>
</html>
