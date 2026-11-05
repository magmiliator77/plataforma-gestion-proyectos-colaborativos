{{-- Logo animado: el logo de Laravel montado con bloques, como en el Tetris.
     - Los bloques caen uno a uno y la figura gira para que el siguiente encaje.
     - Cuando dos bloques de la misma pieza del logo se juntan, se unen en una sola caja
       (desaparece la línea que los separaba).
     - Al final cae un bloque gris de apoyo, el cubo pequeño cae encima, el apoyo se rompe
       y el cubo se queda flotando, como en el logo de Laravel.
     Todo es HTML y CSS: las piezas las creo con @foreach y las animaciones con @keyframes. --}}
@php
    // Tamaño de un bloque en píxeles
    $tam = 25;

    // Duración de toda la animación en segundos
    $duracion = 13;

    // Ángulo final en el que la figura se ve como el logo de Laravel
    $giroFinal = -45;

    // Centro de la figura (en bloques), para que quede centrada en el recuadro
    $centroX = 1.5;
    $centroY = 1.5;
    $centroZ = -1;

    // Lista de piezas. Cada pieza es una caja con:
    //   x, y, z            -> posición de su centro (en bloques; y = altura)
    //   ancho, alto, fondo -> tamaño (en bloques)
    //   tipo               -> 'cae' (cae desde arriba), 'aparece' (aparece de golpe al unirse dos bloques),
    //                         'apoyo' (cae y luego se rompe) o 'flota' (cae y se queda flotando)
    //   inicio             -> % de la animación en el que empieza
    //   fin                -> % en el que desaparece porque se ha unido a otra caja (100 = se queda hasta el final)
    // Proporciones del logo: columna de 4 de alto, pie de 3 de largo, brazo de 2 y el cubo flotando.
    $piezas = [
        // Bloques que caen, en orden
        ['x' => 0, 'y' => 0, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 2, 'fin' => 42],      // columna 1
        ['x' => 1, 'y' => 0, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 8, 'fin' => 18],      // pie 1
        ['x' => 2, 'y' => 0, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 14, 'fin' => 18],     // pie 2
        ['x' => 3, 'y' => 0, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 20, 'fin' => 24],     // pie 3
        ['x' => 3, 'y' => 0, 'z' => -1, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 26, 'fin' => 36],    // brazo 1
        ['x' => 3, 'y' => 0, 'z' => -2, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 32, 'fin' => 36],    // brazo 2
        ['x' => 0, 'y' => 1, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 38, 'fin' => 42],     // columna 2
        ['x' => 0, 'y' => 2, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 44, 'fin' => 48],     // columna 3
        ['x' => 0, 'y' => 3, 'z' => 0, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'cae', 'inicio' => 50, 'fin' => 54],     // columna 4
        ['x' => 3, 'y' => 1, 'z' => -2, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'apoyo', 'inicio' => 56, 'fin' => 100], // apoyo gris que se rompe
        ['x' => 3, 'y' => 2, 'z' => -2, 'ancho' => 1, 'alto' => 1, 'fondo' => 1, 'tipo' => 'flota', 'inicio' => 62, 'fin' => 100],  // cubo pequeño

        // Cajas unidas: aparecen justo cuando cae el bloque que las completa
        ['x' => 1.5, 'y' => 0, 'z' => 0, 'ancho' => 2, 'alto' => 1, 'fondo' => 1, 'tipo' => 'aparece', 'inicio' => 18, 'fin' => 24],    // pie de 2
        ['x' => 2, 'y' => 0, 'z' => 0, 'ancho' => 3, 'alto' => 1, 'fondo' => 1, 'tipo' => 'aparece', 'inicio' => 24, 'fin' => 100],     // pie entero
        ['x' => 3, 'y' => 0, 'z' => -1.5, 'ancho' => 1, 'alto' => 1, 'fondo' => 2, 'tipo' => 'aparece', 'inicio' => 36, 'fin' => 100],  // brazo entero
        ['x' => 0, 'y' => 0.5, 'z' => 0, 'ancho' => 1, 'alto' => 2, 'fondo' => 1, 'tipo' => 'aparece', 'inicio' => 42, 'fin' => 48],    // columna de 2
        ['x' => 0, 'y' => 1, 'z' => 0, 'ancho' => 1, 'alto' => 3, 'fondo' => 1, 'tipo' => 'aparece', 'inicio' => 48, 'fin' => 54],      // columna de 3
        ['x' => 0, 'y' => 1.5, 'z' => 0, 'ancho' => 1, 'alto' => 4, 'fondo' => 1, 'tipo' => 'aparece', 'inicio' => 54, 'fin' => 100],   // columna entera
    ];

    // Altura a la que se queda flotando el cubo pequeño cuando se rompe el apoyo
    $alturaFlotando = 1.7;

    // Momento en el que se rompe el apoyo (justo después de que aterrice el cubo)
    $rompe = 67;

    // Cada bloque tarda esto en caer (en % de la animación)
    $caida = 4;
