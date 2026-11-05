{{-- Logo animado: el logo de Laravel montado con bloques, como en el Tetris.
     Los bloques caen uno a uno y la figura va girando para que el siguiente encaje.
     Al final queda la forma del logo de Laravel (la "L" con el cubo pequeño a la derecha).
     Todo es HTML y CSS: los bloques los creo con un @foreach y las animaciones con @keyframes. --}}
@php
    // Tamaño de cada bloque en píxeles
    $tam = 26;
    $mitad = $tam / 2;

    // Bloques que forman el logo, en el orden en el que caen: [x, y, z]
    // x = hacia la derecha, y = altura (0 es el suelo), z = profundidad
    $bloques = [
        [0, 0, 0],   // base de la columna
        [1, 0, 0],   // pie de la L
        [2, 0, 0],   // final del pie
        [2, 0, -1],  // final del pie, por detrás
        [0, 1, 0],   // columna, segundo bloque
        [0, 2, 0],   // columna, arriba del todo
        [2, 1, -1],  // el cubo pequeño de la derecha
    ];

    // Momento (en % de la animación) en el que empieza a caer cada bloque
    $inicio = [2, 10, 18, 26, 34, 42, 50];
    $duracionCaida = 6;

    // Ángulo final en el que la figura se parece al logo de Laravel
    $giroFinal = -45;
@endphp

<div class="logo-escena" role="img" aria-label="Logo de Laravel montándose con bloques">
    <div class="logo-figura">
        @foreach ($bloques as $numero => $bloque)
            @php
                // Paso de la posición del bloque a píxeles, con la figura centrada.
                // En CSS la y crece hacia abajo, por eso le cambio el signo.
                $px = ($bloque[0] - 1) * $tam;
                $py = (1 - $bloque[1]) * $tam;
                $pz = ($bloque[2] + 0.5) * $tam;
            @endphp
            <div class="logo-bloque" style="--x: {{ $px }}px; --y: {{ $py }}px; --z: {{ $pz }}px; animation-name: caer-{{ $numero }};">
                <div class="cara arriba"></div>
                <div class="cara abajo"></div>
                <div class="cara frente"></div>
                <div class="cara atras"></div>
                <div class="cara derecha"></div>
                <div class="cara izquierda"></div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .logo-escena {
        width: 160px;
        height: 160px;
        perspective: 800px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* La figura entera: preserve-3d hace que los bloques de dentro también sean 3D */
    .logo-figura {
        position: relative;
        width: 0;
        height: 0;
        transform-style: preserve-3d;
        transform: rotateX(-35deg) rotateY({{ $giroFinal }}deg);
        animation: girar-figura 9s infinite;
    }

    .logo-bloque {
        position: absolute;
        transform-style: preserve-3d;
        transform: translate3d(var(--x), var(--y), var(--z));
        animation-duration: 9s;
        animation-iteration-count: infinite;
    }

    /* Cada cara es blanca con el borde rojo de Laravel, como el dibujo del logo */
    .cara {
        position: absolute;
        width: {{ $tam }}px;
        height: {{ $tam }}px;
        left: -{{ $mitad }}px;
        top: -{{ $mitad }}px;
        background: #ffffff;
        border: 2px solid #ff2d20;
    }

    /* Cada cara se gira hacia su lado y se separa del centro del bloque */
    .arriba { transform: rotateX(90deg) translateZ({{ $mitad }}px); background: #fff1f0; }
    .abajo { transform: rotateX(-90deg) translateZ({{ $mitad }}px); }
    .frente { transform: translateZ({{ $mitad }}px); }
    .atras { transform: rotateY(180deg) translateZ({{ $mitad }}px); }
    .derecha { transform: rotateY(90deg) translateZ({{ $mitad }}px); }
    .izquierda { transform: rotateY(-90deg) translateZ({{ $mitad }}px); }

    /* La figura gira 90 grados cada dos bloques, para que se vea dónde encaja el siguiente.
       Empieza tres cuartos de vuelta antes y termina justo en el ángulo del logo. */
    @keyframes girar-figura {
        0%, 16% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 270 }}deg); }
        21%, 32% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 180 }}deg); }
        37%, 48% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 90 }}deg); }
        56%, 100% { transform: rotateX(-35deg) rotateY({{ $giroFinal }}deg); }
    }

    /* Una animación de caída para cada bloque: empieza escondido arriba, cae hasta su sitio
       y se queda quieto. Al final, como en el Tetris al completar, todos caen hacia abajo
       y desaparecen para volver a empezar.
       Uso visibility y no opacity porque opacity rompe el 3D de los bloques (los aplana). */
    @foreach ($inicio as $numero => $momento)
        @keyframes caer-{{ $numero }} {
            0%, {{ $momento }}% {
                visibility: hidden;
                transform: translate3d(var(--x), calc(var(--y) - 180px), var(--z));
                animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6);
            }
            {{ $momento + 1 }}% {
                visibility: visible;
            }
            {{ $momento + $duracionCaida }}%, 90% {
                visibility: visible;
                transform: translate3d(var(--x), var(--y), var(--z));
                animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6);
            }
            97% {
                visibility: visible;
                transform: translate3d(var(--x), calc(var(--y) + 180px), var(--z));
            }
            98%, 100% {
                visibility: hidden;
                transform: translate3d(var(--x), calc(var(--y) + 180px), var(--z));
            }
        }
    @endforeach

    /* Si el usuario tiene activado "reducir movimiento" en su sistema, enseño el logo ya montado y quieto */
    @media (prefers-reduced-motion: reduce) {
        .logo-figura, .logo-bloque {
            animation: none;
        }
    }
</style>
