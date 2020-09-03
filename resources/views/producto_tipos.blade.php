@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Tipos de productos</h1>
    </div>
    <br>

    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">{{session('success')}} <i class="fas fa-fw fa-check-circle"></i></div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del tipo de producto</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Id:</label>
                        <div class="col-md-8">
                            <input readonly="readonly" id="id" class="form-control @error('id') is-invalid @enderror"
                                   value="{{old('id')}}" name="id">
                            @error('id')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Nombre:</label>

                        <div class="col-md-8">
                            <input id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{old('nombre')}}" name="nombre" required autocomplete="nombreProveedor">
                            @error('nombre')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center" >

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
                <h3 class="m-0 font-weight-bold text-primary text-center">Tipos de productos registrados</h3>
            </div>
            <div class="card-body">
                @if(!$tipos->isEmpty())
                    <table id="recurso" class="table table-bordered dt-responsive nowrap table-hover"
                           style="width:100%" cellspacing="0" data-page-length='5' data-name="recursos">
                        <thead>
                        <tr>
                            @foreach ($tipos->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tipos as $registro)
                            <tr class="row-cursor-hand">
                                @foreach ($registro as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay proveedores disponibles, intentelo más tarde</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var table = $('#recurso').DataTable(options);
            $('#recurso tbody').on('click', 'tr', function () {
                document.getElementById('registrar').disabled = true;
                var data = table.row(this).data();
                document.getElementById('id').value = data[0];
                document.getElementById('nombre').value = data[1];
            });

            $("#registrar").click(function () {
                document.form.action = "{{ route('tiposproductos.crear') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('nombre').value = "";
                document.getElementById('telefono').value = "";
                document.getElementById('direccion').value = "";
                document.getElementById('registrar').disabled = false;
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('tiposproductos.actualizar') }}";
                document.form.submit();
            });

            $("#eliminar").click(function () {
                swal({
                    title: "¿Estas seguro?",
                    text: "¡Una vez borrado no será posible recuperarlo!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["Cancelar","Borrar"]
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var url = "{{ route('tiposproductos.borrar', ':id') }}";
                            document.form.action = url.replace(':id', document.getElementById('id').value);
                            document.form.submit();
                        }
                    });
            });
        });
    </script>
@endsection

