/* =====================================================================
   NivelUp — JavaScript principal
   Bloques: tema claro/oscuro, menu movil, navbar con sombra,
   volver arriba, animaciones al hacer scroll, carrusel, FAQ,
   galeria con visor y validacion del formulario.
   ===================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* ---------------- 1. Modo claro / oscuro ----------------------- */
    const botonTema = document.getElementById('boton-tema');
    if (botonTema) {
        botonTema.addEventListener('click', function () {
            const esOscuro = document.documentElement.dataset.tema === 'oscuro';
            if (esOscuro) {
                delete document.documentElement.dataset.tema;
                localStorage.setItem('tema', 'claro');
            } else {
                document.documentElement.dataset.tema = 'oscuro';
                localStorage.setItem('tema', 'oscuro');
            }
        });
    }

    /* ---------------- 2. Menu hamburguesa (movil) ------------------ */
    const hamburguesa = document.getElementById('hamburguesa');
    const menu = document.getElementById('menu');
    if (hamburguesa && menu) {
        hamburguesa.addEventListener('click', function () {
            const abierto = menu.classList.toggle('abierto');
            hamburguesa.classList.toggle('abierto', abierto);
            hamburguesa.setAttribute('aria-expanded', abierto);
        });
        // Cerrar al elegir una opcion
        menu.querySelectorAll('a').forEach(function (enlace) {
            enlace.addEventListener('click', function () {
                menu.classList.remove('abierto');
                hamburguesa.classList.remove('abierto');
                hamburguesa.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /* ---------------- 3. Sombra de la navbar y boton subir --------- */
    const navbar = document.getElementById('navbar');
    const botonSubir = document.getElementById('volver-arriba');

    function alDesplazar() {
        const y = window.scrollY;
        if (navbar) navbar.classList.toggle('navbar--con-sombra', y > 10);
        if (botonSubir) botonSubir.classList.toggle('visible', y > 400);
    }
    window.addEventListener('scroll', alDesplazar, { passive: true });
    alDesplazar();

    if (botonSubir) {
        botonSubir.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ---------------- 4. Animacion de aparicion -------------------- */
    const animables = document.querySelectorAll('.aparece');
    if ('IntersectionObserver' in window && animables.length) {
        const observador = new IntersectionObserver(function (entradas) {
            entradas.forEach(function (entrada) {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add('visible');
                    observador.unobserve(entrada.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        animables.forEach(function (el) { observador.observe(el); });
    } else {
        animables.forEach(function (el) { el.classList.add('visible'); });
    }

    /* ---------------- 5. Carrusel de imagenes ---------------------- */
    document.querySelectorAll('.carrusel').forEach(function (carrusel) {
        const pista   = carrusel.querySelector('.carrusel__pista');
        const laminas = carrusel.querySelectorAll('.carrusel__lamina');
        const puntos  = carrusel.querySelectorAll('.carrusel__punto');
        const anterior = carrusel.querySelector('.carrusel__flecha--izq');
        const siguiente = carrusel.querySelector('.carrusel__flecha--der');
        if (!pista || laminas.length === 0) return;

        let actual = 0;
        let temporizador = null;

        function mostrar(indice) {
            actual = (indice + laminas.length) % laminas.length;
            pista.style.transform = 'translateX(-' + (actual * 100) + '%)';
            puntos.forEach(function (p, i) { p.classList.toggle('activo', i === actual); });
        }

        function iniciarAuto() {
            detenerAuto();
            temporizador = setInterval(function () { mostrar(actual + 1); }, 5000);
        }
        function detenerAuto() {
            if (temporizador) clearInterval(temporizador);
        }

        if (anterior)  anterior.addEventListener('click',  function () { mostrar(actual - 1); iniciarAuto(); });
        if (siguiente) siguiente.addEventListener('click', function () { mostrar(actual + 1); iniciarAuto(); });
        puntos.forEach(function (punto, i) {
            punto.addEventListener('click', function () { mostrar(i); iniciarAuto(); });
        });

        carrusel.addEventListener('mouseenter', detenerAuto);
        carrusel.addEventListener('mouseleave', iniciarAuto);

        mostrar(0);
        iniciarAuto();
    });

    /* ---------------- 6. Acordeon de preguntas frecuentes ---------- */
    document.querySelectorAll('.faq__pregunta').forEach(function (boton) {
        boton.addEventListener('click', function () {
            const item = boton.closest('.faq__item');
            const respuesta = item.querySelector('.faq__respuesta');
            const yaAbierto = item.classList.contains('abierto');

            // Cerrar los demas (comportamiento de acordeon)
            document.querySelectorAll('.faq__item.abierto').forEach(function (otro) {
                otro.classList.remove('abierto');
                otro.querySelector('.faq__respuesta').style.maxHeight = null;
            });

            if (!yaAbierto) {
                item.classList.add('abierto');
                respuesta.style.maxHeight = respuesta.scrollHeight + 'px';
            }
        });
    });

    /* ---------------- 7. Galeria: filtros y visor ------------------ */
    // Solo los filtros que llevan data-filtro (los de productos.php son
    // enlaces normales y recargan la pagina, no se manejan aqui).
    const filtros = document.querySelectorAll('.galeria__filtro[data-filtro]');
    const items   = document.querySelectorAll('.galeria__item');

    filtros.forEach(function (filtro) {
        filtro.addEventListener('click', function () {
            const categoria = filtro.dataset.filtro;
            filtros.forEach(function (f) { f.classList.remove('activo'); });
            filtro.classList.add('activo');

            items.forEach(function (item) {
                const coincide = categoria === 'todos' || item.dataset.categoria === categoria;
                item.style.display = coincide ? '' : 'none';

                // Al filtrar, un elemento puede subir a la primera fila sin
                // haber pasado nunca por el observador de scroll. Sin esto se
                // mostraria con opacidad cero y pareceria que el filtro fallo.
                if (coincide) item.classList.add('visible');
            });
        });
    });

    const visor = document.getElementById('visor');
    if (visor) {
        const contenido = visor.querySelector('.visor__contenido');

        items.forEach(function (item) {
            item.addEventListener('click', function () {
                const tipo = item.dataset.tipo;
                const fuente = item.dataset.fuente;
                if (!fuente) return;   // marcador sin archivo real

                contenido.innerHTML = tipo === 'video'
                    ? '<video src="' + fuente + '" controls autoplay></video>'
                    : '<img src="' + fuente + '" alt="' + (item.dataset.titulo || '') + '">';
                visor.classList.add('abierto');
                document.body.style.overflow = 'hidden';
            });
        });

        function cerrarVisor() {
            visor.classList.remove('abierto');
            contenido.innerHTML = '';
            document.body.style.overflow = '';
        }

        visor.addEventListener('click', function (evento) {
            if (evento.target === visor || evento.target.classList.contains('visor__cerrar')) {
                cerrarVisor();
            }
        });
        document.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape') cerrarVisor();
        });
    }

    /* ---------------- 8. Validacion del formulario ----------------- */
    const formulario = document.getElementById('formulario-contacto');
    if (formulario) {
        const reglas = {
            nombre:   function (v) {
                if (v.trim().length < 3) return 'Escriba su nombre completo (mínimo 3 caracteres).';
                if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'.-]+$/.test(v)) return 'El nombre solo puede contener letras.';
                return '';
            },
            correo:   function (v) {
                if (!/^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(v)) return 'Ingrese un correo electrónico válido.';
                return '';
            },
            telefono: function (v) {
                if (v.trim() === '') return '';   // campo opcional
                if (!/^[\d\s()+-]{7,20}$/.test(v)) return 'El teléfono solo admite números, espacios y los signos + ( ) -';
                return '';
            },
            asunto:   function (v) {
                return v.trim() === '' ? 'Seleccione un asunto.' : '';
            },
            mensaje:  function (v) {
                if (v.trim().length < 20) return 'Cuéntenos un poco más (mínimo 20 caracteres).';
                if (v.length > 2000) return 'El mensaje no puede superar los 2000 caracteres.';
                return '';
            }
        };

        function validarCampo(campo) {
            const regla = reglas[campo.name];
            if (!regla) return true;

            const contenedor = campo.closest('.campo');
            const error = regla(campo.value);
            const textoError = contenedor.querySelector('.error-texto');

            if (error) {
                contenedor.classList.add('invalido');
                if (textoError) textoError.textContent = error;
                return false;
            }
            contenedor.classList.remove('invalido');
            return true;
        }

        // Validar mientras el usuario escribe (solo si ya hubo un error)
        formulario.querySelectorAll('input, textarea, select').forEach(function (campo) {
            campo.addEventListener('blur', function () { validarCampo(campo); });
            campo.addEventListener('input', function () {
                if (campo.closest('.campo').classList.contains('invalido')) validarCampo(campo);
            });
        });

        formulario.addEventListener('submit', function (evento) {
            let valido = true;
            formulario.querySelectorAll('input, textarea, select').forEach(function (campo) {
                if (!validarCampo(campo)) valido = false;
            });

            if (!valido) {
                evento.preventDefault();
                const primerError = formulario.querySelector('.campo.invalido');
                if (primerError) {
                    primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    const entrada = primerError.querySelector('input, textarea, select');
                    if (entrada) entrada.focus();
                }
            }
        });

        // Contador de caracteres del mensaje
        const mensaje = formulario.querySelector('[name="mensaje"]');
        const contador = document.getElementById('contador-mensaje');
        if (mensaje && contador) {
            mensaje.addEventListener('input', function () {
                contador.textContent = mensaje.value.length + ' / 2000';
            });
        }
    }

    /* ---------------- 9. Foco de luz en las tarjetas ---------------- */
    /* Escribe la posicion del cursor en dos variables CSS de la tarjeta.
       El degradado radial del CSS las lee y dibuja el foco justo debajo
       del mouse. Sin esto la tarjeta solo sube y se siente inerte. */
    const conFoco = document.querySelectorAll('.tarjeta, .producto, .entrada');
    const menosMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!menosMovimiento) {
        conFoco.forEach(function (tarjeta) {
            tarjeta.addEventListener('mousemove', function (evento) {
                const caja = tarjeta.getBoundingClientRect();
                const x = ((evento.clientX - caja.left) / caja.width) * 100;
                const y = ((evento.clientY - caja.top) / caja.height) * 100;
                tarjeta.style.setProperty('--mx', x + '%');
                tarjeta.style.setProperty('--my', y + '%');
            });
        });
    }

    /* ---------------- 10. Contador animado de cifras --------------- */
    const cifras = document.querySelectorAll('.cifra__numero[data-hasta]');
    if ('IntersectionObserver' in window && cifras.length) {
        const obsCifras = new IntersectionObserver(function (entradas) {
            entradas.forEach(function (entrada) {
                if (!entrada.isIntersecting) return;
                const el = entrada.target;
                const hasta = parseInt(el.dataset.hasta, 10);
                const sufijo = el.dataset.sufijo || '';
                let valor = 0;
                const paso = Math.max(1, Math.ceil(hasta / 45));

                const intervalo = setInterval(function () {
                    valor += paso;
                    if (valor >= hasta) { valor = hasta; clearInterval(intervalo); }
                    el.textContent = valor + sufijo;
                }, 30);

                obsCifras.unobserve(el);
            });
        }, { threshold: 0.5 });

        cifras.forEach(function (c) { obsCifras.observe(c); });
    }
});
