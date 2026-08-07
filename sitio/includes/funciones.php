<?php
/**
 * Funciones auxiliares y contenido fijo del sitio.
 *
 * Criterio: lo que cambia seguido (blog, productos, mensajes) vive en MySQL.
 * Lo que casi nunca cambia (servicios, FAQ, equipo) vive aqui como arreglos.
 */

require_once __DIR__ . '/ilustraciones.php';
require_once __DIR__ . '/iconos.php';

/** Escapa texto antes de imprimirlo en el HTML. Evita inyeccion de etiquetas. */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Devuelve una etiqueta <img> si el archivo existe; si no, un marcador
 * con el icono indicado. Asi el sitio nunca muestra imagenes rotas
 * mientras el grupo consigue las fotografias definitivas.
 */
function imagen(?string $ruta, string $alt, string $iconoFallback = '🎮'): string
{
    if ($ruta && is_file(__DIR__ . '/../img/' . $ruta)) {
        return '<img src="img/' . e($ruta) . '" alt="' . e($alt) . '" loading="lazy">';
    }
    // Sin fotografia todavia: se dibuja la ilustracion que corresponde
    return '<span class="marcador" role="img" aria-label="' . e($alt) . '">'
         . ilustracion_para($ruta) . '</span>';
}

/** Formatea una fecha de MySQL (2026-07-18) a texto en espanol. */
function fecha_larga(string $fecha): string
{
    $meses = [1=>'enero','febrero','marzo','abril','mayo','junio','julio',
              'agosto','septiembre','octubre','noviembre','diciembre'];
    $t = strtotime($fecha);
    return date('j', $t) . ' de ' . $meses[(int)date('n', $t)] . ' de ' . date('Y', $t);
}

/** Precio en dolares, con separador de miles. */
function precio(float $valor): string
{
    return '$' . number_format($valor, 0, ',', '.');
}

// =====================================================================
//  Servicios (requisito: minimo 6)
// =====================================================================
function servicios(): array
{
    return [
        [
            'icono' => 'armado',
            'titulo' => 'Armado a la medida',
            'texto'  => 'Nos dices cuánto tienes y a qué juegas, y armamos el equipo con esas dos cosas en mente. Si te sobra presupuesto te lo decimos, en vez de venderte una pieza que no vas a aprovechar.',
            'detalle'=> ['Cotización en el mismo día', 'Piezas compatibles garantizadas', 'Cableado ordenado por dentro'],
        ],
        [
            'icono' => 'sistema',
            'titulo' => 'Instalación del sistema operativo',
            'texto'  => 'Instalamos Windows o Linux, con los controladores al día y las actualizaciones aplicadas. El equipo se entrega encendido, actualizado y listo para usar.',
            'detalle'=> ['Windows 11 o la distribución Linux que prefieras', 'Controladores de video y audio instalados', 'Sin programas basura de fábrica'],
        ],
        [
            'icono' => 'dual',
            'titulo' => 'Arranque dual Windows y Linux',
            'texto'  => 'Los dos sistemas en el mismo computador, y tú eliges cuál abrir cada vez que enciendes. Útil si juegas en Windows pero estudias o programas en Linux.',
            'detalle'=> ['Particiones separadas para cada sistema', 'Menú de arranque configurado', 'Te explicamos cómo funciona antes de entregarlo'],
        ],
        [
            'icono' => 'limpieza',
            'titulo' => 'Mantenimiento y limpieza',
            'texto'  => 'El polvo tapa los ventiladores, la temperatura sube y el equipo empieza a bajar el rendimiento solo. Limpieza física, cambio de pasta térmica y revisión de temperaturas.',
            'detalle'=> ['Limpieza interna completa', 'Cambio de pasta térmica', 'Informe de temperaturas antes y después'],
        ],
        [
            'icono' => 'recuperar',
            'titulo' => 'Recuperación de archivos',
            'texto'  => 'Si borraste algo por accidente o el disco dejó de responder, intentamos recuperarlo. Te decimos de una si se puede o no, sin cobrarte por revisar.',
            'detalle'=> ['Diagnóstico sin costo', 'Copia en una unidad aparte', 'Si no se recupera, no se cobra'],
        ],
        [
            'icono' => 'garantia',
            'titulo' => 'Garantía y soporte',
            'texto'  => 'Un año de garantía en el armado y soporte por WhatsApp cuando algo se ponga raro. Preguntar no cuesta y casi siempre se resuelve sin traer el equipo.',
            'detalle'=> ['12 meses de garantía en mano de obra', 'Soporte por WhatsApp', 'Revisión gratis a los 6 meses'],
        ],
    ];
}

