<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividades</title>
</head>

<body>
    <header>
        <table>
            <tr>
                <td style="text-align: center;">
                    <img src="./logo_uv.png" width="100" height="80" alt="">
                </td>
                <td style="text-align: center; font-size: small; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                    <h2>Registro de Eventos y Actividades</h2>
                    <p>Area Económico-Administrativa</p>
                    <p>Facultad de Estadística e Informática</p>
                    <p>Región Xalapa</p>
                </td>
                <td style="text-align: center;">
                    <img src="./logo_fei.png" width="80" height="80" alt="">
                </td>
            </tr>
        </table>
    </header>
    <main>
        <table>
            <tr>
                <td class="head">Nombre de la dependencia o entidad académica que organizo el evento: </td>
            </tr>
            <tr>
                <td>Facultad de Estadística e Informática</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="head">Tipo y nombre de actividad/evento: </td>
            </tr>
            <tr>
                <td>{{$nombre}} - Evento {{$tipo['nombre']}} </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="head">Descripción breve del objetivo de la actividad/evento</td>
            </tr>
            <tr>
                <td>{{$descripcion}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="mitad" >Modalidad en la que se realizó la actividad/evento</td>
                <td class="mitad" >Fechas en las que se realizó el actividad/evento</td>
            </tr>
            <tr>
                <td>{{$modalidad['nombre']}}</td>
                <td>{{\Carbon\Carbon::parse($inicio)->format('d/m/Y')}} - {{\Carbon\Carbon::parse($fin)->format('d/m/Y')}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="mitad">Actividad dirigida a</td>
                <td class="mitad">Ámbito</td>
            </tr>
            <tr>
                <td>{{$audiencias}}</td>
                <td>{{$ambito}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Nombre(s) de las personas responsables de la actividad/evento asi como correo institucional </td>
            </tr>
            <tr>
                <td>{{$usuario['nombres']}} {{$usuario['apellidoPaterno']}} {{$usuario['apellidoMaterno']}} - {{$usuario['email']}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="mitad">Numero estimado de participantes</td>
                <td class="mitad">Pagina web donde puede obtener más informacion (opcional)</td>
            </tr>
            <tr>
                <td>{{$numParticipantes}}</td>
                <td>{{$pagina}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="mitad">Eje principal del programa al que impacta</td>
                <td class="mitad">Temáticas principales que aborda la actividad</td>
            </tr>
            <tr>
                <td>{{$eje}}</td>
                <td>{{$tematicas}}</td>
            </tr>
        </table>
    </main>
</body>

</html>

<style>
    * {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif
    }

    main td {
        border: 1px solid black;
        padding-left: 5px;

    }

    .head {
        background-color: #18529D;
        color: white;
    }

    table {
        border-collapse: collapse;
        margin-bottom: 10px;
        width: 100%;
    }

    header {
        text-align: center;

    }

    header p {
        margin: 0;

    }

    .input {
        border: 1px solid black;
    }

    .mitad {
        width: 50%;
        background-color: #18529D;
        color: white;
    }
</style>