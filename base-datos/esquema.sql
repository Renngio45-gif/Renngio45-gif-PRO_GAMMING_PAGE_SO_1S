-- =====================================================================
--  NivelUp - Esquema de base de datos
--  Proyecto final de Sistemas Operativos
--
--  Uso en el servidor Ubuntu:
--      sudo mysql < base-datos/esquema.sql
-- =====================================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS nivelup
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE nivelup;

-- ---------------------------------------------------------------------
-- Usuario de la aplicacion (solo los permisos que necesita el sitio)
-- ---------------------------------------------------------------------
CREATE USER IF NOT EXISTS 'nivelup_app'@'localhost'
    IDENTIFIED BY 'CambiarEstaClave2026';

GRANT SELECT, INSERT, UPDATE, DELETE ON nivelup.* TO 'nivelup_app'@'localhost';
FLUSH PRIVILEGES;

-- ---------------------------------------------------------------------
-- Tabla: productos   (requisito: minimo 8)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(120)   NOT NULL,
    categoria   VARCHAR(60)    NOT NULL,
    descripcion TEXT           NOT NULL,
    precio      DECIMAL(10,2)  NOT NULL,
    periodo     VARCHAR(30)    NOT NULL DEFAULT 'IVA incluido',
    imagen      VARCHAR(160)   DEFAULT NULL,
    destacado   TINYINT(1)     NOT NULL DEFAULT 0,
    activo      TINYINT(1)     NOT NULL DEFAULT 1,
    creado_en   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO productos (nombre, categoria, descripcion, precio, periodo, imagen, destacado) VALUES
('PC Nivel 1 · Entrada',
 'Computadores',
 'Para empezar a jugar sin gastar de más. Corre los títulos populares en calidad media a 1080p y mueve sin problema las tareas de estudio. Incluye el sistema operativo instalado y actualizado.',
 650, 'IVA incluido', 'productos/pc-nivel1.jpg', 1),

('PC Nivel 2 · Competitiva',
 'Computadores',
 'Pensada para juegos de disparos y competitivos, donde importa que la imagen no se trabe. Mantiene más de 100 cuadros por segundo en 1080p y aguanta transmitir en vivo al mismo tiempo.',
 1150, 'IVA incluido', 'productos/pc-nivel2.jpg', 1),

('PC Nivel 3 · Creador',
 'Computadores',
 'Para quien además de jugar edita video, modela en 3D o programa. Más memoria y más núcleos, que es lo que de verdad acelera esas tareas. Se entrega con arranque dual si lo pides.',
 1900, 'IVA incluido', 'productos/pc-nivel3.jpg', 1),

('Monitor 24 pulgadas 165 Hz',
 'Monitores',
 'La pantalla actualiza la imagen 165 veces por segundo en vez de 60. En juegos rápidos la diferencia se nota apenas mueves el mouse. Panel IPS, buen color y soporte ajustable en altura.',
 210, 'IVA incluido', 'productos/monitor.jpg', 0),

('Teclado mecánico retroiluminado',
 'Periféricos',
 'Cada tecla tiene su propio interruptor, así que responde más rápido y dura muchísimo más que un teclado normal. Distribución en español, con la eñe donde debe estar.',
 65, 'IVA incluido', 'productos/teclado.jpg', 0),

('Audífonos con micrófono',
 'Periféricos',
 'Sonido envolvente para ubicar de dónde vienen los pasos, micrófono con cancelación de ruido y almohadillas que no calientan la oreja en partidas largas.',
 48, 'IVA incluido', 'productos/audifonos.jpg', 1),

('Silla ergonómica',
 'Mobiliario',
 'Soporte lumbar regulable, apoyabrazos en cuatro direcciones y reclinación. Suena a lujo hasta que llevas cinco horas sentado y la espalda te lo agradece.',
 180, 'IVA incluido', 'productos/silla.jpg', 0),

('Tarjeta gráfica 8 GB',
 'Componentes',
 'La pieza que más influye en cómo se ven los juegos. Ideal para mejorar un equipo que ya tienes en vez de cambiarlo entero. La instalamos y probamos sin costo si la compras aquí.',
 520, 'IVA incluido', 'productos/grafica.jpg', 0);

