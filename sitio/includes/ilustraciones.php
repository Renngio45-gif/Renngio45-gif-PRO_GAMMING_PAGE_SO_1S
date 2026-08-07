<?php
/**
 * Ilustraciones en SVG para productos, blog y galeria.
 *
 * Son dibujos propios, hechos para este proyecto: no hay imagenes de
 * terceros ni problemas de licencia. Se usan mientras el grupo consigue las
 * fotografias reales. Cuando exista el archivo .jpg correspondiente en
 * img/, la funcion imagen() lo prefiere y estas dejan de mostrarse.
 *
 * Los colores salen de las variables del tema, asi que funcionan igual en
 * modo claro y oscuro.
 */

function ilustracion(string $clave): string
{
    $dibujos = [

        // ---------------------------------------------------- torre de PC
        'torre' => '
            <rect class="il-caja" x="58" y="26" width="84" height="112" rx="8"/>
            <rect class="il-vidrio" x="68" y="36" width="64" height="92" rx="5"/>
            <circle class="il-acento" cx="86" cy="60" r="11"/>
            <circle class="il-fondo" cx="86" cy="60" r="4"/>
            <circle class="il-acento" cx="86" cy="92" r="11"/>
            <circle class="il-fondo" cx="86" cy="92" r="4"/>
            <rect class="il-pieza" x="104" y="52" width="20" height="46" rx="3"/>
            <rect class="il-acento2" x="104" y="108" width="20" height="6" rx="3"/>
            <rect class="il-acento2" x="68" y="118" width="30" height="4" rx="2"/>',

        // ------------------------------------------------------- monitor
        'monitor' => '
            <rect class="il-caja" x="26" y="30" width="148" height="86" rx="7"/>
            <rect class="il-vidrio" x="34" y="38" width="132" height="70" rx="4"/>
            <path class="il-acento" d="M48 92l24-30 18 22 14-16 26 24z"/>
            <circle class="il-acento2" cx="130" cy="56" r="8"/>
            <rect class="il-pieza" x="88" y="116" width="24" height="12" rx="2"/>
            <rect class="il-pieza" x="66" y="128" width="68" height="7" rx="3.5"/>',

        // ------------------------------------------------------- teclado
        'teclado' => '
            <rect class="il-caja" x="20" y="48" width="160" height="60" rx="9"/>
            <g class="il-tecla">
              <rect x="32" y="60" width="16" height="12" rx="2.5"/>
              <rect x="52" y="60" width="16" height="12" rx="2.5"/>
              <rect x="72" y="60" width="16" height="12" rx="2.5"/>
              <rect x="92" y="60" width="16" height="12" rx="2.5"/>
              <rect x="112" y="60" width="16" height="12" rx="2.5"/>
              <rect x="132" y="60" width="16" height="12" rx="2.5"/>
              <rect x="152" y="60" width="16" height="12" rx="2.5"/>
              <rect x="32" y="77" width="16" height="12" rx="2.5"/>
              <rect x="52" y="77" width="16" height="12" rx="2.5"/>
              <rect x="112" y="77" width="16" height="12" rx="2.5"/>
              <rect x="132" y="77" width="16" height="12" rx="2.5"/>
              <rect x="152" y="77" width="16" height="12" rx="2.5"/>
            </g>
            <rect class="il-acento" x="72" y="77" width="36" height="12" rx="2.5"/>
            <rect class="il-acento2" x="52" y="94" width="96" height="7" rx="3.5"/>',

        // ----------------------------------------------------- audifonos
        'audifonos' => '
            <path class="il-trazo" d="M52 92V74a48 48 0 0 1 96 0v18"/>
            <rect class="il-caja" x="36" y="86" width="26" height="42" rx="10"/>
            <rect class="il-caja" x="138" y="86" width="26" height="42" rx="10"/>
            <rect class="il-acento" x="42" y="94" width="14" height="26" rx="6"/>
            <rect class="il-acento" x="144" y="94" width="14" height="26" rx="6"/>
            <path class="il-trazo" d="M138 118c-14 0-18 10-28 10"/>
            <circle class="il-acento2" cx="104" cy="128" r="6"/>',

        // --------------------------------------------------------- silla
        'silla' => '
            <rect class="il-caja" x="66" y="20" width="68" height="70" rx="14"/>
            <rect class="il-acento" x="78" y="32" width="44" height="10" rx="5"/>
            <rect class="il-vidrio" x="78" y="50" width="44" height="28" rx="6"/>
            <rect class="il-caja" x="58" y="92" width="84" height="16" rx="7"/>
            <rect class="il-pieza" x="94" y="108" width="12" height="18" rx="3"/>
            <path class="il-trazo" d="M72 136h56M78 126l-6 10M122 126l6 10"/>
            <circle class="il-acento2" cx="72" cy="138" r="5"/>
            <circle class="il-acento2" cx="128" cy="138" r="5"/>',

        // ------------------------------------------------ tarjeta grafica
        'grafica' => '
            <rect class="il-caja" x="22" y="44" width="156" height="62" rx="7"/>
            <circle class="il-vidrio" cx="66" cy="75" r="22"/>
            <circle class="il-vidrio" cx="130" cy="75" r="22"/>
            <path class="il-acento" d="M66 60a15 15 0 1 1-15 15h7a8 8 0 1 0 8-8z"/>
            <path class="il-acento" d="M130 60a15 15 0 1 1-15 15h7a8 8 0 1 0 8-8z"/>
            <rect class="il-acento2" x="34" y="106" width="42" height="8" rx="2"/>
            <rect class="il-acento2" x="86" y="106" width="60" height="8" rx="2"/>',

        // --------------------------------------------- taller / servicio
        'taller' => '
            <path class="il-trazo-grueso" d="M62 118 118 62"/>
            <path class="il-acento" d="M126 34a22 22 0 0 0-25 29l-9 9 14 14 9-9a22 22 0 0 0 29-25l-14 14-13-4-4-13z"/>
            <rect class="il-caja" x="40" y="106" width="34" height="20" rx="6" transform="rotate(-45 57 116)"/>
            <circle class="il-acento2" cx="150" cy="118" r="8"/>
            <circle class="il-acento2" cx="46" cy="44" r="6"/>',

        // -------------------------------------------- pantalla dual boot
        'dual' => '
            <rect class="il-caja" x="26" y="28" width="148" height="94" rx="8"/>
            <rect class="il-vidrio" x="34" y="36" width="66" height="78" rx="4"/>
            <rect class="il-vidrio2" x="100" y="36" width="66" height="78" rx="4"/>
            <rect class="il-acento" x="46" y="52" width="42" height="6" rx="3"/>
            <rect class="il-acento" x="46" y="66" width="30" height="6" rx="3"/>
            <circle class="il-acento2" cx="133" cy="62" r="14"/>
            <path class="il-cruz" d="M133 55v14M126 62h14"/>
            <rect class="il-pieza" x="76" y="122" width="48" height="8" rx="3"/>',

        // ---------------------------------------------------- disco SSD
        'disco' => '
            <rect class="il-caja" x="34" y="42" width="132" height="66" rx="8"/>
            <rect class="il-vidrio" x="46" y="54" width="76" height="42" rx="5"/>
            <rect class="il-acento" x="58" y="66" width="52" height="7" rx="3.5"/>
            <rect class="il-acento" x="58" y="79" width="34" height="7" rx="3.5"/>
            <g class="il-tecla">
              <rect x="132" y="60" width="6" height="30" rx="2"/>
              <rect x="142" y="60" width="6" height="30" rx="2"/>
              <rect x="152" y="60" width="6" height="30" rx="2"/>
            </g>
            <circle class="il-acento2" cx="135" cy="100" r="4"/>',

        // ------------------------------------------------- articulo/blog
        'articulo' => '
            <rect class="il-caja" x="42" y="22" width="116" height="112" rx="9"/>
            <rect class="il-acento" x="58" y="40" width="60" height="10" rx="5"/>
            <g class="il-linea">
              <rect x="58" y="62" width="84" height="6" rx="3"/>
              <rect x="58" y="76" width="84" height="6" rx="3"/>
              <rect x="58" y="90" width="60" height="6" rx="3"/>
              <rect x="58" y="104" width="72" height="6" rx="3"/>
            </g>
            <circle class="il-acento2" cx="132" cy="44" r="7"/>',

        // ---------------------------------------------------- entrega
        'entrega' => '
            <path class="il-caja" d="M100 30 172 62v50l-72 32-72-32V62z"/>
            <path class="il-vidrio" d="M100 30 172 62l-72 32-72-32z"/>
            <path class="il-trazo" d="M100 94v50"/>
            <rect class="il-acento" x="86" y="52" width="28" height="20" rx="4"/>
            <circle class="il-acento2" cx="146" cy="104" r="8"/>',

        // ------------------------------------------------------ trofeo
        'trofeo' => '
            <path class="il-caja" d="M70 28h60v34a30 30 0 0 1-60 0z"/>
            <path class="il-trazo" d="M70 38H54a16 16 0 0 0 16 16M130 38h16a16 16 0 0 1-16 16"/>
            <rect class="il-pieza" x="92" y="92" width="16" height="20" rx="3"/>
            <rect class="il-acento" x="68" y="112" width="64" height="14" rx="5"/>
            <path class="il-acento2" d="m100 44 5 10 11 1-8 8 2 11-10-5-10 5 2-11-8-8 11-1z"/>',

        // ------------------------------------------------------- local
        'local' => '
            <path class="il-caja" d="M34 62h132v72H34z"/>
            <path class="il-acento" d="M28 40h144l-10 22H38z"/>
            <rect class="il-vidrio" x="50" y="80" width="42" height="34" rx="4"/>
            <rect class="il-pieza" x="112" y="80" width="38" height="54" rx="4"/>
            <circle class="il-acento2" cx="120" cy="108" r="4"/>
            <rect class="il-acento2" x="50" y="124" width="42" height="6" rx="3"/>',
    ];

    $svg = $dibujos[$clave] ?? $dibujos['articulo'];

    return '<svg class="ilustracion" viewBox="0 0 200 160" role="img" aria-hidden="true"'
         . ' preserveAspectRatio="xMidYMid meet">' . $svg . '</svg>';
}

/**
 * Traduce el nombre del archivo de imagen que falta al dibujo que le
 * corresponde. Asi cada producto y cada publicacion muestra algo acorde a
 * su contenido y no un icono generico.
 */
function ilustracion_para(?string $archivo): string
{
    $mapa = [
        'pc-nivel1'    => 'torre',     'pc-nivel2' => 'torre',   'pc-nivel3' => 'torre',
        'monitor'      => 'monitor',   'teclado'   => 'teclado',
        'audifonos'    => 'audifonos', 'silla'     => 'silla',   'grafica'   => 'grafica',
        'fps'          => 'monitor',   'ssd'       => 'disco',   'linux'     => 'dual',
        'limpieza'     => 'taller',    'armar'     => 'torre',
        'local'        => 'local',     'mesa-armado' => 'taller','build-rgb' => 'torre',
        'interior'     => 'torre',     'dual-boot' => 'dual',    'torneo'    => 'trofeo',
        'entrega'      => 'entrega',   'armado'    => 'taller',
        'build'        => 'torre',     'taller'    => 'taller',
    ];

    $base = pathinfo($archivo ?? '', PATHINFO_FILENAME);
    return ilustracion($mapa[$base] ?? 'articulo');
}
