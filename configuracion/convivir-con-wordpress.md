# Alojar el sitio en la VM que ya tiene WordPress

## Lo primero: qué es cada cosa

```
        ┌─────────────────────────────────────────┐
        │        Ubuntu Server (la VM)            │  ← el sistema operativo
        │  ┌───────────────────────────────────┐  │
        │  │   Apache + PHP + MySQL  (LAMP)    │  │  ← el servidor web
        │  │  ┌──────────┐   ┌──────────────┐  │  │
        │  │  │WordPress │   │   NivelUp    │  │  │  ← dos sitios, uno
        │  │  └──────────┘   └──────────────┘  │  │     al lado del otro
        │  └───────────────────────────────────┘  │
        └─────────────────────────────────────────┘
```

WordPress **no** es el servidor: es una aplicación PHP que corre sobre el
LAMP. Nuestro sitio es otra aplicación PHP que corre sobre el mismo LAMP.
No hay que meter uno dentro del otro.

**Ventaja de que WordPress ya funcione:** Apache, PHP y MySQL ya están
instalados y probados. De la guía `despliegue.md` te puedes saltar los
pasos 3 y 5.

---

## Diagnóstico previo

Antes de decidir, hay que saber qué hay montado:

```bash
which apache2 nginx php mysql mariadb
systemctl is-active apache2 nginx mysql mariadb
sudo find /var/www /srv -maxdepth 3 -name "wp-config.php" 2>/dev/null
ip -4 addr show | grep inet
```

De la salida importan tres datos:

- **Apache o Nginx.** Si aparece `apache2` activo, sirven las opciones A y B
  tal cual. Si aparece `nginx`, ir a la sección «Si usas Nginx».
- **Dónde está WordPress.** La carpeta que contiene `wp-config.php` es la
  raíz del sitio de WordPress (normalmente `/var/www/html`).
- **La IP de la VM.** Es la dirección con la que se abre el sitio desde
  Windows.

---

## Opción A · Subcarpeta (la más rápida)

El sitio queda en `http://IP-DE-LA-VM/nivelup`. Se hace en cinco minutos y
funciona siempre. Sirve para probar hoy mismo que el sitio corre.

Suponiendo que WordPress está en `/var/www/html`:

```bash
sudo mkdir -p /var/www/html/nivelup
```

Copiar los archivos desde Windows (ejecutar en PowerShell, dentro de la
carpeta del proyecto):

```powershell
scp -r sitio/* usuario@IP-DE-LA-VM:/tmp/nivelup/
```

De vuelta en la VM:

```bash
sudo cp -r /tmp/nivelup/* /var/www/html/nivelup/
sudo chown -R $USER:www-data /var/www/html/nivelup
sudo find /var/www/html/nivelup -type d -exec chmod 750 {} \;
sudo find /var/www/html/nivelup -type f -exec chmod 640 {} \;
```

Abrir `http://IP-DE-LA-VM/nivelup` en el navegador de Windows.

> Las reglas de reescritura de WordPress no interfieren: solo se aplican
> cuando el archivo o la carpeta pedida **no** existe, y `nivelup/` existe
> de verdad.

**Desventaja para la nota:** no demuestra configuración de Virtual Host,
que es un requisito explícito del enunciado. Úsala para probar, no para
entregar.

---

## Opción B · Virtual Host en otro puerto (la recomendada)

El sitio queda en `http://IP-DE-LA-VM:8080` y WordPress sigue en el puerto
80. Cumple el requisito de Virtual Host y no obliga a tocar el archivo
`hosts` de Windows, que es donde más se traba la gente.

### B.1 Ubicar el sitio fuera del WordPress

```bash
sudo mkdir -p /var/www/nivelup
sudo cp -r /tmp/nivelup/* /var/www/nivelup/
sudo chown -R $USER:www-data /var/www/nivelup
sudo find /var/www/nivelup -type d -exec chmod 750 {} \;
sudo find /var/www/nivelup -type f -exec chmod 640 {} \;
```

### B.2 Decirle a Apache que escuche el 8080

```bash
sudo nano /etc/apache2/ports.conf
```

Debajo de `Listen 80` agregar:

```
Listen 8080
```

### B.3 Crear el Virtual Host

```bash
sudo nano /etc/apache2/sites-available/nivelup.conf
```

```apache
<VirtualHost *:8080>
    ServerName   nivelup.local
    ServerAdmin  webmaster@nivelup.local
    DocumentRoot /var/www/nivelup

    <Directory /var/www/nivelup>
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Require all granted
        DirectoryIndex index.php index.html
    </Directory>

    <Directory /var/www/nivelup/includes>
        Require all denied
    </Directory>

    ServerSignature Off
    ErrorLog  ${APACHE_LOG_DIR}/nivelup_error.log
    CustomLog ${APACHE_LOG_DIR}/nivelup_access.log combined
</VirtualHost>
```

Es el mismo archivo `configuracion/nivelup.conf` del proyecto, cambiando
`*:80` por `*:8080` y quitando el `ServerAlias`.

### B.4 Activarlo

```bash
sudo a2ensite nivelup.conf
```

```bash
sudo apache2ctl configtest
```

Debe responder `Syntax OK`. Si dice otra cosa, no recargar todavía: el
mensaje indica la línea del error.

