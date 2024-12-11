<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_edicion_master">Ticket</span></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php
    $fields = array();
    foreach ($ticket_usuario as $field => $value) {
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
    if (key_exists('ticket_id', $fields)) {
        $fields['ticket_id']['tipo'] = 'hidden';
        $fields['ticket_id']['value'] = $ticket_id;
    }
    switch ($vista) {
        case 'VISTA_SOLUCION':
            if ($solucion_encontrada) {
                $fields['actualizacion']['id'] = 'actualizacion';
                $fields['actualizacion']['name'] = 'actualizacion';
                $fields['actualizacion']['tipo'] = 'hidden';
                $fields['actualizacion']['value'] = 'true';
                unset($fields['usuario_id']);
                unset($fields['respuesta']);
            } else {
                $fields['actualizacion']['tipo'] = 'hidden';
                $fields['actualizacion']['value'] = 'false';
                unset($fields['respuesta']);
                unset($fields['usuario_id']);
                unset($fields['fecha_creacion']);
            }
            break;
        case 'VISTA_RESPUESTA':
            $fields['usuario_id']['tipo'] = 'select';
            $fields['usuario_id']['sel'] = $tecnicos;
            if ($respuesta_encontrada) {
                $fields['actualizacion']['id'] = 'actualizacion';
                $fields['actualizacion']['name'] = 'actualizacion';
                $fields['actualizacion']['tipo'] = 'hidden';
                $fields['actualizacion']['value'] = 'true';
                unset($fields['solucion']);
            } else {
                $fields['actualizacion']['tipo'] = 'hidden';
                $fields['actualizacion']['value'] = 'false';
                unset($fields['solucion']);
                unset($fields['fecha_creacion']);
            }
            break;
        default:
            # code...
            break;
    }




    if (key_exists('fecha_creacion', $fields)) {
        $fields['fecha_creacion']['tipo'] = 'hidden';
    }


    ?>
    <?= form_open(base_url() . "tickets/ticket/ticket_solucion_guardar", array("id" => "form_modal_edicion")); ?>
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
    <button type="button" class="btn btn-primary btn-guardar-modal">Guardar Cambios</button>


    <script>
        function recargarFinca() {
            loadFinca(<?= isset($finca->id) ? $finca->id : 0 ?>);
        }

        $(document).on('click', '#modalEdicionSolucion .btn-salir-modal', function() {
            $('#modalEdicionSolucion').modal("hide");
        });

        $(document).on('click', '.btn-guardar-modal', function(e) {
            e.preventDefault();
            $("#modalEdicionSolucion #form_modal_edicion").submit();
        });

        $(document).on('submit', '#modalEdicionSolucion #form_modal_edicion', function(e) {

            console.log("En el submit");
            e.preventDefault();
            e.stopImmediatePropagation();
            if (!unsoloclick()) {
                console.log("No hacemos el submit");
                return false;
            }

            var form = $(this);
            var url = form.attr('action');
            var nombre = $('select[name="entidad_id"] option:selected').text();

            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                data: form.serialize() + '&nombre=' + nombre, // serializes the form's elements.

                success: function(data) {

                    if (data.error) {
                        mostrarError(data.respuesta);
                    } else {
                        mostrarExito(data.respuesta);
                        $("#modalEdicionSolucion").modal("hide");

                    }
                }
            });
        });
    </script>

</div>

<!-- Modal -->
<div class="modal" id="modalEdicionDetalle" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>