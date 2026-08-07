# Cómo subir los archivos del proyecto a tu máquina virtual

Guía independiente. No hace falta haber seguido las otras: aquí está todo
desde cero.

El problema que resuelve es simple de enunciar y fácil de enredar. Los
archivos del sitio están en tu **Windows**, y tienen que terminar dentro de
la carpeta que Apache publica en tu **máquina virtual con Ubuntu**. Son dos
computadores distintos aunque uno viva dentro del otro.

---

## Lo que necesitas saber antes de empezar

Tres datos. Anótalos, los vas a usar todo el tiempo.

| Dato | Cómo obtenerlo | Ejemplo |
|------|----------------|---------|
| Usuario de Ubuntu | Es con el que inicias sesión en la VM | `juan` |
| IP de la VM | Ejecuta `ip a` dentro de Ubuntu | `192.168.1.53` |
| Ruta del proyecto en Windows | Cópiala de la barra del Explorador | `C:\Proyecto\sitio` |

Para ver la IP, abre la terminal de Ubuntu y escribe:

```bash
ip -4 addr show | grep inet
```

Te va a mostrar dos líneas. Ignora la que dice `127.0.0.1` — esa es interna.
La que sirve es la otra, normalmente algo como `192.168.x.x`.

> **Si no aparece ninguna 192.168.x.x**, tu VM está en modo NAT y no es
> visible desde Windows. Apaga la VM, entra a Configuración → Red en
> VirtualBox, cambia «Conectado a» de *NAT* a **Adaptador puente**, y
> vuelve a encenderla.

---

## Requisito: que la VM acepte conexiones

En Ubuntu, ejecuta:

```bash
sudo apt install -y openssh-server
```

Si ya estaba instalado, no pasa nada, el comando lo detecta. Después
verifica que esté corriendo:

```bash
systemctl is-active ssh
```

Debe responder `active`. Con eso tu VM ya puede recibir archivos.

---

## Método 1 · Con el comando scp (el recomendado)

Es el que se usa en el mundo real y el que conviene mostrar en la
sustentación, porque demuestra el requisito de «acceso remoto mediante SSH».

Windows 10 y 11 ya traen la herramienta instalada, no hay que descargar
nada.

### Dónde se escribe

**Esto es lo que más se equivoca la gente, así que léelo con calma.**

El comando `scp` se ejecuta en **Windows**, no dentro de Ubuntu. Tiene
sentido si lo piensas: los archivos están en Windows, y desde ahí los
empujas hacia la VM.

Para saber siempre dónde estás, mira cómo empieza la línea:

| Lo que ves al inicio | Dónde estás | Qué va ahí |
|---|---|---|
| `PS C:\Users\...>` o `C:\Users\...>` | Windows | los comandos `scp` |
| `juan@ubuntu:~$` | La máquina virtual | todo lo que empiece con `sudo` |

Regla corta: **si el comando menciona una ruta con `C:\`, va en Windows.**

### Los comandos

Abre **PowerShell** en Windows (tecla Windows → escribe `PowerShell` →
Enter) y ejecuta, cambiando los datos por los tuyos:

```bash
scp -r "C:\Proyecto\sitio" juan@192.168.1.53:/tmp/
```

Y el archivo de la base de datos:

```bash
scp "C:\Proyecto\base-datos\esquema.sql" juan@192.168.1.53:/tmp/
```

Desglose de lo que significa cada parte:

- `scp` — el programa que copia archivos por SSH
- `-r` — «recursivo», o sea que incluya las subcarpetas. Sin esto solo
  copia archivos sueltos y falla con una carpeta.
- `"C:\Proyecto\sitio"` — qué copiar. Las comillas son obligatorias si la
  ruta tiene espacios.
- `juan@192.168.1.53` — a qué usuario y a qué máquina
- `:/tmp/` — en qué carpeta de la VM dejarlo

### Qué va a pasar

1. La primera vez pregunta si confías en el equipo. Escribe `yes` completo
   (no `y`) y Enter.
2. Pide la contraseña del usuario de Ubuntu. **Mientras la escribes no
   aparece nada en pantalla**, ni asteriscos ni puntos. Es a propósito.
   Escríbela a ciegas y dale Enter.
3. Verás la lista de archivos con porcentajes hasta llegar a 100%.

### Comprobar que llegaron

Ahora sí, en la terminal de Ubuntu:

```bash
ls /tmp/sitio
```

Deben aparecer los archivos `.php` y las carpetas `css`, `js`, `img` e
`includes`.

---

## Método 2 · Con FileZilla (si prefieres arrastrar y soltar)

Más cómodo si te incomoda la terminal. Hace exactamente lo mismo.

1. Descarga **FileZilla Client** de `filezilla-project.org` e instálalo en
   Windows.
2. Ábrelo y llena la barra superior:
   - **Servidor:** `sftp://192.168.1.53`
   - **Nombre de usuario:** tu usuario de Ubuntu
   - **Contraseña:** la de ese usuario
   - **Puerto:** `22`
3. Clic en **Conexión rápida**. Acepta la clave del servidor la primera vez.
4. A la izquierda ves tu Windows, a la derecha tu Ubuntu. Navega a `/tmp` en
   el lado derecho.
