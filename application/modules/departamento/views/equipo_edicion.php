<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="modalEdicionEquipos">Equipo</span></h5>
    <button type="button" class="close" onclick="$('#modalEdicionEquipos').modal('hide');" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php
    $fields = array();
    foreach ($equipos as $field => $value) {
        if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
            $fields[$field] = array(
                "id" => $field,
                "name" => $field,
                "value" => $value,
                "tipo" => 'input',
            );
        }
    }
    if (key_exists('id', $fields)) {
        $fields['id']['tipo'] = 'hidden';
    }
    if (key_exists('departamento_id', $fields)) {
        $fields['departamento_id']['tipo'] = 'hidden';
    }
    if (key_exists('entidad_id', $fields)) {
        $fields['entidad_id']['tipo'] = 'hidden';
    }
    $fields['estado']['sel'] = array(ESTADO_ACTIVO => 'Activo', ESTADO_INACTIVO => 'Inactivo');
    $fields['estado']['tipo'] = 'select';



    ?>
    <?= form_open(base_url() . "departamento/departamento/equipo_guardar", array("id" => "form_modal_edicion_equipo")); ?>
    <div class="form-horizontal col-12 row">
        <?php
        foreach ($fields as $k => $field) {
            echo item_formulario_vertical($field);
        }
        ?>
    </div>
    <?= form_close(); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-salir-modal">Salir</button>
    <button type="button" class="btn btn-primary btn-guardar-modal-equipo">Guardar Cambios</button>


    <script>
        function recargarEquipos() {
            loadEquipos(<?= isset($equipos->departamento_id) ? $equipos->departamento_id : 0 ?>);
        }

        $(document).on('click', '#modalEdicionEquipos .btn-salir-modal', function() {
            $('#modalEdicionEquipos').modal("hide");
        });

        $(document).on('click', '#modalEdicionEquipos .btn-guardar-modal-equipo', function(e) {
            e.preventDefault();
            $("#form_modal_edicion_equipo").submit();
        });

        $(document).on('submit', ' #form_modal_edicion_equipo', function(e) {

            console.log("En el submit");
            e.preventDefault();
            e.stopImmediatePropagation();
            if (!unsoloclick()) {
                console.log("No hacemos el submit");
                return false;
            }

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                data: form.serialize(), // serializes the form's elements.

                success: function(data) {

                    if (data.error) {
                        mostrarError(data.respuesta);
                    } else {
                        mostrarExito(data.respuesta);
                        $("#modalEdicionEquipos").modal("hide"); 
                        $("#modalEdicionDetalle").modal("hide");
                        //recargarEquipos();
                    }
                }
            });
        });
    </script>

</div>

<!-- Modal -->
<div class="modal" id="modalEdicionEquipos" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>