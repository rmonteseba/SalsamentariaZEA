@extends('layouts.app')

@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Reporte de facturas por cobrar</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Facturas por cobrar</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include("js.reportes.reporteFacturasPorCobrar")
@endsection