-- ---------------------------------------------------------------------
-- Tabla: publicaciones   (requisito: minimo 5)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS publicaciones;
CREATE TABLE publicaciones (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    titulo       VARCHAR(180)  NOT NULL,
    slug         VARCHAR(180)  NOT NULL UNIQUE,
    resumen      VARCHAR(400)  NOT NULL,
    contenido    TEXT          NOT NULL,
    autor        VARCHAR(80)   NOT NULL DEFAULT 'Equipo NivelUp',
    categoria    VARCHAR(60)   NOT NULL,
    imagen       VARCHAR(160)  DEFAULT NULL,
    publicado_en DATE          NOT NULL,
    visible      TINYINT(1)    NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO publicaciones (titulo, slug, resumen, contenido, autor, categoria, imagen, publicado_en) VALUES
('¿Cuántos cuadros por segundo necesitas de verdad?',
 'cuadros-por-segundo',
 'Las tiendas venden números cada vez más grandes, pero a partir de cierto punto el ojo deja de notar la diferencia. Esto es lo que cambia de verdad según el tipo de juego.',
 'Un juego se dibuja en la pantalla como una secuencia de imágenes fijas, igual que una película. Cuántas imágenes alcanza a dibujar el computador en un segundo es lo que llamamos cuadros por segundo, o FPS por sus siglas en inglés.

A 30 cuadros por segundo el movimiento ya se ve continuo, y por eso las consolas antiguas apuntaban a esa cifra. A 60 se siente notoriamente más suave. De ahí en adelante la ganancia empieza a ser menor, pero no desaparece: en juegos de disparos competitivos, pasar de 60 a 144 se nota al girar la cámara rápido.

Aquí viene lo importante, y es lo que casi nadie explica en el mostrador. De nada sirve que el computador genere 165 cuadros si el monitor solo puede mostrar 60. La pantalla es el cuello de botella. Comprar una tarjeta gráfica costosa para usarla con un monitor viejo de 60 hercios es tirar la mitad del dinero.

Nuestra recomendación práctica: si juegas títulos de historia, con 60 cuadros estables vas perfecto y te conviene invertir en resolución y calidad de imagen. Si juegas competitivos, prioriza un monitor de 144 hercios o más antes que subir de gama en la tarjeta gráfica.',
 'Equipo NivelUp', 'Guías', 'blog/fps.jpg', '2026-07-26'),

('SSD o disco duro: en qué se nota la diferencia',
 'ssd-o-disco-duro',
 'Es la mejora más barata que se le puede hacer a un computador lento, y la que más se siente en el uso diario. Explicado sin tecnicismos.',
 'Un disco duro tradicional guarda la información en platos que giran, y un brazo mecánico se mueve para leerla. Es tecnología con partes móviles, parecida a un tocadiscos. Una unidad de estado sólido, o SSD, no tiene nada que se mueva: guarda todo en memoria, como una USB pero mucho más rápida.

La diferencia en números es grande, pero lo que importa es dónde se siente. El sistema operativo pasa de tardar dos minutos en encender a tardar quince segundos. Los juegos que cargaban un minuto entre pantallas cargan en diez. Abrir el navegador deja de ser una espera.

Lo que no cambia son los cuadros por segundo. Un SSD no hace que el juego se vea más fluido mientras juegas, porque eso depende de la tarjeta gráfica y el procesador. Lo que mejora es todo lo que implique cargar algo desde el disco.

Si tienes un computador de hace unos años que se siente lento, cambiar el disco por un SSD suele costar menos que cualquier otra mejora y es la que más se nota. Lo hacemos el mismo día y pasamos tus archivos sin que pierdas nada.',
 'Equipo NivelUp', 'Hardware', 'blog/ssd.jpg', '2026-07-20'),

('Jugar en Linux ya no es lo que era',
 'jugar-en-linux',
 'Durante años la respuesta era que no valía la pena. Eso cambió, y conviene saber por qué si estás estudiando programación o sistemas.',
 'Hasta hace poco, instalar Linux significaba renunciar a la mayoría de los juegos, porque estaban hechos para Windows y no había forma de ejecutarlos. La situación se dio vuelta gracias a una capa de compatibilidad llamada Proton, que traduce las instrucciones del juego para que Linux las entienda.

Hoy una gran parte del catálogo de Steam funciona en Linux sin que el usuario tenga que configurar nada. Se instala y se juega. La consola portátil Steam Deck, que corre Linux, empujó bastante ese avance porque obligó a los estudios a probar sus juegos en ese sistema.

Todavía hay excepciones, y conviene conocerlas antes de cambiarse. Varios juegos competitivos en línea bloquean Linux por su sistema anti trampas. Si tu juego principal es alguno de esos, la respuesta sigue siendo quedarte en Windows o instalar los dos sistemas.

Para un estudiante de sistemas la combinación más cómoda es el arranque dual: Windows para lo que no funcione y Linux para estudiar, programar y entender cómo trabaja un sistema operativo por dentro. Lo configuramos sin costo adicional en cualquier equipo que armemos.',
 'Equipo NivelUp', 'Sistemas operativos', 'blog/linux.jpg', '2026-07-14'),

('Cómo limpiar tu computador sin dañarlo',
 'limpiar-tu-computador',
 'El polvo es la causa más común de que un equipo empiece a calentarse y a bajar el rendimiento. Se limpia fácil, pero hay tres errores que salen caros.',
 'Los ventiladores de un computador mueven aire para sacar el calor, y con el aire entra polvo. Con los meses el polvo se acumula en los disipadores y bloquea el paso. El equipo se calienta y, para protegerse, baja su propia velocidad. El usuario siente que la máquina se puso lenta cuando en realidad se está ahogando.

Para limpiarlo necesitas un destornillador de estrella, aire comprimido en lata y una brocha suave. Desconecta el equipo de la corriente, sácalo a un espacio ventilado y sopla en ráfagas cortas, siempre de adentro hacia afuera.

Los tres errores que vemos seguido en el taller. El primero es usar la aspiradora: genera electricidad estática y puede quemar componentes. El segundo es dejar girar los ventiladores con el aire comprimido, porque al girar sin control generan corriente hacia la tarjeta madre; se sujetan con un dedo mientras se soplan. El tercero es limpiar con el equipo enchufado.

Cada seis meses es suficiente en un ambiente normal. Si vives cerca de una vía destapada o tienes mascotas, cada tres. Si prefieres no abrirlo tú mismo, en el taller lo hacemos con cambio de pasta térmica incluido.',
 'Equipo NivelUp', 'Mantenimiento', 'blog/limpieza.jpg', '2026-07-08'),

('¿Vale la pena armar tu PC en vez de comprarla hecha?',
 'armar-o-comprar',
 'La respuesta honesta es que depende, y no siempre conviene armar. Estas son las cuentas reales, sin el discurso de siempre.',
 'La ventaja más repetida es el ahorro, y es cierta pero menor de lo que dicen. Armando uno mismo se ahorra entre un diez y un quince por ciento frente a un equipo de marca equivalente. No es la mitad, como a veces se promete.

La ventaja real es otra: eliges cada pieza según lo que vas a hacer. Los equipos de marca suelen traer buena tarjeta gráfica y luego escatiman en la fuente de poder o en la ventilación, que son justo las piezas que determinan cuánto dura todo lo demás. Cuando armas, decides dónde poner el dinero.

La segunda ventaja aparece con el tiempo. Un equipo armado con piezas estándar se mejora por partes: cambias la tarjeta gráfica en dos años y sigues con el mismo gabinete y la misma fuente. Varios equipos de marca usan piezas propias que obligan a cambiarlo todo.

Cuándo no conviene armar: si necesitas el computador mañana, si no tienes a quién reclamarle cuando algo falle, o si el presupuesto es tan ajustado que una sola compra equivocada te desarma el plan. En esos casos, un equipo ya armado con garantía de un solo responsable es la decisión sensata.',
 'Equipo NivelUp', 'Guías', 'blog/armar.jpg', '2026-07-02');

-- ---------------------------------------------------------------------
-- Tabla: mensajes  (los envios del formulario de contacto)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS mensajes;
CREATE TABLE mensajes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(120)  NOT NULL,
    correo     VARCHAR(160)  NOT NULL,
    telefono   VARCHAR(40)   DEFAULT NULL,
    empresa    VARCHAR(120)  DEFAULT NULL,
    asunto     VARCHAR(160)  NOT NULL,
    mensaje    TEXT          NOT NULL,
    ip_origen  VARCHAR(45)   DEFAULT NULL,
    enviado_en TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atendido   TINYINT(1)    NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Comprobacion rapida despues de cargar el script
-- ---------------------------------------------------------------------
SELECT 'productos' AS tabla, COUNT(*) AS registros FROM productos
UNION ALL
SELECT 'publicaciones', COUNT(*) FROM publicaciones
UNION ALL
SELECT 'mensajes', COUNT(*) FROM mensajes;
