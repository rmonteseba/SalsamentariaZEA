<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            size: 279.4mm 216mm
        }

        table {
            border: #b2b2b2 1px solid;
        }

        td {
            border: black 1px solid;
        }

        th {
            border: black 1px solid;
        }
    </style>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{asset('vendor/invoices/style.css')}}" media="all"/>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{asset('favicon.png')}}">
    </div>
    <h1>Reporte de ventas</h1>
    <div style="float: left">
        @if($fechaInicio != null)
            <div><strong class="bold">Desde: </strong>{{$fechaInicio}}</div>
        @else
            <div><strong class="bold">Desde: </strong>el inicio de los tiempos</div>
        @endif
        @if($fechaFin != null)
            <div><strong class="bold">Hasta: </strong>{{ $fechaFin }}</div>
        @else
            <div><strong class="bold">Hasta: </strong>el día de hoy</div>
        @endif
    </div>
</header>
<h3>Detalle del reporte:</h3>
<table>
    <thead>
    <tr>
        <th>Id</th>
        <th class="service">Cliente</th>
        <th class="service">Empleado que vendió</th>
        <th>Valor</th>
        <th>Abonado</th>
        <th>Saldo</th>
        <th>Costo</th>
        <th>Utilidad</th>
        <th>Fecha de pago</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td class="id">{{ $registro->id }}</td>
            <td class="service">{{ $registro->cliente->name }}</td>
            <td class="service">{{ $registro->empleado->name }}</td>
            <td class="unit">{{ "$ ". number_format($registro->valor,0) }}</td>
            <td class="qty">{{ "$ ". number_format($registro->abonado,0) }}</td>
            <td class="qty">{{ "$ ". number_format($registro->saldo,0) }}</td>
            <td class="qty">{{ "$ ". number_format($registro->costo,0) }}</td>
            <td class="qty">{{ "$ ". number_format($registro->utilidad,0) }}</td>
            <td class="total">{{ $registro->fechapagado == null ? "Sin pagar" : $registro->fechapagado}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td class="grand total" colspan="3">Totales</td>
        <td class="grand total">Total vendido: {{$totalVendido}}</td>
        <td class="grand total">Total abonado: {{$totalAbonado}}</td>
        <td class="grand total">Total saldo: {{$totalSaldo}}</td>
        <td class="grand total">Total costo: {{$totalCosto}}</td>
        <td class="grand total">Total utilidades: {{$totalUtilidades}}</td>
        <td class="grand total">Utilidades devengadas: {{$utilidadesDevengadas}}</td>
    </tr>
    </tbody>
</table>
<br>
<div id="notices">
    <div align="center" class="notice">Salsamentaria ZEA</div>
    <div align="center" class="notice">
        Fecha y hora de impresión: {{ $fechaActual }}
    </div>
</div>

</body>
</html>
