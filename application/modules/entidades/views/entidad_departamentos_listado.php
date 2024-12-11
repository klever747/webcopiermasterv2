<div class="card-body text-center">
    <div class="card-header">
        <h3 class="card-title">Departamentos</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-accion_detalle_departamento btn-tool" value="agregar" data-entidad_id="<?= $entidades->id ?>"><i class="fas fa-plus-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <td class="text-left">Departamento</td>
                    <td class="text-left">Estado</td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($departamentos) {
                    foreach ($departamentos as $k => $departamento) {
                ?>
                        <tr>
                            <th scope="row"><?= $k + 1 ?></th>
                            <td class="text-left"><?= $departamento->nombre ?></td>
                            <!--   <td><?= ($departamento->estado === ESTADO_ACTIVO ? 'Activo' : 'Inactivo') ?></td> -->
                            <td class="text-left">
                                <?= mostrarEstilos($departamento->estado); ?>
                            </td>

                            <td>
                                <?php
                                if ($departamento->estado == 'A') {
                                ?>
                                    <button type="submit" class="btn btn-accion_detalle_departamento btn-tool" data-id="<?= $departamento->id ?>" value="eliminar"><i class="far fa-trash-alt"></i></button>
                                <?php
                                }
                                ?>
                                <button type="submit" class="btn btn-accion_detalle_departamento btn-tool" data-id="<?= $departamento->id ?>" value="editar"><i class="fas fa-pencil-alt"></i></button>
                            </td>

                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    

    $(document).on("click", "#modalEdicion .btn-accion_detalle_departamento", function() {
        if ($(this).val() === "eliminar") {
            swal_modal('¿Est&aacute; seguro de eliminar el registro?',
                'Si',
                'No',
                '<?= base_url() ?>departamento/departamento/departamento_eliminar', {
                    "id": $(this).data('id')
                }, recargarDepartamento);

        } else if ($(this).val() === "editar") {
            llamadaAjax(true, '<?= base_url() ?>departamento/departamento/departamento_obtener', {
                "id": $(this).data('id'),
            }, mostrarEdicionDetalle);
        } else if ($(this).val() === "agregar") {
            llamadaAjax(false, '<?= base_url() ?>departamento/departamento/departamento_nuevo', {
                "entidad_id": $(this).data('entidad_id'),
            }, mostrarEdicionDetalle);
        }
    });



    function mostrarEdicionDetalle(r) {
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicionDetalle .modal-content").html(r.respuesta);
            $("#modalEdicionDetalle").modal("show");
        }
    }


    function mostrarEliminacionDetalle(r) {
        mostrarExito("mostrarEliminacionDetalle");
        console.log(r);
        if (r.error) {
            mostrarError("Hubo un problema durante la eliminaci&oacute;n");
        } else {
            mostrarExito("Registro Eliminado");
            console.log("producto_variante_listado 99");
            $("#modalEdicionDetalle").modal("hide");
        }
    }
</script>