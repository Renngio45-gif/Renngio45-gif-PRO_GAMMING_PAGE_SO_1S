# Despliegue del sitio NivelUp en Ubuntu Server

Guía paso a paso, en el orden en que se ejecuta. Cada bloque incluye el
comando de verificación, porque en la sustentación conviene poder demostrar
que cada pieza quedó funcionando.

> Convención: `servidor$` son comandos dentro del Ubuntu Server;
> `local>` son comandos en el equipo Windows del grupo.

---

## 1. Instalación de Ubuntu Server

Descargar **Ubuntu Server 24.04 LTS** e instalar en la máquina virtual
(VirtualBox o VMware) con estos parámetros:

| Parámetro          | Valor sugerido                          |
|--------------------|-----------------------------------------|
| Memoria RAM        | 2048 MB                                 |
| Disco              | 20 GB                                   |
| Red                | Adaptador puente (Bridged)              |
| Usuario            | `admnivelup`                            |
| Nombre del equipo  | `srv-nivelup`                           |
| Paquete adicional  | Marcar **Install OpenSSH server**       |

El adaptador puente es importante: pone la máquina virtual en la misma red
que su computador, y así el sitio se puede abrir desde el navegador del
anfitrión durante la presentación.

---

## 2. Dirección IP fija

Sin IP fija, el servidor cambia de dirección al reiniciar y el enlace de la
presentación deja de funcionar.

```bash
ip a                       # ver el nombre de la interfaz (ej. enp0s3)
sudo nano /etc/netplan/50-cloud-init.yaml
```

```yaml
network:
  version: 2
  ethernets:
    enp0s3:
      dhcp4: no
      addresses: [192.168.1.50/24]
      routes:
        - to: default
          via: 192.168.1.1
      nameservers:
        addresses: [8.8.8.8, 1.1.1.1]
```

```bash
sudo netplan apply
ip a | grep 192.168        # verificación
```

Ajusten `192.168.1.x` al rango de su red. El comando `ip r` muestra la
puerta de enlace real.

---

## 3. Actualización del sistema

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y unattended-upgrades
```

---

## 4. Acceso remoto por SSH

### 4.1 Generar el par de llaves (en Windows)

```powershell
ssh-keygen -t ed25519 -C "proyecto-so"
type $env:USERPROFILE\.ssh\id_ed25519.pub
```

### 4.2 Copiar la llave pública al servidor

```bash
mkdir -p ~/.ssh && chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys      # pegar aquí la llave pública
chmod 600 ~/.ssh/authorized_keys
```

### 4.3 Endurecer el servicio

```bash
sudo nano /etc/ssh/sshd_config
```

```
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
MaxAuthTries 3
```

```bash
sudo systemctl restart ssh
sudo systemctl status ssh        # verificación
```

> **Importante:** antes de cerrar la sesión actual, abrir una segunda
> terminal y comprobar que el ingreso con llave funciona. Si algo quedó mal
> y cierran la única sesión abierta, quedan fuera del servidor.

Conexión desde Windows:

```powershell
ssh admnivelup@192.168.1.50
```

---

## 5. Instalación del entorno LAMP

```bash
sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql
```

Verificación de las cuatro piezas:

```bash
lsb_release -d                   # Linux
apache2 -v                       # Apache
mysql --version                  # MySQL
php -v                           # PHP
systemctl status apache2 mysql   # ambos servicios activos
```

Abrir `http://192.168.1.50` en el navegador: debe aparecer la página
predeterminada de Apache. Si aparece, Linux + Apache ya están listos.

### 5.1 Asegurar MySQL

```bash
sudo mysql_secure_installation
```

Responder: contraseña de root sí, eliminar usuarios anónimos sí, prohibir
ingreso remoto de root sí, eliminar la base de prueba sí, recargar
privilegios sí.

### 5.2 Comprobar que PHP se ejecuta

```bash
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php
```

Abrir `http://192.168.1.50/info.php`. Si se ve la tabla de PHP, funciona.

```bash
sudo rm /var/www/html/info.php   # borrar: expone información del servidor
```

---

## 6. Estructura de directorios y permisos

```bash
sudo mkdir -p /var/www/nivelup
sudo chown -R admnivelup:www-data /var/www/nivelup
sudo chmod -R 750 /var/www/nivelup
```

El criterio: el usuario administrador es el dueño y puede escribir; Apache
(que corre como `www-data`) pertenece al grupo y solo necesita **leer**.
El resto del mundo no tiene ningún permiso.

Después de copiar los archivos se afina la diferencia entre directorios y
archivos:

```bash
sudo find /var/www/nivelup -type d -exec chmod 750 {} \;
sudo find /var/www/nivelup -type f -exec chmod 640 {} \;
```

Verificación:

```bash
ls -l /var/www/nivelup
namei -l /var/www/nivelup/index.php
```

---

## 7. Copiar el sitio al servidor

Desde el equipo Windows, dentro de la carpeta del proyecto:

```powershell
scp -r sitio/* admnivelup@192.168.1.50:/tmp/nivelup/
```

En el servidor:

