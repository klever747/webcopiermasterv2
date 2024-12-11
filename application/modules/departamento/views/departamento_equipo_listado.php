<div class="card-body text-center">
    <div class="card-header">
        <h3 class="card-title">Equipos</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-accion_detalle_equipo btn-tool" value="agregarEquipo" data-departamento_id="<?= $departamentos->id ?>"><i class="fas fa-plus-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <td class="text-left">Marca</td>
                    <td class="text-left">Serie</td>
                    <td class="text-left">Modelo</td>
                    <td class="text-left">Contador</td>
                    <td class="text-left">IP Equipo</td>
                    <td class="text-left">Estado</td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($equipos) {
                    foreach ($equipos as $k => $equipo) {
                ?>
                        <tr>
                            <th scope="row"><?= $k + 1 ?></th>
                            <td class="text-left"><?= $equipo->marca ?></td>
                            <td class="text-left"><?= $equipo->serie ?></td>
                            <td class="text-left"><?= $equipo->modelo ?></td>
                            <td class="text-left"><?= $equipo->contador ?></td>
                            <td class="text-left"><?= $equipo->ip_equipo ?></td>
                            <!--   <td><?= ($equipo->estado === ESTADO_ACTIVO ? 'Activo' : 'Inactivo') ?></td> -->
                            <td class="text-left">
                                <?= mostrarEstilos($equipo->estado); ?>
                            </td>

                            <td>
                                <?php
                                if ($equipo->estado == 'A') {
                                ?>
                                    <button type="submit" class="btn btn-accion_detalle_equipo btn-tool" data-id="<?= $equipo->id ?>" value="eliminar"><i class="far fa-trash-alt"></i></button>
                                <?php
                                }
                                ?>
                                <button type="submit" class="btn btn-accion_detalle_equipo btn-tool" data-id="<?= $equipo->id ?>" data-departamento_id_="<?= $departamentos->id ?>" value="editar"><i class="fas fa-pencil-alt"></i></button>
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
    $(document).on("click", "#modalEdicionDetalle .btn-accion_detalle_equipo", function() {
        console.log("editar equipo");
        if ($(this).val() === "eliminar") {
            swal_modal('¿Est&aacute; seguro de eliminar el registro?',
                    'Si',
                    'No',
                    '<?= base_url() ?>departamento/departamento/departamento_eliminar', {
                "id": $(this).data('id')
            }, recargarDepartamento);
           
        } else if ($(this).val() === "editar") {
            
            llamadaAjax(false, '<?= base_url() ?>departamento/equipo/equipo_obtener', {
                "id": $(this).data('id'),"departamento_id":$(this).data('departamento_id_')
            }, mostrarEdicionEquipos);
        } else if ($(this).val() === "agregarEquipo") {
            llamadaAjax(false, '<?= base_url() ?>departamento/equipo/equipo_nuevo', {
                "departamento_id": $(this).data('departamento_id'),
            }, mostrarEdicionEquipos);
        }
    });



    function mostrarEdicionEquipos(r) {
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicionEquipos .modal-content").html(r.respuesta);
            $("#modalEdicionEquipos").modal("show");
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