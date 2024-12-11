<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ticket</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Ticket</a></li>
                            <li class="breadcrumb-item active">Tickets</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Tickets</h3>
                    </div>

                    <?= filtroBusqueda("tickets/ticket/tickets", array("texto_busqueda" => $texto_busqueda, 
                    "rango_busqueda" => $rango_busqueda,"tipo_calendario" => $tipo_calendario,
                    "sel_sucursal" => $sel_sucursal, "sucursal_id" => $sucursal_id,
                    "uso_calendario" => 1,"sel_orden_estado" => $sel_orden_estado,  "orden_estado_id" => $orden_estado_id
                    ,"regpp" => $regpp)); ?>

                    <div class="card-body text-center">
                        <?= isset($itemsPaginacion) ? ($itemsPaginacion) : ''; ?>
                    </div>
                    <div class="card-body centrado">
                        <div class="row">
                            <?php
                            if ($tickets) {
                            ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ticket</th>
                                            <th scope="col">Descripci&oacute;n</th>
                                            <th scope="col">Fecha Creacion</th>
                                            <th scope="col">Entidad</th>
                                            <th scope="col">Departamento</th>
                                            <th scope="col">Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($tickets as $k => $ticket) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $k + 1 ?></th>
                                                <td class="text-left"><?= $ticket->nombre_ticket ?></td>
                                                <td class="text-left"><?= $ticket->resumen ?></td>
                                                <td class="text-justify"><?= $ticket->fecha_creacion ?></td>
                                                <td class="text-justify"><?= $ticket->nombre_entidad ?></td>
                                                <td class="text-justify"><?= $ticket->nombre_dep ?></td>

                                                <!--   <td><?= ($ticket->estado === ESTADO_ACTIVO ? 'Activo' : 'Inactivo') ?></td> -->
                                                <td class="text-left">
                                                    <?= mostrarEstilos($ticket->estado); ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($ticket->estado == 'A') {
                                                    ?>
                                                        <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $ticket->id ?>" value="eliminar"><i class="far fa-trash-alt"></i></button>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $ticket->id ?>" value="editar"><i class="fas fa-pencil-alt"></i></button>
                                                    <button type="submit" class="btn btn-accion btn-tool" data-id="<?= $ticket->id ?>" value="generar_ticket"><i class="fas fa-location-arrow"></i></button>
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

<script>
    var ticket_actual = 0;


    function recargarPrincipal() {
        console.log("recargarPrincipal, productos");
        $("#btn_buscar").trigger("click");
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

    function loadTicket(opcion) {
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/ticket_obtener', {
            "id": ticket_actual,"opcion":opcion
        }, mostrarEdicionOrdenItem);
    }
    function loadTicketUsuario(opcion) {
        llamadaAjax(false, '<?= base_url() ?>tickets/ticket/ticket_usuario_obtener', {
            "id": ticket_actual,"opcion":opcion
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
                    '<?= base_url() ?>tickets/ticket/ticket_eliminar', {
                        "id": $(this).data('id')
                    },
                    mostrarEliminacion);
                /**/
                // llamadaAjax(false, '<?= base_url() ?>ecommerce/producto/producto_eliminar', {"id": $(this).data('id')}, mostrarEliminacion);
            } else if ($(this).val() === "editar") {
                ticket_actual = $(this).data('id');
                loadTicket();
            } else if ($(this).val() === "agregar") {
                llamadaAjax(false, '<?= base_url() ?>tickets/ticket/ticket_nuevo', false, mostrarEdicionOrdenItem);
            } else if ($(this).val() === "generar_ticket") {
                ticket_actual = $(this).data('id');
                loadTicket($(this).val() === "generar_ticket");
            }else if ($(this).val() === "respuesta_usuario") {
                ticket_actual = $(this).data('id');
                loadTicketUsuario();
            }
        });


    });

    function mostrarEdicionOrdenItem(r) {

        if (analizarRespuesta(r)) {
            $("#modalEdicion .modal-content").html(r.respuesta);
            $("#modalEdicion").modal("show");

            if (r.departamento_id  && r.entidad_id) {
                console.log(r.departamento_id);
                enlazarSelect2('entidad_id', 'departamento_id', '<?= base_url() ?>tickets/ticket/buscarSelDepartamentos', function(){
                    $('#departamento_id').val(r.departamento_id);
                    $('#departamento_id').select2().trigger('change');
                });
                llenarSelect2("entidad_id", '<?= base_url() ?>tickets/ticket/buscarSelEntidades', {
                    "id": (r.entidad_id ? r.entidad_id : 1)
                }, function() {
                    $('#entidad_id').val(r.entidad_id);
                    $('#entidad_id').select2().trigger('change');
                    
                });
                enlazarSelect2('departamento_id', 'equipo_id', '<?= base_url() ?>tickets/ticket/buscarSelEquipos', false);

            } else {

                /**----- se uso otro select modificado para poder llenar a partir de 2 selects anteriores otro select */
                enlazarSelect2('entidad_id', 'departamento_id', '<?= base_url() ?>tickets/ticket/buscarSelDepartamentos', false);

                llenarSelect2("entidad_id", '<?= base_url() ?>tickets/ticket/buscarSelEntidades', {
                    "id": (r.entidad_id ? r.entidad_id : 1)
                }, function() {
                    $('#entidad_id').select2().trigger('change');

                });
                enlazarSelect2('departamento_id', 'equipo_id', '<?= base_url() ?>tickets/ticket/buscarSelEquipos', false);

            }

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