<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="modalEdicionEntidad">Entidad</span></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php
    $fields = array();
    foreach ($entidades as $field => $value) {
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
    $fields['estado']['sel'] = array(ESTADO_ACTIVO => 'Activo', ESTADO_INACTIVO => 'Inactivo');
    $fields['estado']['tipo'] = 'select';



    ?>
    <?= form_open(base_url() . "entidades/entidad/entidad_guardar", array("id" => "form_modal_edicion")); ?>
    <div class="form-horizontal col-12 row">
        <?php
        foreach ($fields as $k => $field) {
            echo item_formulario_vertical($field);
        }
        ?>
    </div>
    <?= form_close(); ?>
    <?= $departamentos ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-salir-modal">Salir</button>
    <button type="button" class="btn btn-primary btn-guardar-modal">Guardar Cambios</button>


    <script>
        function recargarDepartamento() {
            loadEntidad(<?= isset($entidades->id) ? $entidades->id : 0 ?>);
        }
        $(document).on('click', '#modalEdicion .btn-salir-modal', function() {
            $('#modalEdicion').modal("hide");
            console.log("salir entidad edicion");
        });
       

        $(document).on('click', '.btn-guardar-modal', function(e) {
            e.preventDefault();
            $("#modalEdicion #form_modal_edicion").submit();
        });
       
        $(document).on('submit', '#modalEdicion #form_modal_edicion', function(e) {

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
                        $("#modalEdicion").modal("hide");
                        recargarPrincipal();                    }
                }
            });
        });
    </script>

</div>

<!-- Modal -->
<div class="modal" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>