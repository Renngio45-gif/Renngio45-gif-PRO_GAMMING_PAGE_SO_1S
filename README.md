# NivelUp — Sitio web sobre LAMP

Proyecto final de **Sistemas Operativos**.
Pontificia Universidad Católica del Ecuador, Sede Esmeraldas — Desarrollo de Software.

Sitio web de un taller y tienda ficticia de computadores para videojuegos,
desplegado en un servidor **LAMP** (Linux, Apache, MySQL, PHP) sobre Ubuntu.

**Integrantes:** Daniel Farías Estupiñán · Anelys Penélope Valencia · Piero García Olivo
**Docente:** Ing. Chrisbel Paulette Simisterra Batallas

El tema conecta con la asignatura por los servicios que ofrece la tienda:
instalación de sistemas operativos, arranque dual entre Windows y Linux,
administración de permisos y recuperación de archivos.

---

## Estructura

```
PRY_SO_1S/
│
├── sitio/                    ← esto es lo que se copia a /var/www/nivelup
│   ├── index.php             Inicio: banner, CTA, carrusel, destacados
│   ├── nosotros.php          Misión, visión, objetivos y equipo
│   ├── servicios.php         6 servicios con su detalle
│   ├── productos.php         8 productos desde MySQL, con filtro
│   ├── galeria.php           Galería interactiva con visor y video
│   ├── blog.php              5 publicaciones desde MySQL
│   ├── articulo.php          Publicación individual (?slug=...)
│   ├── faq.php               12 preguntas en acordeón
│   ├── contacto.php          Formulario validado, mapa y datos
│   ├── privacidad.php        Política de privacidad
│   │
│   ├── includes/
│   │   ├── config.php        Identidad, contacto y credenciales
│   │   ├── db.php            Conexión PDO y consultas preparadas
│   │   ├── funciones.php     Servicios, FAQ, equipo, galería
│   │   ├── ilustraciones.php Dibujos SVG propios
│   │   ├── iconos.php        Iconos de interfaz (Lucide, ISC)
│   │   ├── redes.php         Logos de redes sociales
│   │   ├── header.php        Cabecera y menú (compartido)
│   │   └── footer.php        Pie, WhatsApp y volver arriba
│   │
│   ├── css/estilos.css
│   ├── js/principal.js
│   └── img/                  Fotos, video e iconos (ver img/CREDITOS.txt)
│
├── base-datos/
│   └── esquema.sql           Base, usuario, 3 tablas y datos
│
├── configuracion/
│   ├── nivelup.conf          Virtual host de Apache
│   ├── despliegue.md         Despliegue paso a paso desde cero
│   ├── convivir-con-wordpress.md   Cómo convivir con otro sitio
│   ├── subir-archivos-a-la-vm.md   Guía de transferencia por SSH
│   └── iniciar-local.bat     Arranca MySQL y PHP en Windows
│
└── documentacion/
    ├── Informe-IEEE-NivelUp-final.pdf      ← el que se entrega
    ├── Informe-IEEE-NivelUp-editable.docx  Versión editable de base
    ├── Guion-Presentacion-NivelUp.docx     Reparto de los 10 minutos
    ├── prompts-imagenes.md                 Prompts de las imágenes
    ├── capturas/                           Evidencias del despliegue
    ├── diseno/                             Paletas, logo e iconos
    └── enunciado/                          Consigna de la asignatura
```

---

## Cómo se despliega

Guía completa en [configuracion/despliegue.md](configuracion/despliegue.md).
Si el servidor ya tiene otro sitio, ver
[convivir-con-wordpress.md](configuracion/convivir-con-wordpress.md).

Resumen, con el sitio ya copiado a `/tmp` por `scp`:

```bash
sudo cp -r /tmp/sitio/* /var/www/nivelup/
sudo chown -R $USER:www-data /var/www/nivelup
sudo find /var/www/nivelup -type d -exec chmod 750 {} \;
sudo find /var/www/nivelup -type f -exec chmod 640 {} \;
sudo mysql < /tmp/esquema.sql
sudo cp configuracion/nivelup.conf /etc/apache2/sites-available/
sudo a2ensite nivelup.conf && sudo apache2ctl configtest
sudo systemctl reload apache2
```

