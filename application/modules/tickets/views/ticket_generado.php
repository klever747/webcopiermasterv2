<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_edicion_detalle">Enviar Ticket</span></h5>
    <button type="button" class="close" onclick="$('#modalEdicion').modal('hide');" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?= form_open(base_url() . "", array("id" => "form_orden_item_propiedad_guardar")); ?>
    <div class="row">

        <div id="texto" class="col-12 col-md-8">
            <?php
            $valor = '';
            $arr = array(
                "id" => "valor",
                "name" => "valor",
                "label" => "<b>Valor</b>",
                "value" =>  $resumen_ticket,
                "tipo" => "textarea",
                "class" => "form-control input-lg"
            );
            echo item_input($arr);
            ?>
        </div>
    </div>
    <?= form_close(); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-salir-modal">Salir</button>
</div>

<script>
     $(document).on('click', '#modalEdicion .btn-salir-modal', function() {
            $('#modalEdicion').modal("hide");
        });
</script>
<div class="modal" id="modalOrdenItemPropiedadEdicion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>