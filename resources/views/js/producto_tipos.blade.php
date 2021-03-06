<script>
    $(document).ready(function () {

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('registrar').disabled = false;
            $('#recurso tr').removeClass("selected");
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/tiposproductos/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'nombre', title: 'Nombre', className: "text-center"},
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('registrar').disabled = true;
            let data = table.row($(this)).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('nombre').value = data['nombre'];
        });


        $("#registrar").click(function () {
            $.post('api/tiposproductos/crear', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        });


        $("#limpiar").click(function () {
            limpiarFormulario();
        });

        $("#modificar").click(function () {
            $.post('api/tiposproductos/modificar', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        });

        $("#eliminar").click(function () {
            swal({
                title: "¿Estas seguro?",
                text: "¡Una vez borrado no será posible recuperarlo!",
                icon: "warning",
                dangerMode: true,
                buttons: ["Cancelar", "Borrar"]
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post('api/tiposproductos/borrar', $('#form').serialize(), function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload();
                        }).fail(function (err) {
                            swal("Ha ocurrido un error", "No se puede borrar el recurso, es posible que otra entidad del negocio esté haciendo referencia a este.", "error");
                            console.error(err);
                        })
                    }
                });
        });
    });
</script>