```bash
sudo systemctl reload apache2
```

### B.5 Abrir el puerto en el firewall

```bash
sudo ufw allow 8080/tcp
```

Verificar que Apache quedó escuchando en los dos puertos:

```bash
sudo ss -tlnp | grep apache2
```

Ahora `http://IP-DE-LA-VM` abre WordPress y `http://IP-DE-LA-VM:8080` abre
NivelUp.

### B.6 (Opcional) Que responda por nombre

Si prefieren `http://nivelup.local:8080` en lugar de la IP, editar en
Windows el archivo `C:\Windows\System32\drivers\etc\hosts` con el Bloc de
notas **abierto como administrador** y agregar:

```
192.168.1.50    nivelup.local
```

Cambiando la IP por la de la VM.

---

## La base de datos

WordPress ya usa MySQL, así que el motor está listo. Solo hay que agregar
**nuestra** base, que es independiente de la de WordPress y no la toca.

Subir el esquema desde Windows:

```powershell
scp base-datos/esquema.sql usuario@IP-DE-LA-VM:/tmp/
```

Cargarlo en la VM:

```bash
sudo mysql < /tmp/esquema.sql
```

Verificar:

```bash
sudo mysql -e "SHOW DATABASES;"
```

Deben aparecer las dos: la de WordPress y `nivelup`.

```bash
sudo mysql -e "USE nivelup; SHOW TABLES; SELECT COUNT(*) FROM productos;"
```

Tres tablas y 8 productos.

> El script crea el usuario `nivelup_app` con permisos **solo** sobre la
> base `nivelup`. No puede tocar la de WordPress. Eso es aplicación directa
> del principio de menor privilegio, y da un buen punto que mencionar en la
> sustentación.

Por último, cambiar la contraseña de ejemplo:

```bash
sudo mysql -e "ALTER USER 'nivelup_app'@'localhost' IDENTIFIED BY 'la-clave-nueva';"
```

Y reflejarla en el archivo de configuración del sitio:

```bash
sudo nano /var/www/nivelup/includes/config.php
```

---

## Si usas Nginx en lugar de Apache

Algunas instalaciones de WordPress vienen con Nginx. El concepto es el
mismo, cambia el archivo de configuración:

```bash
sudo nano /etc/nginx/sites-available/nivelup
```

```nginx
server {
    listen 8080;
    server_name nivelup.local;
    root /var/www/nivelup;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ^~ /includes/ {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/nivelup /etc/nginx/sites-enabled/
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

Verificar antes la versión de PHP-FPM que está corriendo, porque el nombre
del socket cambia:

```bash
ls /run/php/
```

> **Nota para la entrega:** el enunciado pide explícitamente **Apache**. Si
> tu VM tiene Nginx, vale la pena instalar Apache en paralelo (en el puerto
> 8080) y dejar el sitio ahí, para no perder ese punto.

---

## Opción C · Quitar WordPress

Solo si esa VM no la necesitas para otra cosa. Deja el servidor más limpio
y el sitio queda directo en el puerto 80, sin `:8080` en la URL.

```bash
sudo a2dissite 000-default.conf
```

Con eso WordPress deja de responder pero los archivos siguen ahí, así que es
reversible. En el Virtual Host de NivelUp se cambia `*:8080` por `*:80` y se
recarga Apache.

No borres nada hasta estar seguro de que el sitio nuevo funciona.

---

## Verificación final

| Qué                         | Comando o acción                                        |
|-----------------------------|---------------------------------------------------------|
| Apache escucha los 2 puertos| `sudo ss -tlnp \| grep apache2`                          |
| Los dos sitios cargan       | `http://IP` y `http://IP:8080`                           |
| Virtual Hosts reconocidos   | `apache2ctl -S`                                          |
| Permisos correctos          | `ls -l /var/www/nivelup`                                 |
| Base de datos conectada     | Productos y blog muestran contenido en el sitio          |
| Formulario guardando        | `sudo mysql -e "SELECT * FROM nivelup.mensajes\G"`       |
| Errores                     | `sudo tail -20 /var/log/apache2/nivelup_error.log`       |

---

## Problemas comunes

**El puerto 8080 no responde desde Windows**
Revisar en este orden: `sudo ss -tlnp | grep 8080` (¿Apache escucha?),
`sudo ufw status` (¿el puerto está abierto?), y que el adaptador de red de
la VM esté en modo **puente**, no NAT. Con NAT la VM no es visible desde el
equipo anfitrión sin redirección de puertos.

**Error 403 Forbidden**
Permisos. Comprobar el camino completo con
`namei -l /var/www/nivelup/index.php`: todos los directorios necesitan
permiso de ejecución para el grupo.

**Página en blanco**
Error de PHP. Mirar `sudo tail -20 /var/log/apache2/nivelup_error.log`.
Suele ser que falta el módulo `php-mysql`: `sudo apt install php-mysql` y
recargar Apache.

**«Error de conexión a la base de datos»**
La clave de `config.php` no coincide con la del usuario MySQL. Probar a
mano: `mysql -u nivelup_app -p nivelup`.

**Al entrar a la subcarpeta aparece la página de WordPress**
Estás en la Opción A y la carpeta no existe donde creías. Confirmar la raíz
real de WordPress con `apache2ctl -S | grep DocumentRoot`.
