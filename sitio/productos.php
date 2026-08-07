<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/funciones.php';

$pagina_titulo = 'Productos';
$pagina_activa = 'productos';

// Filtro por categoria (viene del enlace ?categoria=Identidad)
$categoria = $_GET['categoria'] ?? '';

if ($categoria !== '') {
    $productos = consultar(
        'SELECT * FROM productos WHERE activo = 1 AND categoria = ? ORDER BY destacado DESC, id',
        [$categoria]
    );
} else {
    $productos = consultar('SELECT * FROM productos WHERE activo = 1 ORDER BY destacado DESC, id');
}

$categorias = consultar('SELECT DISTINCT categoria FROM productos WHERE activo = 1 ORDER BY categoria');

require_once __DIR__ . '/includes/header.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px; text-align:center;">
        <h1 class="seccion__titulo">Catálogo</h1>
        <p class="seccion__subtitulo">
            Ocho productos: tres equipos armados por presupuesto y cinco piezas
            sueltas. Los precios incluyen IVA y la instalación del sistema operativo.
        </p>
    </div>
</section>

<section class="seccion" style="padding-top:0;">
    <div class="contenedor">

        <!-- Filtros por categoria -->
        <div class="galeria__filtros">
            <a href="productos.php" class="galeria__filtro <?= $categoria === '' ? 'activo' : '' ?>">Todas</a>
            <?php foreach ($categorias as $fila): ?>
                <a href="productos.php?categoria=<?= urlencode($fila['categoria']) ?>"
                   class="galeria__filtro <?= $categoria === $fila['categoria'] ? 'activo' : '' ?>">
                    <?= e($fila['categoria']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($productos)): ?>
            <p style="text-align:center; color:var(--texto-suave);">
                No hay productos en esa categoría. <a href="productos.php">Ver todos</a>.
            </p>
        <?php else: ?>
            <div class="tarjetas">
                <?php foreach ($productos as $producto): ?>
                    <article class="producto aparece <?= $producto['destacado'] ? 'producto--destacado' : '' ?>">
                        <div class="producto__imagen"><?= imagen($producto['imagen'], $producto['nombre'], '🛡️') ?></div>
                        <div class="producto__cuerpo">
                            <span class="producto__categoria"><?= e($producto['categoria']) ?></span>
                            <h3><?= e($producto['nombre']) ?></h3>
                            <p><?= e($producto['descripcion']) ?></p>
                            <div class="producto__precio">
                                <?= precio((float)$producto['precio']) ?>
                                <small><?= e($producto['periodo']) ?></small>
                            </div>
                            <a href="contacto.php?asunto=<?= urlencode($producto['nombre']) ?>" class="boton boton--primario">
                                Lo quiero
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="seccion seccion--alterna">
    <div class="contenedor" style="text-align:center; max-width:700px;">
        <h2 class="seccion__titulo">¿No sabes cuál te sirve?</h2>
        <p class="seccion__subtitulo">
            Escríbenos con tu presupuesto y los juegos que te interesan. Te decimos
            cuál conviene y, si puedes esperar unos meses, también te lo decimos.
        </p>
        <a href="contacto.php" class="boton boton--primario">Pedir recomendación</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
