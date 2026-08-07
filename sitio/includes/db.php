<?php
/**
 * Conexion a MySQL usando PDO.
 *
 * Se usa PDO con consultas preparadas: los datos que escribe el visitante
 * viajan aparte de la instruccion SQL, de modo que no puede inyectar
 * comandos en el formulario de contacto.
 *
 * Si la base no responde, el sitio NO se cae: las secciones que dependen de
 * ella salen vacias y el resto de la pagina funciona igual. Asi se puede
 * revisar el diseno en un equipo que todavia no tiene MySQL instalado.
 */

require_once __DIR__ . '/config.php';

/** Guarda el estado de la conexion entre llamadas. */
function bd_estado(): array
{
    static $estado = ['conexion' => null, 'error' => null, 'intentado' => false];

    if ($estado['intentado']) {
        return $estado;
    }
    $estado['intentado'] = true;

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', BD_HOST, BD_NOMBRE);

    try {
        $estado['conexion'] = new PDO($dsn, BD_USUARIO, BD_CLAVE, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        $estado['error'] = $e->getMessage();
        error_log('NivelUp: sin conexion a MySQL. ' . $e->getMessage());
    }

    return $estado;
}

/** Devuelve la conexion, o null si la base no esta disponible. */
function bd(): ?PDO
{
    return bd_estado()['conexion'];
}

/** true si hay conexion utilizable. */
function bd_disponible(): bool
{
    return bd_estado()['conexion'] instanceof PDO;
}

/** Mensaje del ultimo fallo de conexion (para el aviso de desarrollo). */
function bd_error(): ?string
{
    return bd_estado()['error'];
}

/**
 * Ejecuta una consulta preparada y devuelve todas las filas.
 * Sin base de datos devuelve un arreglo vacio en vez de romper la pagina.
 */
function consultar(string $sql, array $parametros = []): array
{
    $conexion = bd();
    if ($conexion === null) {
        return [];
    }
    try {
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute($parametros);
        return $sentencia->fetchAll();
    } catch (PDOException $e) {
        error_log('NivelUp: consulta fallida. ' . $e->getMessage());
        return [];
    }
}

/**
 * Ejecuta una consulta preparada y devuelve una sola fila (o null).
 */
function consultar_una(string $sql, array $parametros = []): ?array
{
    $conexion = bd();
    if ($conexion === null) {
        return null;
    }
    try {
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute($parametros);
        $fila = $sentencia->fetch();
        return $fila === false ? null : $fila;
    } catch (PDOException $e) {
        error_log('NivelUp: consulta fallida. ' . $e->getMessage());
        return null;
    }
}
