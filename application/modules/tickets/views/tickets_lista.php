<link rel="stylesheet" href="<?= base_url() . "assets/" ?>/css/emoji.css">
<!--<link rel = "stylesheet" href = "<?= base_url() . "assets/" ?>img/emoji.png">-->
<?php
?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item">Ticket</li>
                            <li class="breadcrumb-item">Tickets</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default" id="soloLectura">
                    <?php
                    //Filtro agregado

                    if ($session_finca == 1) {
                        $filtroActual = array(
                            "rango_busqueda" => $rango_busqueda, "tipo_calendario" => $tipo_calendario, "uso_calendario" => 1,
                            "texto_busqueda" => $texto_busqueda,
                            "sel_sucursal" => $sel_sucursal, "sucursal_id" => $sucursal_id,
                            "sel_orden_estado" => $sel_orden_estado, "orden_estado_id" => $orden_estado_id,
                            "regpp" => $regpp,
                            "empacar" => true, "imprimir_tarjetas" => "Exportar tickets seleccionados"
                        );
                    }

                    //$filtroActual['tipo_caja'] = $sel_tipo_caja;
                    echo filtroBusqueda($url_busqueda, $filtroActual);
                    ?>

                    <div class="card-body text-center">
                        <?= isset($itemsPaginacion) ? ($itemsPaginacion) : ''; ?>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <?php
                            $item_row_max = 3;
                            $item_row_count = 0;
                            foreach ($ordenes as $orden) {
                                $item_row_count++;
                                echo '<div id="card_orden_' . 2 . '" class="col-12 col-sm-12 col-md-6 col-lg-4 ' . 2 . '">';
                                print_r($orden->card);
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-body justify-content-md-center align-items-start">
                        <?= isset($itemsPaginacion) ? ($itemsPaginacion) : ''; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
</div>

<!-- Modal -->
<div class="modal" id="modalEdicionOrden" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<div class="modal" id="confirmacion_accion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                ¿Esta seguro de que desea cancelar la orden?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-salir-modal" data-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary btn_accion_orden" id="btn_accion_orden_1" data-ticket_id='' data-accion='' data-dismiss="modal">Cancelar Orden</button>
                <button type="button" class="btn btn-primary btn_accion_orden" id="btn_accion_orden_2" data-ticket_id='' data-accion='' data-dismiss="modal">Cancelar Orden</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" id="modalEdicionSolucion" tabindex="-1" role="dialog" aria-labelledby="wsanchez" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<script>
    var filtroActual = <?= json_encode($filtroActual) ?>;
    var orden_actual = <?= $orden_actual ?>;
    var orden_caja_actual = 0
    var item_id = 0;
    var producto_id = 0;
    var variante_id = 0;
    var ticket_id = 0;

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

    function mostrarRespuesta(r) {
        console.log("mostrarRespuesta ordenes ");
        if (analizarRespuesta(r)) {
            recargarPrincipal();
        }
    }
  

    function respuestaEmpaqueOrden(r) {
        if (analizarRespuesta(r)) {
            console.log(r);
            console.log(r.orden_id);
            loadOrdenCard(r.orden_id);
        }
    }

    function recargarPrincipalOrdenes(r) {
        if (r.error) {
            mostrarError("Hubo un error, por favor intentelo nuevamente");
        } else {
            mostrarExito("Actualizado");
            $("#btn_buscar").trigger("click");
        }

    }

    function mostrarOrdenNueva(r) {
        mostrarOrden(r, true);
    }

    function respuestaGenTarjMen(r) {
        if (analizarRespuesta(r) && (r.ruta_pdf != '')) {
            console.log("ruta a abrir es " + r.ruta_pdf);
            window.open(r.ruta_pdf, '_blank');
           
        }
    }

    function respuestaGenTracking(r) {
        if (analizarRespuesta(r) && (r.ruta_pdf != '')) {
            console.log("ruta es ");
            console.log(r.ruta_pdf);
            window.open(r.ruta_pdf, '_blank');
            $.each(r.ordenes_impresas, function(key, orden_id) {
                loadOrdenCard(orden_id);
            });
        }
    }

    function respuestaReenviarOrden(r) {
        if (analizarRespuesta(r)) {
            $('#confirmacion_accion').hide();
            console.log(r);
            orden_actual = r.nueva_orden_id;
            loadOrden();
        }
    }

    function respuestaEmpaqueMasivo(r) {
        analizarRespuesta(r);
        $.each(r.empacados, function(key, orden_id) {
            console.log("recargamos tarjeta de orden " + orden_id);
            loadOrdenCard(orden_id);
        });
    }

    function mostrarOrdenCard(r) {
        console.log("Mostrar orden card");
        //        console.log(r);
        $("#card_orden_" + r.orden_id).html(r.card);
    }

    function mostrarOrden(r, small = false) {
        console.log("mostrarOrden " + r);
        //        console.log(r.detalle_orden);
        //        console.log(r.error);
        //        console.log(small);
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            if (small) {
                $("#modalEdicionOrden .modal-content").parent().addClass("modal-xs");
                $("#modalEdicionOrden .modal-content").parent().removeClass("modal-xl");
            } else {
                $("#modalEdicionOrden .modal-content").parent().addClass("modal-xl");
                $("#modalEdicionOrden .modal-content").parent().removeClass("modal-xs");
            }
            $("#modalEdicionOrden .modal-content").html(r.detalle_ticket);
            $("#modalEdicionOrden").modal("show");

        }
    }

    //    function mostrarEdicion(r) {
    //        if (r.error) {
    //            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
    //        } else {
    //            $("modalEdicionOrden .modal-content-orden").html(r.respuesta);
    //            $("#modalEdicionOrden").modal("show");
    //        }
    //    }

    function mostrarEliminacion(r) {
        if (r.error) {
            mostrarError("Hubo un problema durante la eliminaci&oacute;n");
        } else {
            location.reload();
        }
    }

    function loadTicket() {
        console.log("loadOrden " + orden_actual);
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/obtenerTicket', {
            "id": orden_actual,
            "orden_caja_id": orden_caja_actual,
        }, mostrarOrden);
    }

    function loadOrdenCard(orden_id = false) {
        console.log("loadOrdenCard en ordenes " + orden_actual);
        orden_id = (!orden_id) ? orden_actual : orden_id;
        llamadaAjax(false, '<?= base_url() ?>ecommerce/json_orden_card', {
            "orden_id": orden_id,
            "filtro": filtroActual
        }, mostrarOrdenCard);
    }

    function recargarPrincipal() {
        console.log("RecargarPrincipal en ordenes");
        loadOrden();
        validaciones_buttons();
        loadOrdenCard();
    }
    function loadTicketSolucion() {
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/ticket_usuario_obtener', {
            "id": ticket_id
        }, mostrarEdicionSolucion);
    }
    function loadTicketRespuesta() {
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/ticket_respuesta', {
            "id": ticket_id
        }, mostrarEdicionSolucion);
    }
    function mostrarEdicionSolucion(r) {
        console.log("mostrarEdicion en productos.php linea 132");
        if (r.error) {
            mostrarError("No existe informaci&oacute;n disponible en estos momentos");
        } else {
            $("#modalEdicionSolucion .modal-content").html(r.respuesta);
            $("#modalEdicionSolucion").modal("show");
        }
    }
    $(document).ready(function() {
        
        $("#tracking_number").focus();
        //        $("#tracking_number").on('keypress', function (e) {
        //            if (e.which == 13) {
        //                alert('You pressed enter!');
        //            }
        //        });

        /*************** ACCIONES PANTALLA PRINCIPAL *********************/
        $(document).on('click', '.btn-accion', function() {
            unsoloclick('.btn-accion');
            if ($(this).val() === "agregar_orden") {
                swal_info('Desde la administración de Clientes puede crear una orden nueva');
                //alert('Desde la administración de Clientes puede crear una orden nueva');
            }
        });
        $(document).on('click', '.btn-orden-numero', function() {
            console.log("aqui");
            orden_actual = $(this).data('ticket_id');
            orden_caja_actual = $(this).data('caja_id');
            loadTicket();
        });
        //        $("#modalEdicionOrden").on('shown.bs.modal', function () {
        ////            alert('The modal is fully shown.');
        ////            $(this).find('p').text("This is changed text after alert");
        //        });

        $(document).on('click', '.btn-accion-logistica', function() {
            if ($(this).val() === "meter_en_caja") {
                llamadaAjax(true, '<?= base_url() ?>produccion/logistica/orden_meter_a_caja', {
                    "orden_id": $(this).data('id'),
                }, respuestaEmpaqueOrden);
            }
        });

        function respuestaObtenerReenvio(r) {
            console.log(r.error);
            console.log(r.error === true);
            if (r.error === true) {
                $("#confirmacion_accion .modal-body").html("Seleccione el tipo de reenvio");
                $("#confirmacion_accion .modal-title").html("Reenvio de Orden");
                $("#btn_accion_orden_1").css("display", "block");
                $("#btn_accion_orden_2").css("display", "block");

                $("#confirmacion_accion #btn_accion_orden_1").html("Parcial");
                $("#confirmacion_accion #btn_accion_orden_1").data("orden_id", r.orden_id);
                $("#confirmacion_accion #btn_accion_orden_1").data("accion", "reenvio_orden_parcial");

                $("#confirmacion_accion #btn_accion_orden_2").html("Total");
                $("#confirmacion_accion #btn_accion_orden_2").data("orden_id", r.orden_id);
                $("#confirmacion_accion #btn_accion_orden_2").data("accion", "reenvio_orden_total");

                $('#confirmacion_accion').modal();
            } else {
                mostrarExito(r.mensaje);
                orden_actual = r.reenvio_orden_id;
                loadOrden();
            }
        }

        function respuestaObtenerClonacion(r) {
            console.log(r.error);
            console.log(r.error === true);
            if (r.error === true) {
                $("#confirmacion_accion .modal-body").html("Desea clonar esta orden?");
                $("#confirmacion_accion .modal-title").html("Clonaci&oacute;n de Orden");
                $("#btn_accion_orden_1").css("display", "block");
                $("#btn_accion_orden_2").css("display", "none");

                $("#confirmacion_accion #btn_accion_orden_1").html("Si");
                $("#confirmacion_accion #btn_accion_orden_1").data("orden_id", r.orden_id);
                $("#confirmacion_accion #btn_accion_orden_1").data("accion", "clonacion_orden");

                $('#confirmacion_accion').modal();
            } else {
                mostrarExito(r.mensaje);
                orden_actual = r.clonacion_orden_id;
                loadOrden();
            }
        }

        $(document).on('click', '.btn-accion-orden', function() {
            console.log("btn_accion_orden");
            if ($(this).val() === "imprimir_mensaje") {
                llamadaAjax(true, '<?= base_url() ?>ecommerce/orden/json_imprimir_tarjeta', {
                    "orden_id": $(this).data('orden_id'),
                }, respuestaGenTarjMen);
            } else if ($(this).val() === "imprimir_mensaje_eternizadas") {
                llamadaAjax(true, '<?= base_url() ?>ecommerce/orden/json_imprimir_tarjeta_eternizadas', {
                    "orden_id": $(this).data('orden_id'),
                }, respuestaGenTarjMen);
            } else if ($(this).val() === "reenviar_orden") {
                //vamos a verificar si existe una orden asociada, para cargar esa
                console.log("Reenviar orden");
                llamadaAjax(true, '<?= base_url() ?>produccion/orden/json_obtener_reenvio', {
                    "orden_id": $(this).data('orden_id'),
                }, respuestaObtenerReenvio);

            } else if ($(this).val() === "clonar_orden") {
                //vamos a verificar si existe una orden asociada, para cargar esa
                console.log("Clonar orden");
                llamadaAjax(true, '<?= base_url() ?>produccion/orden/json_obtener_clonacion', {
                    "orden_id": $(this).data('orden_id'),
                }, respuestaObtenerClonacion);

            } else if ($(this).val() === "imprimir_tracking") {
                llamadaAjax(true, '<?= base_url() ?>ecommerce/orden/json_imprimir_tracking', {
                    "orden_id": $(this).data('orden_id')
                }, respuestaGenTarjMen);
            } else if ($(this).val() === "imprimir_mensaje_caja") {
                llamadaAjax(true, '<?= base_url() ?>ecommerce/orden/json_imprimir_tarjeta_caja', {
                    "caja_id": $(this).data('caja_id'),
                    "orden_id": $(this).data('orden_id')
                }, respuestaGenTracking);
                console.log(orden_caja_actual);
            } else if ($(this).val() === "imprimir_caja_and_traking") {
                llamadaAjax(true, '<?= base_url() ?>ecommerce/orden/json_imprimir_traking_and_caja', {
                    "caja_id": $(this).data('caja_id'),
                    "orden_id": $(this).data('orden_id')
                }, respuestaGenTracking);
            }
        });
        //-------------------------------
        // boton imprimir-tarjetas-masivo
        //-------------------------------
        $(document).on('click', '.btn-imprimir', function() {

            if ($(this).val() === "ordenes_seleccionadas") {
                //obtenemos todas las ordenes que tengan un visto
                var ids = '';
                $('input[type=checkbox][name=orden_impresion]').each(function() {
                    if (this.checked) {
                        console.log($(this).val());
                        ids += "-" + $(this).val();
                    }
                });
                if (ids.length > 0) {
                    window.open('<?= base_url() ?>tickets/ticket/imprimir_tickets_masivos?tickets_id='+JSON.stringify(ids), '_blank');

                   // llamadaAjax("imprimir_tarjeta", '<?= base_url() ?>tickets/ticket/imprimir_tickets_masivos', {
                     //   "tickets_id": ids,
                    //}, respuestaGenTarjMen);
                }
            }
        });
        $(document).on('click', '#empacar_ordenes', function() {
            var ids = '';
            $('input[type=checkbox][name=orden_impresion]').each(function() {
                if (this.checked) {
                    //                    console.log($(this).val());
                    ids += "-" + $(this).val();
                }
            });
            if (ids.length > 0) {
                llamadaAjax("empacar_ordenes", '<?= base_url() ?>produccion/logistica/empacar_masivo', {
                    "ordenes_id": ids,
                }, respuestaEmpaqueMasivo);
            }
        });
        <?php if ($orden_actual) {
        ?>
            loadOrden();
        <?php
        }
        ?>

    });
    /****** CLIENTE_DIRECCION_DESTINO ***********/
    $(document).on('click', '#orden_detalle_destino .btn-accion', function() {
        if ($(this).val() === "editar_destino_orden") {
            llamadaAjax(false, '<?= base_url() ?>ecommerce/cliente/orden_direccion_envio_edicion', {
                "direccion_id": $(this).data('direccion_id'),
                "cliente_id": $(this).data('cliente_id'),
                "orden_id": $(this).data('orden_id')
            }, mostrarEdicion);
        } else if ($(this).val() === "cambiar_destino_orden") {
            console.log("Cambiar destino orden");
            console.log($(this).data('direccion_id'));
            console.log($(this).data('cliente_id'));
            console.log($(this).data('orden_id'));
            llamadaAjax(false, '<?= base_url() ?>ecommerce/cliente/cliente_direcciones_envio_listado', {
                "direccion_id": $(this).data('direccion_id'),
                "cliente_id": $(this).data('cliente_id'),
                "orden_id": $(this).data('orden_id')
            }, mostrarEdicion);
        }
    });

    function actualizarDireccionOrden(orden_id, nueva_direccion_id) {
        llamadaAjax(false, '<?= base_url() ?>ecommerce/actualizar_direccion_orden', {
            "orden_id": orden_id,
            "cliente_direccion_id": nueva_direccion_id
        }, recargarPrincipal);
    }

    $(document).on('click', '#orden_detalle_destino_edicion .btn-accion', function() {
        if ($(this).val() === "seleccionar_direccion_destino_orden") {
            console.log("Ai en seleccionar direccion destino orden");
            actualizarDireccionOrden($(this).data('orden_id'), $(this).data('direccion_id'));
        }
    });
    /*********** ORDEN DETALLE CLIENTE *************/
    $(document).on('click', '#orden_detalle_cliente .btn-accion', function() {
        unsoloclick('.btn-accion');
        if ($(this).val() === "editar_cliente_orden") {
            llamadaAjax(false, '<?= base_url() ?>ecommerce/cliente/cliente_obtener', {
                "id": $(this).data('id'),
                "mostrar_direccion_envio": 0
            }, mostrarEdicion);
        }
    });

    /***************** ORDEN ACCIONES *****************/
    $(document).on('click', '.btn-preguntar_accion_orden', function() {

        if ($(this).val() === "cancelar_orden") {
            swal_modal('¿Est&aacute; seguro de la cancelaci&oacute;n de la orden?',
                'Si, cancelar',
                'No',
                '<?= base_url() ?>ecommerce/orden/cancelar_orden', {
                    "orden_id": $(this).data('orden_id')
                },
                recargarPrincipalOrdenes);
            /* $("#confirmacion_accion .modal-body").html("Confirme la cancelaci&oacute;n de la orden");
             $("#confirmacion_accion .modal-title").html("Cancelar Orden");
             $("#btn_accion_orden_1").css("display", "block");
             $("#btn_accion_orden_2").css("display", "none");
             
             $("#confirmacion_accion .modal-content").parent().addClass("modal-xs");
             $("#confirmacion_accion .modal-content").parent().removeClass("modal-lg");
             $("#confirmacion_accion .modal-content").parent().removeClass("modal-xl");
             
             $("#confirmacion_accion #btn_accion_orden_1").html("Cancelar fff Orden");
             $("#confirmacion_accion #btn_accion_orden_1").data("orden_id", $(this).data('orden_id'));
             $("#confirmacion_accion #btn_accion_orden_1").data("accion", "cancelar_orden");
             
             $('#confirmacion_accion').modal();*/
        } else if ($(this).val() === "cambiar_fecha_orden") {
            $("#confirmacion_accion .modal-body").html("Confirme el guardar la fecha de la orden <br/> <ul><li>Si existen items de la orden marcados como bonchados/vestidos estos ser&aacute;n desmarcados.</li></ul>");
            $("#confirmacion_accion .modal-title").html("Cambio fecha de Orden");
            $("#btn_accion_orden_1").css("display", "block");
            $("#btn_accion_orden_2").css("display", "none");

            $("#confirmacion_accion .modal-content").parent().removeClass("modal-xs");
            $("#confirmacion_accion .modal-content").parent().addClass("modal-lg");
            $("#confirmacion_accion .modal-content").parent().removeClass("modal-xl");

            $("#confirmacion_accion #btn_accion_orden_1").html("Guardar fecha de la orden");
            $("#confirmacion_accion #btn_accion_orden_1").data("orden_id", $(this).data('orden_id'));
            $("#confirmacion_accion #btn_accion_orden_1").data("accion", "cambiar_fecha_orden");

            $('#confirmacion_accion').modal();
        }
    });

    $(document).on('click', '.btn_accion_orden', function() {
        console.log($(this).data('accion'));
        if ($(this).data('accion') === "cancelar_orden") {
            llamadaAjax('btn_accion_orden', '<?= base_url() ?>ecommerce/orden/cancelar_orden', {
                "orden_id": $(this).data('orden_id')
            }, recargarPrincipalOrdenes);
        }
        if ($(this).data('accion') === "cambiar_fecha_orden") {
            var finca_id = <?= json_encode($finca_id) ?>;
            var session_finca = <?= json_encode($session_finca) ?>;
            llamadaAjax('btn-accion_guardar_fecha', '<?= base_url() ?>ecommerce/orden/actualizar_fecha_orden', {
                "orden_id": $(this).data('orden_id'),
                "fecha_entrega": $("#fecha_entrega").val(),
                "fecha_carguera": $("#fecha_carguera").val(),
                "fecha_preparacion": $("#fecha_preparacion").val(),
                "finca_id": finca_id,
                "session_finca": session_finca
            }, mostrarRespuesta);
        }
        if ($(this).data('accion') === "reenvio_orden_total") {
            llamadaAjax('btn_accion_orden', '<?= base_url() ?>produccion/orden/json_reenviar_orden_total', {
                "orden_id": $(this).data('orden_id')
            }, respuestaReenviarOrden);
        }
        if ($(this).data('accion') === "reenvio_orden_parcial") {
            llamadaAjax('btn_accion_orden', '<?= base_url() ?>produccion/orden/json_reenviar_orden_parcial', {
                "orden_id": $(this).data('orden_id')
            }, respuestaReenviarOrden);
        }
        if ($(this).data('accion') === "clonacion_orden") {
            llamadaAjax('btn_accion_orden', '<?= base_url() ?>produccion/orden/json_clonar_orden', {
                "orden_id": $(this).data('orden_id')
            }, respuestaReenviarOrden);
        }
    });
</script>