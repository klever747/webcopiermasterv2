<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Usuario</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Seguridad</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios</h3>
                    </div>

                    <?= filtroBusqueda("seguridad/usuario/usuarios", array("texto_busqueda" => $texto_busqueda, "estado_id" => $estado_id, "regpp" => $regpp)); ?>

                    <div class="card-body text-center">
                        <?= isset($itemsPaginacion) ? ($itemsPaginacion) : ''; ?>
                    </div>
                    <div class="card-body centrado">
                        <div class="row">
                            <?php
                            if ($usuarios) {
                            ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Usuario</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($usuarios as $k => $usuario) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $k + 1 ?></th>
                                                <td class="text-left"><?= $usuario->nombre ?></td>
                                                <td class="text-left"><?= $usuario->usuario ?></td>
                                                <td class="text-left"><?= $usuario->correo ?></td>


                                                <!--   <td><?= ($usuario->estado === ESTADO_ACTIVO ? 'Activo' : 'Inactivo') ?></td> -->
                                                <td class="text-left">
                                                    <?= mostrarEstilos($usuario->estado); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($usuario->estado == 'A') {
                                                    ?>
                                                        <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $usuario->id ?>" value="eliminar"><i class="far fa-trash-alt"></i></button>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $usuario->id ?>" value="editar"><i class="fas fa-pencil-alt"></i></button>
                                                    <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $usuario->id ?>" value="password"><i class="fas fa-key"></i></button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<button type="button" class="float btn-accion" data-id="" value="agregar">
    <i class="fa fa-plus my-float"></i>
</button>

<div class="modal" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<script>
    var usuario_actual = 0;

    function recargarPrincipal() {
        console.log("recargarPrincipal, usuarios");
        $("#btn_buscar").trigger("click");
    }

    function analizarRespuesta(r) {
        if (r.error) {
            mostrarError(r.mensaje);
            return false;
        } else {
            mostrarExito(r.mensaje);
            return true;
        }
        return false;
    }

    function mostrarEdicionUsuarios(r) {

        if (analizarRespuesta(r)) {
            $("#modalEdicion .modal-content").html(r.respuesta);
            $("#modalEdicion").modal("show");
           
            if (r.departamento_id && r.entidad_id) {
                console.log("depart"+r.entidad_id);
                enlazarSelect2('entidad_id', 'departamento_id', '<?= base_url() ?>tickets/ticket/buscarSelDepartamentos', function() {
                    $('#departamento_id').val(r.departamento_id);
                    $('#departamento_id').select2().trigger('change');
                });
                llenarSelect2("entidad_id", '<?= base_url() ?>tickets/ticket/buscarSelEntidades', {
                    "id": (r.entidad_id ? r.entidad_id : 1)
                }, function() {
                    $('#entidad_id').val(r.entidad_id);
                    $('#entidad_id').select2().trigger('change');

                });
                enlazarSelect2('departamento_id', 'equipo_id', '<?= base_url() ?>tickets/ticket/buscarSelEquipos', false);

            } else {

                /**----- se uso otro select modificado para poder llenar a partir de 2 selects anteriores otro select */
                enlazarSelect2('entidad_id', 'departamento_id', '<?= base_url() ?>tickets/ticket/buscarSelDepartamentos', false);

                llenarSelect2("entidad_id", '<?= base_url() ?>tickets/ticket/buscarSelEntidades', {
                    "id": (r.entidad_id ? r.entidad_id : 1)
                }, function() {
                    $('#entidad_id').select2().trigger('change');

                });
                enlazarSelect2('departamento_id', 'equipo_id', '<?= base_url() ?>tickets/ticket/buscarSelEquipos', false);

            }

        }
    }

    function mostrarEliminacion(r) {
        if (r.error) {
            mostrarError("Hubo un problema durante la eliminación");
        } else {
            mostrarExito(r.respuesta);
            recargarPrincipal();
        }
    }

    function loadUsuario() {
        llamadaAjax(false, '<?= base_url() ?>seguridad/usuario/usuario_obtener', {
            "id": usuario_actual
        }, mostrarEdicion);
    }
    $(document).ready(function() {

        /*************** ACCIONES PANTALLA PRINCIPAL *********************/
        $(".btn-accion").on('click', function() {
            unsoloclick('.btn-accion');
            if ($(this).val() === "eliminar") {
                console.log('Boton de eliminar');
                swal_modal('¿Est&aacute; seguro de eliminar el registro?',
                    'Si',
                    'No',
                    '<?= base_url() ?>seguridad/usuario/usuario_eliminar', {
                        "id": $(this).data('id')
                    },
                    mostrarEliminacion);

            } else if ($(this).val() === "editar") {
                usuario_actual = $(this).data('id');
                loadUsuario();
            } else if ($(this).val() === "agregar") {
                llamadaAjax(false, '<?= base_url() ?>seguridad/usuario/usuario_nuevo', false, mostrarEdicionUsuarios);
            } else if ($(this).val() === "password") {
                llamadaAjax(false, '<?= base_url() ?>seguridad/usuario/editar_password', {
                    "id": $(this).data('id')
                }, mostrarEdicion);
            }
        });

        $("#modalEdicion").on('shown.bs.modal', function() {});
        $("#modalEdicion").on('hide.bs.modal', function() {
            //recargarPrincipal();
        });
        /***************** ACCIONES MODAL DETALLE *******************/

        $("#texto_busqueda").on('keypress', function(e) {
            if (e.which === 13) {
                $("#btn_buscar").trigger("click");
            }
        });

    });
</script>