```bash
sudo cp -r /tmp/nivelup/* /var/www/nivelup/
sudo chown -R admnivelup:www-data /var/www/nivelup
rm -rf /tmp/nivelup
```

> Alternativa: clonar desde un repositorio Git si el grupo lo usa. Es más
> cómodo para actualizar el sitio durante las pruebas.

---

## 8. Base de datos

```bash
sudo mysql < /var/www/nivelup/../base-datos/esquema.sql
```

Si el archivo `esquema.sql` no se copió al servidor, súbanlo primero con
`scp` y ejecuten:

```bash
sudo mysql < ~/esquema.sql
```

Verificación:

```bash
sudo mysql -e "USE nivelup; SHOW TABLES; SELECT COUNT(*) FROM productos;"
```

Deben aparecer las tres tablas y 8 productos.

**Cambiar la contraseña** definida en el script y reflejarla en
`sitio/includes/config.php`:

```bash
sudo mysql -e "ALTER USER 'nivelup_app'@'localhost' IDENTIFIED BY 'la-nueva-clave';"
sudo nano /var/www/nivelup/includes/config.php
```

---

## 9. Virtual Host de Apache

```bash
sudo cp /var/www/nivelup/../configuracion/nivelup.conf /etc/apache2/sites-available/
sudo a2ensite nivelup.conf
sudo a2dissite 000-default.conf
sudo apache2ctl configtest        # debe decir: Syntax OK
sudo systemctl reload apache2
```

Para que el nombre `nivelup.local` resuelva, agregarlo al archivo hosts.

En el servidor:

```bash
echo "127.0.0.1 nivelup.local" | sudo tee -a /etc/hosts
```

En Windows, abrir el Bloc de notas **como administrador**, editar
`C:\Windows\System32\drivers\etc\hosts` y agregar:

```
192.168.1.50    nivelup.local
```

---

## 10. Firewall

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Apache'
sudo ufw enable
sudo ufw status verbose           # verificación
```

Debe quedar en `deny (incoming)` por defecto, con 22, 80 y 443 permitidos.

---

## 11. Ajuste final de PHP

```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

> El número de la ruta es la versión de PHP instalada. En Ubuntu 22.04 es
> `8.1`; en 24.04 es `8.3`. Confírmalo con `php -v` o con `ls /etc/php/`.

```
display_errors = Off
expose_php = Off
```

```bash
sudo systemctl restart apache2
```

Y en `sitio/includes/config.php` cambiar:

```php
define('MODO_DESARROLLO', false);
```

Así, si algo falla durante la sustentación, el visitante ve un mensaje
genérico en lugar de la ruta interna de los archivos.

---

## 12. Comprobación final

| Qué se comprueba              | Cómo                                                       |
|-------------------------------|------------------------------------------------------------|
| Sitio en línea                | `http://nivelup.local` desde el navegador de Windows       |
| Todas las secciones           | Recorrer los 8 enlaces del menú                            |
| Base de datos conectada       | Productos y blog muestran contenido                        |
| Formulario funcionando        | Enviar un mensaje de prueba                                |
| Registro en MySQL             | `sudo mysql -e "SELECT * FROM nivelup.mensajes\G"`         |
| Responsive                    | F12 → modo dispositivo → iPhone / iPad                     |
| Modo oscuro                   | Botón de la barra superior, recargar y verificar que persiste |
| Bitácoras de Apache           | `sudo tail -f /var/log/apache2/nivelup_access.log`         |

---

## Evidencias para el informe

Capturas de pantalla que conviene tomar mientras se ejecuta el despliegue:

1. `lsb_release -a` y `uname -a` (sistema operativo instalado)
2. `systemctl status apache2 mysql` (servicios activos)
3. `apache2 -v`, `mysql --version`, `php -v` (las cuatro piezas del LAMP)
4. Conexión SSH desde Windows con autenticación por llave
5. `ls -l /var/www/nivelup` (permisos y propietario)
6. `sudo apache2ctl configtest` con «Syntax OK»
7. `sudo ufw status verbose`
8. `SHOW TABLES;` de la base de datos
9. El sitio abierto en el navegador con la URL `nivelup.local` visible
10. El mensaje de prueba guardado en la tabla `mensajes`
11. El sitio en vista móvil y en modo oscuro

---

## Problemas comunes

**Error 403 Forbidden**
Permisos mal aplicados. Revisar que `www-data` pertenezca al grupo y que
todos los directorios del camino tengan permiso de ejecución:
`namei -l /var/www/nivelup/index.php`.

**Página en blanco**
Error de PHP con `display_errors` apagado. Revisar
`sudo tail -20 /var/log/apache2/nivelup_error.log`.

**«Error de conexión a la base de datos»**
La contraseña de `config.php` no coincide con la del usuario MySQL.
Comprobar con `mysql -u nivelup_app -p nivelup`.

**El navegador no abre el sitio**
Verificar en este orden: `ping 192.168.1.50`, `sudo ufw status`,
`systemctl status apache2`, y que el adaptador de la máquina virtual esté
en modo puente.

**Los estilos no cargan**
Los archivos `.css` y `.js` quedaron con permisos incorrectos. Reaplicar
`chmod 640` a los archivos y `750` a los directorios.
