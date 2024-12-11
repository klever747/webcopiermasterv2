<?php
$items = '';
$cajas_id = array();
$todosEnCaja = false;

?>
<div class="card color_ticket_<?= $estado ?> ">
    <div class="card-header row col-12 pr-0 m-0 ">        
        <button class="btn btn-orden-numero row col-6" data-ticket_id="<?= $id ?>"  >
            <div class="col-12 row">
<!--<a href="#modalOrden" class="btn btn-orden-card" data-orden_id="<?= $id ?>" data-toggle="modal" data-target="#modalOrden" >-->
                <b class="col-12 text-left"><?= $nombre_entidad ?> </b>
                <b class="col-12 text-left"><?= $nombre_dep?> </b>
                <b class="col-12 text-left small">Ticket. #<?= $nombre_ticket ?></b>
            </div>
        </button>
        <div class="row col-6">
            <div class="col-12 row pr-0">
                <button type = "button" id="btn-accion-logistica_<?= $id ?>" class="btn <?= (1 == PANTALLA_LOGISTICA ? 'btn-accion-logistica' : '') ?> btn-tool col-8" data-id="<?= $id ?>" value="meter_en_caja"  style="color:blue; text-align:right;" data-toggle="tooltip" data-placement="top" title="Empacar automaticamente">
                    <i class="fas fa-box fa-lg"></i> x<?= sizeof($cajas_id) . " " . ((sizeof($cajas_id) > 0 && $todosEnCaja) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-exclamation"></i>') ?>
                </button>                
                <div class="col-4">
                    <?= form_checkbox('orden_impresion', $id, ($estado == ESTADO_ACTIVO ? true : false), array("class" => "col-6")) ?> x 
                </div>
            </div>
            <div class="col-12 row pr-0">

                <?php
                if ($estado == ESTADO_ACTIVO) {
                    ?>
                    <button type = "button" class="btn btn-accion-orden btn-tool col-4" data-ticket_id="<?= $id ?>" value="imprimir_mensaje" data-toggle="tooltip" data-placement="bottom" title="Imprimir tarjeta">
                        <i class="fas fa-print fa-sm"></i>
                    </button>
                    <button type = "button" class="btn btn-accion-orden btn-tool col-4" data-ticket_id="<?= $id ?>" value="imprimir_mensaje_eternizadas" data-toggle="tooltip" data-placement="bottom" title="Imprimir tarjeta Eternizadas">
                        <i class="fas fa-fingerprint"></i>
                    </button>
                    <button type = "button" class="btn btn-accion-orden btn-tool col-4" data-ticket_id="<?= $id ?>" value="reenviar_orden" data-toggle="tooltip" data-placement="bottom" title="Reenviar">
                        <i class="fas fa-share-square fa-sm"></i>
                    </button>
                    <button  type = "button" class="btn btn-accion-orden btn-tool col-4" data-ticket_id="<?= $id ?>" value="clonar_orden" data-toggle="tooltip" data-placement="bottom" title="Clonar">
                        <i class="far fa-clone"></i>
                    </button>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row col-12 text-left">
            <?= $estado == ESTADO_CONCLUIDO ? "<h2 class='col-12'>CONCLUIDO</h2>" : "" ?>
            <?= $estado == ESTADO_ERROR ? "<h2 class='col-12'>ERROR</h2>" : "" ?>
        </div>
    </div>
    <div class="card-body pt-1 pb-1">          
        <div class="card-text small">
            <?= $items; ?>
        </div>
    </div>
</div>
<script>

</script>