5. Arrastra la carpeta `sitio` y el archivo `esquema.sql` de izquierda a
   derecha.

> El prefijo `sftp://` importa. Sin él FileZilla intenta FTP normal, que no
> está instalado por defecto y da error de conexión.

---

## Método 3 · Carpeta compartida de VirtualBox (sin red)

Sirve si la VM no tiene red o el adaptador puente no funciona en la red de
tu casa o de la universidad.

1. Con la VM **apagada**, en VirtualBox: Configuración → Carpetas
   compartidas → botón de agregar.
2. **Ruta:** la carpeta del proyecto en Windows.
   **Nombre:** `proyecto`.
   Marca **Automontar** y **Hacer permanente**.
3. Enciende la VM e instala las adiciones de invitado:

```bash
sudo apt install -y virtualbox-guest-utils
```

4. Agrega tu usuario al grupo que puede leer la carpeta compartida:

```bash
sudo usermod -aG vboxsf $USER
```

5. **Reinicia la VM.** Este paso no es opcional: el cambio de grupo solo
   toma efecto al volver a iniciar sesión.

6. La carpeta queda disponible en `/media/sf_proyecto`. Cópiala a `/tmp`:

```bash
cp -r /media/sf_proyecto/sitio /tmp/
```

---

## Después de subir: poner el sitio en su lugar

Los archivos están en `/tmp`, que es una carpeta temporal. Apache no publica
desde ahí. Hay que moverlos y darles los permisos correctos.

Todo esto va **en la terminal de Ubuntu**:

```bash
sudo mkdir -p /var/www/misitio
```

```bash
sudo cp -r /tmp/sitio/* /var/www/misitio/
```

```bash
sudo chown -R $USER:www-data /var/www/misitio
```

```bash
sudo find /var/www/misitio -type d -exec chmod 750 {} \;
```

```bash
sudo find /var/www/misitio -type f -exec chmod 640 {} \;
```

### Por qué esos permisos y no `777`

Es la pregunta que más hace el docente, así que vale entenderla.

Apache no corre como tu usuario ni como administrador: corre bajo una cuenta
de servicio llamada `www-data`. Con `chown` le dijimos que tu usuario es el
dueño y que `www-data` es el grupo.

El `750` en las carpetas significa: el dueño puede entrar, leer y escribir;
el grupo puede entrar y leer; el resto del sistema, nada. El `640` en los
archivos: el dueño lee y escribe, el grupo solo lee, el resto nada.

Apache únicamente necesita **leer** el sitio para servirlo. Darle permiso de
escritura, o poner `777` que se lo da a todo el mundo, sería regalar
privilegios que nadie pidió. Eso se llama principio de menor privilegio.

Para ver cómo quedó:

```bash
ls -l /var/www/misitio
```

Debes ver `-rw-r-----` en los archivos y `drwxr-x---` en las carpetas.

---

## Errores comunes

**`ssh: Could not resolve hostname c`**
Ejecutaste el `scp` dentro de Ubuntu en vez de Windows. Linux leyó la `C:`
de la ruta como si fuera el nombre de un servidor. Ciérralo y córrelo en
PowerShell.

**`El token '&&' no es un separador de instrucciones válido`**
Estás pegando un comando de Linux en PowerShell. Esa línea va en la terminal
de Ubuntu.

**Repite «Please type 'yes', 'no' or the fingerprint»**
Le diste Enter sin escribir nada. Haz clic dentro de la ventana para
asegurarte de que tiene el foco, escribe `yes` y dale Enter.

**`Permission denied, please try again`**
Contraseña equivocada. Recuerda que no se ve mientras la escribes; si
dudas, bórrala con retroceso varias veces y empieza de nuevo.

**`Connection refused` o se queda esperando**
No hay SSH en la VM, o la IP cambió. Verifica con `systemctl is-active ssh`
dentro de Ubuntu, y vuelve a mirar la IP con `ip a`.

**`No such file or directory` al copiar de `/tmp`**
La carpeta no se llama como creías. Mira qué hay con `ls /tmp`.

**Error 403 Forbidden al abrir el sitio**
Permisos mal aplicados. Revisa todo el camino con:

```bash
namei -l /var/www/misitio/index.php
```

Cada carpeta del recorrido necesita permiso de ejecución para el grupo.

**Página en blanco**
Un error de PHP. Mira las últimas líneas del registro:

```bash
sudo tail -20 /var/log/apache2/error.log
```

Lo más frecuente es que falte el módulo de MySQL:
`sudo apt install php-mysql` y luego `sudo systemctl restart apache2`.

**El sitio carga pero sin estilos, o los cambios no se ven**
El navegador tiene guardado el archivo viejo. Recarga con **Ctrl+F5**. Si
persiste, abre la página en una ventana de incógnito para confirmar que el
archivo nuevo sí está en el servidor.

---

## Resumen en cinco líneas

```
1. En Ubuntu:    sudo apt install -y openssh-server
2. En Ubuntu:    ip -4 addr show | grep inet      → anota la IP
3. En Windows:   scp -r "C:\ruta\sitio" usuario@IP:/tmp/
4. En Ubuntu:    sudo cp -r /tmp/sitio/* /var/www/misitio/
5. En Ubuntu:    sudo chown -R $USER:www-data /var/www/misitio
```
