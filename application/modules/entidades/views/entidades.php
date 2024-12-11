<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Entidad</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Entidad</a></li>
                            <li class="breadcrumb-item active">Entidades</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Entidades</h3>
                    </div>

                    <?= filtroBusqueda("entidades/entidad/entidades", array("texto_busqueda" => $texto_busqueda, "estado_id" => $estado_id, "regpp" => $regpp)); ?>

                    <div class="card-body text-center">
                        <?= isset($itemsPaginacion) ? ($itemsPaginacion) : ''; ?>
                    </div>
                    <div class="card-body centrado">
                        <div class="row">
                            <?php
                            if ($entidades) {
                            ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripci&oacute;n</th>
                                            <th scope="col">Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($entidades as $k => $entidad) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $k + 1 ?></th>
                                                <td class="text-left"><?= $entidad->nombre ?></td>
                                                <td class="text-left"><?= $entidad->descripcion ?></td>
                                                
                                                <!--   <td><?= ($entidad->estado === ESTADO_ACTIVO ? 'Activo' : 'Inactivo') ?></td> -->
                                                <td class="text-left">
                                                    <?= mostrarEstilos($entidad->estado); ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($entidad->estado == 'A') {
                                                    ?>
                                                        <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $entidad->id ?>" value="eliminar"><i class="far fa-trash-alt"></i></button>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $entidad->id ?>" value="editar"><i class="fas fa-pencil-alt"></i></button>
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
<div class="modal" id="modalEdicionDetalle" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<div class="modal" id="modalEdicionEquipos" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<script>
    var entidad_actual = 0;
    var departamento_id =0;                            

    function recargarPrincipal() {
        console.log("recargarPrincipal, productos");
        $("#btn_buscar").trigger("click");
    }
    function loadDepartamentos() {
        llamadaAjax(true, '<?= base_url() ?>entidades/entidad/entidad_obtener', {
            "id": entidad_actual
        }, mostrarEdicionOrdenItem);
    }
    function loadEquipos() {
        
        llamadaAjax(true, '<?= base_url() ?>departamento/equipo/equipo_obtener', {
            "id": departamento_id
        }, mostrarEdicionEquipos2);
    }
    function mostrarEdicion(r) {
        console.log("mostrarEdicion en productos.php linea 132");
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicion .modal-content").html(r.respuesta);
            $("#modalEdicion").modal("show");
        }
    }

    function mostrarEliminacion(r) {
        if (r.error) {
            mostrarError(r.respuesta);
        } else {
            mostrarExito(r.respuesta);
            recargarPrincipal();
        }
    }

    function loadEntidad() {
        llamadaAjax(false, '<?= base_url() ?>entidades/entidad/entidad_obtener', {
            "id": entidad_actual
        }, mostrarEdicionOrdenItem);
    }

    $(document).ready(function() {

        /*************** ACCIONES PANTALLA PRINCIPAL *********************/
        $(".btn-accion").on('click', function() {

            unsoloclick('.btn-accion');
            if ($(this).val() === "eliminar") {
                /**/
                console.log('Boton de eliminar');
                swal_modal('¿Est&aacute; seguro de eliminar el registro?',
                    'Si',
                    'No',
                    '<?= base_url() ?>entidades/entidad/entidad_eliminar', {
                        "id": $(this).data('id')
                    },
                    mostrarEliminacion);
                /**/
                // llamadaAjax(false, '<?= base_url() ?>ecommerce/producto/producto_eliminar', {"id": $(this).data('id')}, mostrarEliminacion);
            } else if ($(this).val() === "editar") {
                entidad_actual = $(this).data('id');
                loadEntidad();
            } else if ($(this).val() === "agregar") {
                llamadaAjax(true, '<?= base_url() ?>entidades/entidad/entidad_nuevo', false, mostrarEdicionOrdenItem);
            } 
        });


    });

    function mostrarEdicionOrdenItem(r) {

        if (analizarRespuesta(r)) {
            $("#modalEdicion .modal-content").html(r.respuesta);
            $("#modalEdicion").modal("show");
        }
    }
    function mostrarEdicionEquipos2(r) {
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicionDetalle .modal-content").html(r.respuesta);
            $("#modalEdicionDetalle").modal("show");
        }
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
</script>