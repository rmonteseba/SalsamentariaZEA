@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Empleados</h1>
    </div>
    <br>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del empleado</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Id:</label>
                        <div class="col-md-8">
                            <input readonly="readonly" id="id" class="form-control"
                                   name="id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Nombre:</label>

                        <div class="col-md-8">
                            <input id="name" class="form-control"
                                   name="name" required autocomplete="nameCliente">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Documento de identidad:</label>

                        <div class="col-md-8">
                            <input id="di" type="number"
                                   class="form-control"
                                   name="di">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Teléfono celular:</label>
                        <div class="col-md-8">
                            <input id="celular" type="number"
                                   class="form-control"
                                   name="celular">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Teléfono fijo:</label>
                        <div class="col-md-8">
                            <input id="fijo" type="number"
                                   class="form-control"
                                   name="fijo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Rol:</label>

                        <div class="col-md-8">
                            <select id="rol_id" name="rol_id"
                                    class="form-control"
                                    name="rol_id" required>
                                <option disabled selected value>Seleccione una opción</option>
                                @foreach($rols as $rol)
                                    <option value="{!! $rol->{'Id de rol'} !!}">{{$rol->Rol}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Correo electrónico:</label>
                        <div class="col-md-8">
                            <input id="email" type="email"
                                   class="form-control"
                                   name="email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Contraseña:</label>
                        <div class="col-md-8">
                            <input id="password" type="password"
                                   class="form-control"
                                   name="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Dirección:</label>
                        <div class="col-md-8">
                            <input id="direccion"
                                   class="form-control"
                                   name="direccion">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Salario:</label>
                        <div class="col-md-8">
                            <input id="salario" type="number"
                                   class="form-control"
                                   name="salario">
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="registrar" type="button" value="Registrar"
                               class="btn btn-primary container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="modificar" type="button" value="Modificar"
                               class="btn btn-warning container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="eliminar" type="button" value="Eliminar" class="btn btn-danger container-fluid"/>
                    </div>

                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Empleados registrados</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/libs/js/controllers/empleados.js') }}"></script>
@endsection

