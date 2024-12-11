<div class="modal-header color_tienda_ <?= $ticket->estado == ESTADO_CONCLUIDO ? "ticket_concluido" : "" ?>">
    <div class="modal-title row col-10" id="Cabecera">
        <div class="row col-12 col-sm-6 col-lg-6 align-center align-items-center">
            <h6 class="col-6">
                Ticket #<?= $ticket->nombre_ticket ?>
            </h6>

            <div class="col-12 col-md-3">
                <?php
                $avance = 10;
                switch ($ticket->estado) {
                    case ESTADO_ACTIVO:
                        $estado = '';
                        break;
                    case ESTADO_INACTIVO:
                        $estado = 'INACTIVO';
                        break;

                    case ESTADO_CONCLUIDO:
                        $estado = 'CONCLUIDO';
                        break;


                    case ESTADO_PROCESO:
                        $estado = 'EN PROCESO';
                        $avance = 25;
                        break;


                    default:
                        $estado = 'ERROR-INDEFINIDO';
                        break;
                }

                ?>
                <?= !empty($estado) ? "<h2>" . $estado . "</h2>" : "" ?>
            </div>


        </div>

    </div>

    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="card card-default" id="orden_detalle_cliente">
    <div class="card-header">
        <h6 class="card-title" data-toggle="collapse" data-target="#det_cliente">

        </h6>
        <div class="card-tools">
            <?php if ($ticket) {
            ?>
                <button type="button" class="btn btn-accion-solucion btn-tool visible_" data-id="<?= $ticket->id ?>" value="agregar_solucion">Agregar Solucion <i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-accion-solucion btn-tool visible_" data-id="<?= $ticket->id ?>" value="agregar_respuesta">Agregar Respuesta <i class="fas fa-pencil-alt"></i></button>
                <!--<button type = "button" class="btn btn-accion btn-tool" data-id="<?= $ticket->id ?>" value="cambiar_cliente_orden"><i class="fas fa-exchange-alt"></i></button>-->
            <?php } ?>

        </div>
    </div>

    <div class="">
        <div class="card-body " id="det_cliente">
            <?php if ($ticket) { ?>
                <div class="row text-left">
                    <div class="col-12 row">
                        <h6 class="col-3 col-lg-auto"><b>Nombre Ticket: </b></h6>
                        <h6 class="col-9"><?= $ticket->nombre_ticket ?></h6>
                    </div>
                    <div class="col-12 col-lg-6 row">
                        <h6 class="col-auto"><b>Resumen: </b></h6>
                        <h6 class="col-9"><?= $ticket->resumen ?></h6>
                    </div>
                    <div class="col-12 col-lg-6 row">
                        <h6 class="col-auto"><b>Entidad: </b></h6>
                        <h6 class="col-9"><?= $ticket->nombre_entidad ?></h6>
                    </div>
                    <div class="col-12 col-lg-6 row">
                        <h6 class="col-auto"><b>Departamento: </b></h6>
                        <h6 class="col-9"><?= $ticket->nombre_dep ?></h6>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="col-lg-3 row">
        <div class="card-body ">
            <select name="ticket_estado" id="ticket_estado" class="form-control input-lg" data-id_ticket="<?= $ticket->id ?>" title="Selección de Finca" style="background: #acded4;">

                <?php
                switch ($ticket->estado) {
                    case ESTADO_ACTIVO:
                        $estado_ticket = 'ACTIVO';
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . $ticket->estado . "' selected='' > " . $estado_ticket . "</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_PROCESO . "' > EN PROCESO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_CONCLUIDO . "' >CONCLUIDO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_INACTIVO . "' > INACTIVO</option>";

                        break;
                    case ESTADO_INACTIVO:
                        $estado_ticket = 'INACTIVO';
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . $ticket->estado . "' selected='' > " . $estado_ticket . "</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_ACTIVO . "' > ACTIVO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_CONCLUIDO . "' >CONCLUIDO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_PROCESO . "' > EN PROCESO</option>";

                        break;
                    case ESTADO_PROCESO:
                        $estado_ticket = 'EN PROCESO';
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . $ticket->estado . "' selected='' > " . $estado_ticket . "</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_ACTIVO . "' > ACTIVO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_INACTIVO . "' > INACTIVO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_CONCLUIDO . "' >CONCLUIDO</option>";
                        break;
                    case ESTADO_CONCLUIDO:
                        $estado_ticket = 'CONCLUIDO';
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . $ticket->estado . "' selected='' > " . $estado_ticket . "</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_ACTIVO . "' > ACTIVO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_INACTIVO . "' > INACTIVO</option>";
                        echo "<option data-id_ticket = '" . $ticket->id . "' value='" . ESTADO_PROCESO . "' > EN PROCESO</option>";
                        break;
                    default:
                        $estado_ticket = 'ERROR-INDEFINIDO';
                        break;
                }



                ?>
            </select>
        </div>
    </div>

</div>

</div>

<!-- NVILLON -->
</section>
<!--  -->


<?php /*
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
  <button type="button" class="btn btn-primary btn-guardar-modal">Guardar Cambios</button>
  </div>
 */
?>

<div class="modal" tabindex="-1" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>


<script>
    /********** CAMBIAR ESTADO DEL TICKET ACTIVO-INACTIVO-PROCESO *************/


    $(document).on('click', '#ticket_estado option', function() {

        var estado = $("#ticket_estado").val();
        var ticket_id = $(this).data('id_ticket');
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/actualizar_estado_ticket', {
            "id": ticket_id,
            "estado": estado
        }, mostrarEdicion);
        //alert(ticket_id +","+ estado);
    });


    $(document).on('click', '.btn-accion-solucion',function() {


        /*************** AGREGAR SOLUCION Y RESPEUSTA DEL TECNICO *********************/


            unsoloclick('.btn-accion-solucion');
            if ($(this).val() === "agregar_solucion") {
                ticket_id = $(this).data('id');
                loadTicketSolucion();
            } else if ($(this).val() === "agregar_respuesta") {
                ticket_id = $(this).data('id');
                loadTicketRespuesta();
            }
       


    });



    function mostrarEdicion(r) {
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicionOrden").modal("hide");
            recargarPrincipalTickets(r);

        }
    }

    function recargarPrincipalTickets(r) {
        if (r.error) {
            mostrarError("Hubo un error, por favor intentelo nuevamente");
        } else {
            mostrarExito("Actualizado");
            $("#btn_buscar").trigger("click");
        }

    }

    function analizarRespuesta(r) {
        if (r.error) {
            mostrarError(r.respuesta);
            return false;
        } else {
            mostrarExito(r.respuesta);
            return true;
        }
        return false;
    }

    function setearProducto() {

    }

    function setearVariante() {

    }

    function mostrarEdicionOrdenItem(r) {
        console.log(r);
        if (analizarRespuesta(r)) {
            $("#modalEdicion .modal-content").html(r.respuesta);
            $("#modalEdicion").modal("show");
            console.log(r.orden_item);
            if (r.orden_item) {
                $('#producto_id').val(r.orden_item.producto_id);
                $('#producto_id').select2().trigger('change');

                $('#variante_id').val(r.variante_id);
                $('#variante_id').select2().trigger('change');


            }
            enlazarSelect('producto_id', 'variante_id', '<?= base_url() ?>ecommerce/orden_variante_select', false);
            llenarSelect("producto_id", '<?= base_url() ?>ecommerce/orden_producto_select', {
                "id": (r.orden_item ? r.orden_item.producto_id : 0)
            }, function() {
                $('#producto_id').select2().trigger('change');
            });

            $(".soloNumeros").inputFilter(function(value) {
                return /^-?\d*$/.test(value);
            });
        }
    }






    function actualizarFechas(r) {
        if (r.fecha_entrega) {
            $('#fecha_entrega').data('daterangepicker').remove();
            $('#fecha_entrega').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: '<?= FORMATO_FECHA_DATEPICKER_JS ?>'
                },
                startDate: r.fecha_entrega,
                endDate: r.fecha_entrega,
                //                minDate: today,
                maxDate: '2050-01-01',
                autoApply: true
            });
        }
        if (r.fecha_carguera) {
            $('#fecha_carguera').data('daterangepicker').remove();
            $('#fecha_carguera').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: '<?= FORMATO_FECHA_DATEPICKER_JS ?>'
                },
                startDate: r.fecha_carguera,
                endDate: r.fecha_carguera,
                minDate: '2010-12-12',
                maxDate: r.fecha_entrega,
                autoApply: true
            });
        }
        if (r.fecha_preparacion) {
            $('#fecha_preparacion').data('daterangepicker').remove();
            $('#fecha_preparacion').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: '<?= FORMATO_FECHA_DATEPICKER_JS ?>'
                },
                startDate: r.fecha_preparacion,
                endDate: r.fecha_preparacion,
                minDate: '2010-12-12',
                maxDate: r.fecha_carguera,
                autoApply: true
            });
        }

        $('.fecha_entrega').on('apply.daterangepicker', function(e, picker) {
            llamadaAjax(false, '<?= base_url() ?>ecommerce/orden/calcular_fechas_entrega', {
                "orden_id": $(this).data('id'),
                "fecha_entrega": picker.startDate.format('YYYY-MM-DD')
            }, actualizarFechas);
        });
        $('.fecha_carguera').on('apply.daterangepicker', function(e, picker) {
            llamadaAjax(false, '<?= base_url() ?>ecommerce/orden/calcular_fechas_entrega', {
                "orden_id": $(this).data('id'),
                "fecha_carguera": picker.startDate.format('YYYY-MM-DD')
            }, actualizarFechas);
        });
        //recargarPrincipal();
    }


    /******************** orden_detalle_item_edicion ************************/
</script>