// =====================================================================
//  Preguntas frecuentes (requisito: minimo 10)
// =====================================================================
function preguntas_frecuentes(): array
{
    return [
        ['¿Cuánto se demora el armado de un computador?',
         'Entre dos y cuatro días hábiles si tenemos todas las piezas en bodega. Si hay que encargar algo, avisamos el tiempo exacto antes de que pagues. Nunca entregamos un equipo a medio armar para cumplir una fecha.'],

        ['¿Puedo llevar mis propias piezas?',
         'Claro. Muchos clientes llegan con la tarjeta gráfica o el disco que ya tenían y nosotros completamos el resto. Revisamos primero que todo sea compatible y te decimos si algo no va a funcionar junto.'],

        ['¿Qué pasa si no sé qué piezas necesito?',
         'Es lo más normal, y para eso estamos. Con dos preguntas basta: a qué juegas y cuánto puedes invertir. Con eso armamos dos o tres opciones y te explicamos la diferencia entre ellas en palabras sencillas.'],

        ['¿El precio incluye el sistema operativo?',
         'Incluye la instalación, siempre. La licencia de Windows se cobra aparte si la necesitas. Si prefieres Linux no hay costo de licencia, porque es libre, y te lo dejamos configurado igual.'],

        ['¿Por qué ofrecen Linux si es una tienda de juegos?',
         'Porque cada vez más juegos corren bien en Linux gracias a las capas de compatibilidad, y porque muchos clientes son estudiantes que lo necesitan para programar. Si tienes dudas, el arranque dual te deja los dos sistemas y no tienes que escoger.'],

        ['¿Cuánto dura una computadora armada por ustedes?',
         'El equipo como tal puede durar muchos años. Lo que envejece es lo que exigen los juegos nuevos. Un equipo de gama media armado hoy corre bien la mayoría de títulos durante unos cuatro o cinco años, y después se puede mejorar por partes en vez de comprar todo de nuevo.'],

        ['¿Hacen envíos a otras ciudades?',
         'Sí, con embalaje reforzado y seguro incluido. El equipo viaja con las piezas pesadas aseguradas por dentro para que el movimiento no las suelte. El costo depende de la ciudad y se cotiza aparte.'],

        ['¿Qué garantía tienen las piezas?',
         'La que da cada fabricante, que suele ser de uno a tres años según la pieza. Nosotros ponemos un año adicional sobre el armado, es decir, sobre nuestro trabajo. Si algo falla por cómo lo montamos, lo corregimos sin costo.'],

        ['¿Puedo pagar en cuotas?',
         'Aceptamos tarjeta de crédito con las cuotas que maneje tu banco, y para compras grandes tenemos plan separado: abonas y reservamos las piezas al precio del día en que apartaste.'],

        ['¿Atienden a quienes no juegan?',
         'Todos los días. Armamos equipos para edición de video, diseño, programación y oficina. Un computador para editar y uno para jugar se parecen bastante, cambian las prioridades entre procesador, memoria y tarjeta gráfica.'],

        ['¿Qué hago si el equipo empieza a apagarse solo?',
         'Casi siempre es temperatura o fuente de poder. Escríbenos por WhatsApp antes de destaparlo: varias veces se resuelve revisando en qué momento pasa y qué estabas ejecutando. Si hay que traerlo, el diagnóstico no se cobra.'],

        ['¿Compran equipos usados o reciben en parte de pago?',
         'Recibimos piezas en parte de pago cuando están en buen estado y sirven para otro armado. Las evaluamos en el local y te damos un valor de una vez, sin compromiso de que aceptes.'],
    ];
}

// =====================================================================
//  Equipo de trabajo (editar con los nombres reales del grupo)
// =====================================================================
function equipo(): array
{
    return [
        ['nombre' => 'Daniel Farías Estupiñán', 'rol' => 'Servidor y despliegue',      'texto' => 'Instalación de Ubuntu, configuración de Apache y del virtual host, permisos de directorios y acceso remoto por SSH.', 'inicial' => 'DF'],
        ['nombre' => 'Anelys Penélope Valencia','rol' => 'Diseño y maquetación',       'texto' => 'Diseño responsive, modo claro y oscuro, animaciones, carrusel y galería interactiva.',                              'inicial' => 'AV'],
        ['nombre' => 'Piero García Olivo',      'rol' => 'Base de datos y contenido',  'texto' => 'Tablas en MySQL, consultas con PDO, validación del formulario y redacción de las publicaciones del blog.',            'inicial' => 'PG'],
    ];
}

// =====================================================================
//  Galeria (imagenes y videos)
// =====================================================================
function galeria(): array
{
    return [
        ['archivo' => 'galeria/local.jpg',        'tipo' => 'imagen', 'categoria' => 'taller',   'titulo' => 'Nuestro taller', 'icono' => '🏪'],
        ['archivo' => 'galeria/mesa-armado.jpg',  'tipo' => 'imagen', 'categoria' => 'taller',   'titulo' => 'Mesa de armado', 'icono' => '🔧'],
        ['archivo' => 'galeria/build-rgb.jpg',    'tipo' => 'imagen', 'categoria' => 'equipos',  'titulo' => 'Equipo terminado con iluminación', 'icono' => '💡'],
        ['archivo' => 'galeria/interior.jpg',     'tipo' => 'imagen', 'categoria' => 'equipos',  'titulo' => 'Cableado por dentro', 'icono' => '🧵'],
        ['archivo' => 'galeria/limpieza.jpg',     'tipo' => 'imagen', 'categoria' => 'taller',   'titulo' => 'Mantenimiento y limpieza', 'icono' => 'limpieza'],
        ['archivo' => 'galeria/dual-boot.jpg',    'tipo' => 'imagen', 'categoria' => 'equipos',  'titulo' => 'Menú de arranque dual', 'icono' => 'dual'],
        ['archivo' => 'galeria/torneo.jpg',       'tipo' => 'imagen', 'categoria' => 'eventos',  'titulo' => 'Torneo con la comunidad', 'icono' => '🏆'],
        ['archivo' => 'galeria/entrega.jpg',      'tipo' => 'imagen', 'categoria' => 'eventos',  'titulo' => 'Entrega de un equipo', 'icono' => '📦'],
        ['archivo' => 'galeria/armado.mp4',       'tipo' => 'video',  'categoria' => 'taller',   'titulo' => 'Cómo armamos un equipo, paso a paso', 'icono' => '🎬'],
    ];
}
