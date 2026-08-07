# Prompts para generar las imágenes del sitio

Para usar con Nano Banana, Gemini, o cualquier generador de imágenes.

## Antes de empezar: tres reglas

**Proporción 16:9.** El carrusel recorta a 16:7 en pantalla grande y a 16:10
en móvil, así que la imagen pierde una franja arriba y otra abajo. Todo lo
importante tiene que quedar en la banda central. Si el generador permite
elegir, pide **16:9**; si no, pide horizontal y luego yo la recorto.

**Sin texto ni marcas.** Los modelos meten letras deformes y logotipos
inventados en cuanto pueden. Todos los prompts terminan pidiendo que no
aparezcan, y aun así conviene revisar la imagen generada: si tiene texto
raro, se regenera.

**Coherencia de color.** El sitio es violeta y magenta. Los prompts piden
iluminación en esos tonos para que las fotos no desentonen con el diseño.

---

# El taller por dentro (carrusel de la portada)

Son cuatro imágenes. Guárdalas en `sitio/img/carrusel/` con estos nombres
exactos.

---

## 1 · `build.jpg`
*Pie de foto en el sitio: «Cada equipo se arma a mano y se prueba antes de salir»*

```
Fotografía profesional en formato horizontal 16:9 de un técnico armando un
computador de escritorio sobre una mesa de trabajo limpia. Se ven sus manos
instalando un módulo de memoria RAM en la tarjeta madre, dentro de un
gabinete moderno con panel lateral de vidrio. Iluminación de estudio suave y
fría, con reflejos violeta y magenta provenientes de los ventiladores RGB del
equipo. Componentes ordenados alrededor: destornillador, brida para cables,
tapete antiestático. Profundidad de campo media, el foco en las manos y la
tarjeta madre. Estilo realista, moderno y limpio, nada de polvo ni desorden.
Sin texto, sin logotipos, sin marcas visibles.
```

---

## 2 · `taller.jpg`
*Pie: «Mesa de trabajo: aquí pasa toda la magia»*

```
Fotografía profesional horizontal 16:9 de una mesa de taller de reparación
de computadores, vista en ángulo de tres cuartos. Sobre el tapete antiestático
azul oscuro hay herramientas organizadas: juego de destornilladores de
precisión, pinzas, aire comprimido en lata, pasta térmica y una tarjeta madre
desmontada. Al fondo, desenfocado, un gabinete abierto con iluminación
violeta. Luz cenital limpia tipo lámpara de trabajo, ambiente profesional y
ordenado. Paleta fría con acentos violeta y magenta. Composición centrada,
espacio de aire arriba y abajo. Sin texto, sin logotipos, sin marcas.
```

---

## 3 · `interior.jpg`
*Pie: «Cableado ordenado por dentro, no solo por fuera»*

```
Fotografía macro profesional horizontal 16:9 del interior de un computador de
escritorio recién armado, con el panel lateral retirado. Gestión de cables
impecable: los cables van peinados y sujetos con bridas por detrás de la
bandeja de la tarjeta madre. Se aprecian la tarjeta gráfica, el disipador del
procesador y tres ventiladores con iluminación RGB en tonos violeta y magenta.
Fondo oscuro, luz lateral suave que resalta los bordes metálicos. Enfoque
nítido en el centro del gabinete. Aspecto premium, limpio y nuevo, sin polvo.
Sin texto, sin logotipos, sin marcas.
```

---

## 4 · `entrega.jpg`
*Pie: «Se entrega encendido, actualizado y listo para jugar»*

```
Fotografía profesional horizontal 16:9 de un computador gamer terminado y
encendido sobre un escritorio de madera clara. El gabinete de vidrio deja ver
la iluminación interna violeta y magenta. Al lado, un monitor encendido
mostrando un escritorio genérico de colores oscuros, un teclado mecánico
retroiluminado y unos audífonos colgados. Ambiente de habitación acogedora al
atardecer, luz cálida entrando por una ventana desenfocada al fondo.
Composición centrada y equilibrada. Estilo realista, cálido y aspiracional.
Sin texto en la pantalla, sin logotipos, sin marcas.
```

---

# Las que también faltan

Si te sobra tiempo, estas cuatro completan el sitio. Van en las carpetas que
se indican.

## `sitio/img/blog/limpieza.jpg` — proporción 16:9

```
Fotografía profesional horizontal de una persona limpiando el interior de un
computador de escritorio con aire comprimido en lata. Se ve el gabinete
abierto sobre una mesa, con los ventiladores y el disipador visibles y algo de
polvo saliendo. Manos con guantes de nitrilo azul. Iluminación de taller
clara y neutra, ambiente profesional. Enfoque en el disipador. Sin texto, sin
logotipos, sin marcas.
```

## `sitio/img/galeria/dual-boot.jpg` — proporción 4:3

```
Fotografía horizontal de un monitor de computador mostrando un menú de
arranque de sistema operativo: una lista simple de opciones en texto blanco
sobre fondo negro, estilo terminal, con una opción resaltada. El monitor está
sobre un escritorio ordenado, en penumbra, con luz violeta tenue de fondo.
Enfoque nítido en la pantalla. El texto de la pantalla debe verse borroso o
ilegible a propósito, no inventar palabras.
```

## `sitio/img/galeria/build-rgb.jpg` — proporción 4:3

```
Fotografía profesional de un computador gamer terminado, fotografiado de
frente en un cuarto oscuro. El panel lateral de vidrio deja ver los
ventiladores y las memorias iluminadas en violeta y magenta intensos. Reflejos
suaves sobre la superficie de la mesa. Estética premium tipo catálogo de
producto, fondo negro limpio. Sin texto, sin logotipos, sin marcas.
```

## `sitio/img/productos/pc-nivel2.jpg` — proporción 3:2

```
Fotografía de producto de un computador de escritorio para videojuegos,
fotografiado en tres cuartos sobre fondo gris neutro degradado. Gabinete negro
de tamaño medio con panel de vidrio templado, ventiladores con iluminación
violeta. Iluminación de estudio profesional con dos luces suaves, sombra
tenue debajo. Estilo catálogo de tienda, nítido y limpio. Sin texto, sin
logotipos, sin marcas.
```

---

# Después de generarlas

Déjalas en las carpetas indicadas con esos nombres exactos y avísame. Yo me
encargo de:

1. **Redimensionar y comprimir.** Las imágenes generadas suelen pesar varios
   megabytes; hay que dejarlas por debajo de 200 KB o el sitio se vuelve
   lento y baja la nota en experiencia de usuario.
2. **Revisar que no tengan texto inventado**, que es el fallo más común de
   los generadores.
3. **Anotarlas en `sitio/img/CREDITOS.txt`** como generadas por inteligencia
   artificial. Conviene declararlo: si la docente pregunta, es mejor tenerlo
   escrito desde el principio que tener que explicarlo después.

## Una advertencia honesta

Pregunta a la docente si acepta imágenes generadas con IA. Algunos
profesores las prohíben en trabajos evaluados, otros las aceptan si se
declaran. Es una consulta de dos minutos que evita un problema el día de la
entrega.

Si resulta que no las acepta, la alternativa sigue siendo tomar las fotos
ustedes mismos: si alguno del grupo tiene un PC de escritorio, abrirlo y
fotografiar el interior da mejor resultado que cualquier imagen generada, y
responde sola la pregunta de si el trabajo es suyo.