**Despliegue actual:** `http://192.168.1.60:8080`

## Probarlo en Windows sin la máquina virtual

Con PHP y MySQL portables instalados, doble clic en
[configuracion/iniciar-local.bat](configuracion/iniciar-local.bat).
Levanta los dos servicios y abre el navegador en `http://localhost:8080`.

El `esquema.sql` crea el mismo usuario y contraseña que en el servidor, así
que `config.php` funciona igual en local y en producción.

---

## Cumplimiento de los requisitos

| Requisito | Dónde está |
|---|---|
| Inicio con banner y CTA | `index.php` |
| Nosotros: misión, visión, objetivos | `nosotros.php` |
| Servicios (mínimo 6) | `servicios.php` + `funciones.php` |
| Productos (mínimo 8) | `productos.php` + tabla `productos` |
| Galería con imágenes y video | `galeria.php` |
| Blog (mínimo 5) | `blog.php` + tabla `publicaciones` |
| FAQ (mínimo 10, hay 12) | `faq.php` |
| Contacto validado con mapa | `contacto.php` |
| Pie con avisos legales | `footer.php` + `privacidad.php` |
| Diseño responsive | `estilos.css`, media queries al final |
| Menú de navegación fijo | `.navbar { position: sticky }` |
| Animaciones y transiciones | clase `.aparece` + IntersectionObserver |
| Carrusel de imágenes | `index.php` + bloque 5 de `principal.js` |
| Botón volver al inicio | `footer.php` + bloque 3 |
| Modo claro y oscuro | `data-tema` + bloque 1 |
| Validación con JavaScript | bloque 8 de `principal.js` |
| Hover y scroll suave | `estilos.css` |
| Galería interactiva | filtros y visor, bloque 7 |
| Botón flotante de WhatsApp | `footer.php` |
| Entorno LAMP | Ubuntu 22.04, Apache 2.4.52, MySQL 8.0.46, PHP 8.1.2 |
| Apache con virtual host | `configuracion/nivelup.conf`, puerto 8080 |
| Permisos y directorios | 750 en carpetas, 640 en archivos |
| Acceso remoto por SSH | puerto 22, transferencia por `scp` |
| Documentación IEEE | `documentacion/Informe-IEEE-NivelUp-final.pdf` |

---

## Decisiones técnicas que conviene poder explicar

**Sin frameworks ni CDN.** El diseño responsive, el carrusel, la galería, el
modo oscuro y la validación están escritos a mano. El sitio funciona aunque
el servidor no tenga acceso a internet.

**Consultas preparadas con PDO.** La instrucción SQL y los datos del
visitante viajan por separado, de modo que un campo del formulario no puede
convertirse en un comando.

**Validación en dos capas.** JavaScript para avisar rápido, PHP para
defender de verdad: el JavaScript se puede desactivar desde el navegador.

**Permisos 750 y 640.** Apache solo necesita leer el sitio. Darle escritura,
o poner 777, sería regalar privilegios que nadie pidió.

**Virtual host en el puerto 8080.** El servidor ya alojaba WordPress de una
práctica anterior. El virtual host permite que ambos convivan sin tocarse.

**Versionado de CSS y JavaScript.** Se sirven con `?v=` y la fecha de
modificación del archivo, para que el navegador no siga usando una copia
vieja en caché tras actualizar el sitio.

---

## Créditos de las imágenes

Detalle completo en [sitio/img/CREDITOS.txt](sitio/img/CREDITOS.txt).

Las fotografías provienen de Openverse con licencia CC0 y dominio público.
Siete imágenes se generaron con inteligencia artificial y están declaradas
como tales. Los iconos de interfaz son de Lucide (licencia ISC) y las
ilustraciones son dibujos propios hechos para este proyecto.