@endphp

<div class="logo-escena" role="img" aria-label="Logo de Laravel montándose con bloques">
    <div class="logo-figura">
        @foreach ($piezas as $numero => $pieza)
            @php
                // Tamaño de la caja en píxeles
                $ancho = $pieza['ancho'] * $tam;
                $alto = $pieza['alto'] * $tam;
                $fondo = $pieza['fondo'] * $tam;

                // Posición final en píxeles, con la figura centrada.
                // En CSS la y crece hacia abajo, por eso le cambio el signo.
                $y = $pieza['y'];
                if ($pieza['tipo'] == 'flota') {
                    $y = $alturaFlotando;
                }
                $px = ($pieza['x'] - $centroX) * $tam;
                $py = ($centroY - $y) * $tam;
                $pz = ($pieza['z'] - $centroZ) * $tam;

                // Las 6 caras de la caja: tamaño y cómo se colocan
                $caras = [
                    ['ancho' => $ancho, 'alto' => $alto, 'giro' => 'translateZ(' . ($fondo / 2) . 'px)'],
                    ['ancho' => $ancho, 'alto' => $alto, 'giro' => 'rotateY(180deg) translateZ(' . ($fondo / 2) . 'px)'],
                    ['ancho' => $fondo, 'alto' => $alto, 'giro' => 'rotateY(90deg) translateZ(' . ($ancho / 2) . 'px)'],
                    ['ancho' => $fondo, 'alto' => $alto, 'giro' => 'rotateY(-90deg) translateZ(' . ($ancho / 2) . 'px)'],
                    ['ancho' => $ancho, 'alto' => $fondo, 'giro' => 'rotateX(90deg) translateZ(' . ($alto / 2) . 'px)'],
                    ['ancho' => $ancho, 'alto' => $fondo, 'giro' => 'rotateX(-90deg) translateZ(' . ($alto / 2) . 'px)'],
                ];

                // Las piezas que se quedan hasta el final son las que forman el logo
                $clases = 'logo-pieza';
                if ($pieza['tipo'] == 'apoyo') {
                    $clases = $clases . ' apoyo';
                }
                if ($pieza['fin'] == 100 && $pieza['tipo'] != 'apoyo') {
                    $clases = $clases . ' final';
                }
            @endphp
            <div class="{{ $clases }}" style="transform: translate3d({{ $px }}px, {{ $py }}px, {{ $pz }}px); animation-name: pieza-{{ $numero }};">
                @foreach ($caras as $numeroCara => $cara)
                    <div class="cara" style="width: {{ $cara['ancho'] }}px; height: {{ $cara['alto'] }}px; left: -{{ $cara['ancho'] / 2 }}px; top: -{{ $cara['alto'] / 2 }}px; transform: {{ $cara['giro'] }};"></div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<style>
    .logo-escena {
        width: 200px;
        height: 200px;
        perspective: 800px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* La figura entera: preserve-3d hace que las piezas de dentro también sean 3D */
    .logo-figura {
        position: relative;
        width: 0;
        height: 0;
        transform-style: preserve-3d;
        transform: rotateX(-35deg) rotateY({{ $giroFinal }}deg);
        animation: girar-figura {{ $duracion }}s infinite;
    }

    .logo-pieza {
        position: absolute;
        transform-style: preserve-3d;
        visibility: hidden;
        animation-duration: {{ $duracion }}s;
        animation-iteration-count: infinite;
    }

    /* Caras blancas con el borde rojo de Laravel, como el dibujo del logo */
    .cara {
        position: absolute;
        background: #ffffff;
        border: 3px solid #ff2d20;
    }

    /* El apoyo es gris para que se vea que no forma parte del logo */
    .apoyo .cara {
        background: #e7e5e4;
        border-color: #a8a29e;
    }

    /* La figura gira 90 grados cada dos bloques, para que se vea dónde encaja el siguiente.
       Empieza tres cuartos de vuelta antes y termina justo en el ángulo del logo. */
    @keyframes girar-figura {
        0%, 12% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 270 }}deg); }
        16%, 24% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 180 }}deg); }
        28%, 36% { transform: rotateX(-35deg) rotateY({{ $giroFinal - 90 }}deg); }
        40%, 100% { transform: rotateX(-35deg) rotateY({{ $giroFinal }}deg); }
    }

    /* Una animación para cada pieza, según su tipo.
       Uso visibility y no opacity porque opacity rompe el 3D de las piezas (las aplana). */
    @foreach ($piezas as $numero => $pieza)
        @php
            $y = $pieza['y'];
            if ($pieza['tipo'] == 'flota') {
                $y = $alturaFlotando;
            }
            $px = ($pieza['x'] - $centroX) * $tam;
            $py = ($centroY - $y) * $tam;
            $pz = ($pieza['z'] - $centroZ) * $tam;

            // Posición donde aterriza (el cubo que flota aterriza primero encima del apoyo)
            $pyAterriza = $py;
            if ($pieza['tipo'] == 'flota') {
                $pyAterriza = ($centroY - $pieza['y']) * $tam;
            }

            $sitio = "translate3d({$px}px, {$py}px, {$pz}px)";
            $sitioAterriza = "translate3d({$px}px, {$pyAterriza}px, {$pz}px)";
            $arriba = "translate3d({$px}px, " . ($pyAterriza - 180) . "px, {$pz}px)";
            $abajo = "translate3d({$px}px, " . ($py + 180) . "px, {$pz}px)";
            $aterriza = $pieza['inicio'] + $caida;
        @endphp
        @keyframes pieza-{{ $numero }} {
            @if ($pieza['tipo'] == 'aparece')
                {{-- Aparece de golpe en su sitio cuando se juntan los bloques --}}
                0%, {{ $pieza['inicio'] - 0.1 }}% { visibility: hidden; transform: {{ $sitio }}; }
                {{ $pieza['inicio'] }}% { visibility: visible; transform: {{ $sitio }}; }
            @else
                {{-- Cae desde arriba hasta su sitio, acelerando como si tuviera gravedad --}}
                0%, {{ $pieza['inicio'] }}% {
                    visibility: hidden;
                    transform: {{ $arriba }};
                    animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6);
                }
                {{ $pieza['inicio'] + 0.5 }}% { visibility: visible; }
                {{ $aterriza }}% { visibility: visible; transform: {{ $sitioAterriza }}; }
            @endif

            @if ($pieza['tipo'] == 'apoyo')
                {{-- El apoyo aguanta un momento con el cubo encima y se rompe --}}
                {{ $rompe }}% { visibility: visible; transform: {{ $sitio }} scale(1); }
                {{ $rompe + 2 }}% { visibility: visible; transform: {{ $sitio }} scale(1.2); }
                {{ $rompe + 4 }}%, 100% { visibility: hidden; transform: {{ $sitio }} scale(0); }
            @elseif ($pieza['tipo'] == 'flota')
                {{-- Al romperse el apoyo, el cubo baja un poco y se queda flotando --}}
                {{ $rompe + 3 }}% { visibility: visible; transform: {{ $sitioAterriza }}; animation-timing-function: ease-out; }
                {{ $rompe + 9 }}%, 88% { visibility: visible; transform: {{ $sitio }}; animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6); }
                95% { visibility: visible; transform: {{ $abajo }}; }
                96%, 100% { visibility: hidden; transform: {{ $abajo }}; }
            @elseif ($pieza['fin'] < 100)
                {{-- Se une a otra caja: desaparece justo cuando aparece la caja unida --}}
                {{ $pieza['fin'] }}% { visibility: visible; transform: {{ $sitio }}; }
                {{ $pieza['fin'] + 0.1 }}%, 100% { visibility: hidden; transform: {{ $sitio }}; }
            @else
                {{-- Se queda hasta el final y luego cae hacia abajo, como en el Tetris al completar --}}
                88% { visibility: visible; transform: {{ $sitio }}; animation-timing-function: cubic-bezier(0.5, 0, 1, 0.6); }
                95% { visibility: visible; transform: {{ $abajo }}; }
                96%, 100% { visibility: hidden; transform: {{ $abajo }}; }
            @endif
        }
    @endforeach

    /* Si el usuario tiene activado "reducir movimiento" en su sistema, enseño el logo ya montado y quieto */
    @media (prefers-reduced-motion: reduce) {
        .logo-figura, .logo-pieza {
            animation: none;
        }
        .final {
            visibility: visible;
        }
    }
</style>
