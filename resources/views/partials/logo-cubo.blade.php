{{-- Logo animado: un cubo de Rubik de 2x2x2 que se monta como un Tetris.
     Las 8 piezas caen una a una y el cubo gira para que la siguiente encaje.
     Todo es HTML y CSS: las piezas las creo con un @foreach y las animaciones con @keyframes. --}}
@php
    // Tamaño de cada pieza en píxeles; $mitad es lo que se separa cada pieza del centro
    $tam = 34;
    $mitad = $tam / 2;

    // Posición de cada pieza: [x, y, z]. En CSS la y crece hacia abajo,
    // así que y = 1 es la capa de abajo (cae primero) e y = -1 la de arriba.
    $piezas = [
        [-1, 1, 1], [1, 1, 1], [1, 1, -1], [-1, 1, -1],
        [-1, -1, 1], [1, -1, 1], [1, -1, -1], [-1, -1, -1],
    ];

    // Momento (en % de la animación) en el que empieza a caer cada pieza
    $inicio = [2, 10, 18, 26, 34, 42, 50, 58];
    $duracionCaida = 6;
@endphp

<div class="cubo-escena" role="img" aria-label="Logo de Proyectos en equipo: un cubo que se va montando pieza a pieza">
    <div class="cubo">
        @foreach ($piezas as $numero => $pieza)
            @php
                $x = $pieza[0];
                $y = $pieza[1];
                $z = $pieza[2];

                // Solo las caras de fuera llevan color; las de dentro se quedan oscuras
                $colorArriba = '';
                $colorAbajo = '';
                $colorFrente = '';
                $colorAtras = '';
                $colorDerecha = '';
                $colorIzquierda = '';
                if ($y == -1) {
                    $colorArriba = 'blanco';
                }
                if ($y == 1) {
                    $colorAbajo = 'amarillo';
                }
                if ($z == 1) {
                    $colorFrente = 'verde';
                }
                if ($z == -1) {
                    $colorAtras = 'azul';
                }
                if ($x == 1) {
                    $colorDerecha = 'rojo';
                }
                if ($x == -1) {
                    $colorIzquierda = 'naranja';
                }
            @endphp
            <div class="cubo-pieza" style="--x: {{ $x * $mitad }}px; --y: {{ $y * $mitad }}px; --z: {{ $z * $mitad }}px; animation-name: caer-{{ $numero }};">
                <div class="cara arriba {{ $colorArriba }}"></div>
                <div class="cara abajo {{ $colorAbajo }}"></div>
                <div class="cara frente {{ $colorFrente }}"></div>
                <div class="cara atras {{ $colorAtras }}"></div>
                <div class="cara derecha {{ $colorDerecha }}"></div>
                <div class="cara izquierda {{ $colorIzquierda }}"></div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .cubo-escena {
        width: 150px;
        height: 150px;
        overflow: hidden;
        perspective: 700px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* El cubo entero: preserve-3d hace que las piezas de dentro también sean 3D */
    .cubo {
        position: relative;
        width: 0;
        height: 0;
        transform-style: preserve-3d;
        transform: rotateX(-26deg) rotateY(35deg);
        animation: girar-cubo 9s infinite;
    }

    .cubo-pieza {
        position: absolute;
        transform-style: preserve-3d;
        transform: translate3d(var(--x), var(--y), var(--z));
        animation-duration: 9s;
        animation-iteration-count: infinite;
    }

    .cara {
        position: absolute;
        width: {{ $tam }}px;
        height: {{ $tam }}px;
        left: -{{ $mitad }}px;
        top: -{{ $mitad }}px;
        background: #1c1917;
        border: 2px solid #1c1917;
        border-radius: 5px;
    }

    /* Cada cara se gira hacia su lado y se separa del centro de la pieza */
    .arriba { transform: rotateX(90deg) translateZ({{ $mitad }}px); }
    .abajo { transform: rotateX(-90deg) translateZ({{ $mitad }}px); }
    .frente { transform: translateZ({{ $mitad }}px); }
    .atras { transform: rotateY(180deg) translateZ({{ $mitad }}px); }
    .derecha { transform: rotateY(90deg) translateZ({{ $mitad }}px); }
    .izquierda { transform: rotateY(-90deg) translateZ({{ $mitad }}px); }

    .blanco { background: #f5f5f4; }
    .amarillo { background: #facc15; }
    .verde { background: #0f766e; }
    .azul { background: #1d4ed8; }
    .rojo { background: #dc2626; }
    .naranja { background: #ea580c; }

    /* El cubo gira 90 grados cada dos piezas, para dejar a la vista el hueco de la siguiente.
       Al final da otro cuarto de vuelta y se agranda un poco: ya está completo. */
    @keyframes girar-cubo {
        0%, 16% { transform: rotateX(-26deg) rotateY(35deg); }
        21%, 32% { transform: rotateX(-26deg) rotateY(125deg); }
        37%, 48% { transform: rotateX(-26deg) rotateY(215deg); }
        53%, 66% { transform: rotateX(-26deg) rotateY(305deg); }
        72% { transform: rotateX(-26deg) rotateY(343deg) scale(1.08); }
        80%, 100% { transform: rotateX(-26deg) rotateY(395deg) scale(1); }
    }

    /* Una animación de caída para cada pieza: empieza escondida arriba, cae hasta su sitio
       y se queda quieta. Al final, como en el Tetris cuando se completa, todas caen hacia abajo
       y desaparecen para volver a empezar.
       Uso visibility y no opacity porque opacity rompe el 3D de las piezas (las aplana). */
    @foreach ($inicio as $numero => $momento)
        @keyframes caer-{{ $numero }} {
            0%, {{ $momento }}% {
                visibility: hidden;
                transform: translate3d(var(--x), calc(var(--y) - 170px), var(--z));
                animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6);
            }
            {{ $momento + 1 }}% {
                visibility: visible;
            }
            {{ $momento + $duracionCaida }}%, 88% {
                visibility: visible;
                transform: translate3d(var(--x), var(--y), var(--z));
                animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6);
            }
            96% {
                visibility: visible;
                transform: translate3d(var(--x), calc(var(--y) + 170px), var(--z));
            }
            97%, 100% {
                visibility: hidden;
                transform: translate3d(var(--x), calc(var(--y) + 170px), var(--z));
            }
        }
    @endforeach

    /* Si el usuario tiene activado "reducir movimiento" en su sistema, enseño el cubo ya montado y quieto */
    @media (prefers-reduced-motion: reduce) {
        .cubo, .cubo-pieza {
            animation: none;
        }
    }
</style>
