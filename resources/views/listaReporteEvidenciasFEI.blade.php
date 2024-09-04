<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Evidencias</title>
</head>

<body>
    @php
        $contador = 0
    @endphp

    @foreach ($eventos as $evento)
    @php
        $contador++
    @endphp


    <header>
        <table>
            <tr>
                <td>
                    <img src="./logo_uv.png" width="100" height="80" alt="">
                </td>
                <td>
                    <h2>Registro de Eventos y Actividades</h2>
                    <p>Area Económico-Administrativa</p>
                    <p>Facultad de Estadística e Informática</p>
                    <p>Región Xalapa</p>
                </td>
                <td>
                    <img src="./logo_fei.png" width="80" height="80" alt="">
                </td>
            </tr>
        </table>
    </header>
    <main>
        <div class="info">
            <p>Nombre del evento: {{$evento['nombre']}} </p>
            <p>Fecha de realización: {{\Carbon\Carbon::parse($evento['inicio'])->format('d/m/Y')}}</p>
            <p>Fecha de finalización: {{\Carbon\Carbon::parse($evento['fin'])->format('d/m/Y')}}</p>
            <p>Realizador: {{$evento['usuario']['nombres']}} {{$evento['usuario']['apellidoPaterno']}} {{$evento['usuario']['apellidoMaterno']}}</p>
        </div>
        <div class="evidencias">
            <p>Evidencias</p>
            <div class="evidencia">

                @foreach ($evento['evidencias'] as $evidencia)
                @php
                $src = 'data:'.$evidencia['tipo'].';base64,' .$evidencia['archivo'];
                $nombre = $evidencia['nombre'];
                @endphp
                <img class="imagen" src="{{$src}}" alt="{{$nombre}}">
                @endforeach
            </div>
        </div>
    </main>
    @php
    $longitud = count($eventos);


    if ($contador < $longitud){
        echo '<div class="page-break"></div>' ;
        }

        @endphp


        @endforeach
        </body>

</html>
<style>
    * {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    table {
        border-collapse: collapse;
        margin-bottom: 10px;
        width: 100%;
    }

    header {
        text-align: center;
        font-size: small;
    }

    p {
        margin: 0;
    }

    .info {
        width: 100%;
    }

    .evidencias {
        width: 100%;
        text-align: center;
    }

    .evidencia {
        width: 100%;
    }

    .imagen {
        object-fit: cover;
        width: 600px;
        height: 300px;
        margin-bottom: 10px;
    }

    .page-break {
        page-break-after: always;
    }
</style>