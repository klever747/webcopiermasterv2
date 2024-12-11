
<?php

use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("tickets/service_ticket");
    }

    /*     * ******************* TICKETS ********************* */
    public function subirArchivoPdfTracking($documento)
    {
        $trackings = array();
        require_once 'vendor/autoload.php';
        //require_once('application/libraries/mpdf-development/src/');
        //require_once('application/libraries/FPDI-2.3.7/src/tcpdi.php');

        $dir = 'uploads/tracking/';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($documento['tmp_name'] as $key => $fichero) {
            //muevo el pdf a mi carpeta tracking
            $fichero = $documento['tmp_name'][$key];
            move_uploaded_file($fichero, $dir . $documento['name'][$key]);

            //obtengo el pdf
            $nombreDocumento = $dir . $documento['name'][$key];
            $pdf = new Mpdf();
            $pageCount = $pdf->setSourceFile($nombreDocumento);
            $pdf->SetProtection(array('copy'), '1234', '123', 128);
            $contador = 0;

            for ($i = 1; $i <= $pageCount; $i++) {

                $pageid = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($pageid);
                $pdf->addPage($size['orientation']);
                $pdf->useImportedPage($pageid);

                $nombre = trim('documento' . $key . $contador);

                $contador = $contador + 1;
            }

            $new_filename = $dir . str_replace('.pdf', '', $nombre) . ".pdf";
            //guardo el pdf
            $pdf->Output($new_filename, "F");
        }
    }
    public function tickets()
    {

        // _fn5_TenD6QKRJR@cop2023
        //_fn5_TenD6QKRJR@copiermaster2023
        //Klm_@copier2023P@rsad3121__
        //Klm_@copier2023M@ster2023
        //obtener el array de los tracking del pdf

        $texto_busqueda = "";
        $data['texto_busqueda'] = "";
        $listadoProductos = false;
        $estado_id = false;
        $cuantos = 0;
        $ticketsLista = false;
        $url = $this->api() . 'api/ticket/findTickets';
        $method = 'GET';
        $fields = array();
        $data['rango_busqueda'] = "";
        $data['tipo_calendario'] = 0;
        $data['rango_busqueda'] = $this->input->post('rango_busqueda');
        $sucursal_id = $this->session->userSucursalesId;
        $usuario_id = $this->session->id;
        $data['sel_sucursal'] = $this->service_ticket->obtenerSucursalSel($usuario_id);
        
        $userPerfilId = $this->session->userPerfilId;

        $data['sucursal_id'] = $sucursal_id;


        $data['orden_estado_id'] = 'A';

        $data['sel_orden_estado'] = $this->service_ticket->obtenerSelEstadoTicket();

        if ($this->input->post('btn_buscar') != null) {
            $data['sucursal'] = $this->input->post('sucursal_id');
            $data['estado'] = $this->input->post('orden_estado_id');
            $url = $this->api() . 'api/ticket/findTicketsParam';
            $method = 'GET';
            $arrRango = explode(" - ", $data['rango_busqueda']);
            $limite = $this->service_ticket->formart($ticketsLista);
            $fechaIni = date_format(DateTime::createFromFormat(FORMATO_FECHA, convertirFechaBD($arrRango[0])), 'Y-m-d 00:00:00');
            $fechaFin = date_format(DateTime::createFromFormat(FORMATO_FECHA, convertirFechaBD($arrRango[1])), 'Y-m-d 23:59:59');
            $filtroData = (object)[
                'limite' => $limite['limit'],
                'offset' => $limite['offset'],
                'estado' =>  $data['estado'],
                'texto_busqueda' => $data['texto_busqueda'],
                'fecha_ini' => $fechaIni,
                'sucursal_id' => $data['sucursal'],
                'fecha_fin' => $fechaFin
            ];
            $fields = json_encode($filtroData);
            $listadoTickets = $this->request($url, $method, $fields);
        } else {
            /*------VERIFICAR QUE EL PERFIL DEL USUARIO SEA DIFERENTE DE SUPERADMINISTRADOR O TECNICOS */
            if ($userPerfilId[0]->id != 1 && $userPerfilId[0]->id != 2) {
                $listadoTickets = $this->service_ticket->obtenerTicketsPorUsuario($userPerfilId);
            } else {
                $listadoTickets = $this->request($url, $method, $fields);
            }
        }
        if ($listadoTickets->status >= 400 || $listadoTickets->status >= 500) {
            $data['tickets'] = false;
        } else {
            $data['tickets'] = $listadoTickets->data->tickets;
            $data['cuantos'] = $listadoTickets->data->cuantos;

            $data['regpp'] = 10;
            $data['pagina'] = $this->paginacion['pagina'];
            if (isset($data['cuantos'])) {
                $data['itemsPaginacion'] = $this->calcularPaginacion($data['cuantos']);
            }
        }

        $data['texto_busqueda'] = $texto_busqueda;

        $this->mostrarVista('tickets.php', $data);
    }

    public function ticket_nuevo()
    {
        $this->ticket_obtener();
    }

    public function ticket_obtener()
    {
        $proveedores = array();
        $data = array();
        $opcion = $this->input->post('opcion');
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/ticket/findbyid/' . $id;
            $data['operacion'] = false;
            $tickets = $this->request($urlBusquedaText, 'GET', array());
            $data['ticket'] =  $tickets->data->tickets[0];

            if (!$opcion) {
                unset($data['ticket']->nombre_ticket);
                unset($data['ticket']->fecha_creacion);
                unset($data['ticket']->nombre_dep);
                unset($data['ticket']->nombre_entidad);
                $data['resumen_ticket'] = false;
            }
        } else {
            $data['resumen_ticket'] = false;
            $data['entidades'] = false;
            $data['departamentos'] = false;
            $data['equipos'] = false;
            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo producto';
            $data['ticket'] = $this->service_ticket->obtenerNuevoTicket();
        }
        if ($opcion == 'true') {
            $data['resumen_ticket'] = "TICKET: " . $data['ticket']->nombre_ticket . "-" . $data['ticket']->id . "\r\n" .
                "DEPARTAMENTO:" . " " . $data['ticket']->nombre_dep . "\r\n" .
                "FECHA CREACION: " . " " . $data['ticket']->fecha_creacion . "\r\n" .
                "RESUMEN:" . " " . $data['ticket']->resumen;
            $ticket_det = $this->load->view('ticket_generado.php', $data, true);
        } else {
            $ticket_det = $this->load->view('ticket_edicion.php', $data, true);
        }



        $respuesta = array(
            "error" => (!$data['ticket'] ? true : false), "respuesta" => $ticket_det, "departamento_id" => $data['ticket']->departamento_id,
            "entidad_id" => $data['ticket']->entidad_id, "equipo_id" => $data['ticket']->equipo_id
        );

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }

    public function buscarSelDepartamentos($entidad_id = false, $retormarData = false)
    {
        if ($entidad_id != true) {
            $entidad_id = $this->input->post('id');
        }
        $usuarioId = $this->session->id;
        $userPerfilId = $this->session->userPerfilId;
        $perfil = $userPerfilId[0]->id;
        /*--------------VERIFICAR QUE SI EL ARRAY DE PERFILES TRAE MAS DE UN PERFIL ES DE SUPERADMINSITRADOR---- */
        if ($perfil > 2) {
            /*-------BUSCAR ENTIDADES QUE PERTENECEN AL PERFIL DEL USUARIO----- */
            $arr = $this->service_ticket->obtenerDepartamentosPorUsuario($usuarioId);
        } else {
            $url = $this->api() . 'api/entidad/listarDepartamentos/' . $entidad_id;
            $method = 'GET';
            $fields = array();
            $listadoDepartamentos = $this->request($url, $method, $fields);
            $arr = $this->service_ticket->obtenerDepartamentos($listadoDepartamentos);
            if ($retormarData) {
                return $arr;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    public function buscarSelEquipos($departamento_id = false, $retormarData = false)
    {
        if ($departamento_id != true) {
            $departamento_id = $this->input->post('id');
        }
        $departamento_id = $this->input->post('id');
        $url = $this->api() . 'api/entidad/listarEquipos/' . $departamento_id;
        $method = 'GET';
        $fields = array();
        $listadoEquipos = $this->request($url, $method, $fields);
        $arr = $this->service_ticket->obtenerEquipos($listadoEquipos);
        if ($retormarData) {
            return $arr;
        }
        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    public function buscarSelEntidades($retormarData = false)
    {
        $sucursal_id = $this->session->userSucursalesId;
        $usuarioId = $this->session->id;
        $userPerfilId = $this->session->userPerfilId;
        $perfil = $userPerfilId[0]->id;
        /*--------------VERIFICAR QUE SI EL ARRAY DE PERFILES TRAE MAS DE UN PERFIL ES DE SUPERADMINSITRADOR---- */
        if ($perfil >2) {
            /*-------BUSCAR ENTIDADES QUE PERTENECEN AL PERFIL DEL USUARIO----- */
            $arr_data = $this->service_ticket->obtenerEntidadesPorUsuario($usuarioId);
        } else {
            $url = $this->api() . 'api/entidad/listarEntidades/' . $sucursal_id;
            $method = 'GET';
            $fields = array();
            $listadoEntidades = $this->request($url, $method, $fields);
            $arr_data = $this->service_ticket->obtenerEntidades($listadoEntidades);
            if ($retormarData) {
                return $arr_data;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($arr_data);
    }
    public function ticket_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/ticket/findTicket/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_ticket->obtenerNuevoTicket();
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {

                $id = $this->input->post('id');
                $url = $this->api() . 'api/ticket/update/' . $id;

                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $arr['creacion_usu'] = $this->session->id;
                $arr['nombre'] = $arr['nombre'] . "-" . date('d-m-Y H:i:s');
                $arr['fecha_creacion'] = date('Y-m-d H:i:s');
                $url = $this->api() . 'api/ticket/create';
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'POST', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la creaci&oacute;n';
                } else {
                    if ($actualizacion->status == 200) {
                        $respuesta = 'Registro creado';
                    } else {
                        $respuesta = 'No se registro el ticker, codigo 404 Not Found';
                        $actualizacion = false;
                    }
                }
            }
        } else {
            $respuesta = 'No se encuentra el registro';
        }

        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function actualizar_estado_ticket()
    {
        $id = $this->input->post('id');
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/ticket/findTicket/' . $id;
            $obj = $this->request($url, 'GET', array());
        }
        if ($obj) {
            if ($this->input->post('id') != null) {
                $estado = $this->input->post('estado');
                $estado_actualizar = (object) ['estado' => $estado];
                $id = $this->input->post('id');
                $url = $this->api() . 'api/ticket/update/' . $id;

                $fields = json_encode($estado_actualizar);
                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            }
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function ticket_eliminar()
    {
        $id = $this->input->post('id');
        $url = $this->api() . 'api/ticket/deletelogic/' . $id;
        $actualizacion = $this->request($url, 'PUT', array());
        if (!$actualizacion) {
            $respuesta = 'Existe un problema durante la inactivaci&oacute;n';
        } else {
            $respuesta = 'Registro inactivado';
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => (!$actualizacion ? 'Existe un problema durante la inactivaci&oacute;n' : 'Registro inactivado'));
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    /*--------------------Modulo para asignar ticket a un usuario-----------------*/
    public function ticket_usuario_obtener()
    {
        $proveedores = array();
        $data['solucion_encontrada'] = false;
        $data['respuesta_encontrada'] = false;
        $data = array();
        $opcion = $this->input->post('opcion');
        if ($this->input->post('id') != null) {
            $id_ticket = $this->input->post('id');
            $data['ticket_id'] = $id_ticket;
            $sucursal_id = $this->session->userSucursalesId;
            $data['tecnicos'] = $this->service_ticket->obtenerTecnicos($sucursal_id);
            /*----------------BUSCAR QUE EL TICKET TENGA RESPEUSTA POR EL USUARIO--------------------- */
            $data['solucion_ticket'] = $this->service_ticket->obtenerSolucionTecnico($id_ticket);
            if ($data['solucion_ticket']) {
                if ($data['solucion_ticket']->solucion != null) {
                    $data['vista'] = "VISTA_SOLUCION";
                    $data['solucion_encontrada'] = true;
                    $data['ticket_usuario'] = $data['solucion_ticket'];
                } else {
                    $data['vista'] = "VISTA_SOLUCION";
                    $data['solucion_encontrada'] = false;
                    $data['ticket_usuario'] = $this->service_ticket->obtenerNuevoTicketUsuario();
                    $data['ticket_usuario']->id = $data['solucion_ticket']->id;
                }
            } else {
                $data['vista'] = "VISTA_SOLUCION";
                $data['solucion_encontrada'] = false;
                $data['ticket_usuario'] = $this->service_ticket->obtenerNuevoTicketUsuario();
            }
        }
        $ticket_det = $this->load->view('ticket_usuario_edicion.php', $data, true);

        $respuesta = array(
            "error" => (!$data['ticket_usuario'] ? true : false), "respuesta" => $ticket_det
        );

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function ticket_respuesta()
    {
        $proveedores = array();
        $data['solucion_encontrada'] = false;
        $data['respuesta_encontrada'] = false;
        $data = array();
        $opcion = $this->input->post('opcion');
        if ($this->input->post('id') != null) {
            $id_ticket = $this->input->post('id');
            $data['ticket_id'] = $id_ticket;
            $sucursal_id = $this->session->userSucursalesId;
            $data['tecnicos'] = $this->service_ticket->obtenerTecnicos($sucursal_id);
            /*----------------BUSCAR QUE EL TICKET TENGA RESPEUSTA POR EL USUARIO--------------------- */
            $data['solucion_ticket'] = $this->service_ticket->obtenerSolucionTecnico($id_ticket);
            if ($data['solucion_ticket']) {
                if ($data['solucion_ticket']->respuesta != null) {
                    $data['vista'] = "VISTA_RESPUESTA";
                    $data['respuesta_encontrada'] = true;
                    $data['ticket_usuario'] = $data['solucion_ticket'];
                } else {
                    $data['vista'] = "VISTA_RESPUESTA";
                    $data['respuesta_encontrada'] = false;
                    $data['ticket_usuario'] = $this->service_ticket->obtenerNuevoTicketUsuario();
                    $data['ticket_usuario']->id = $data['solucion_ticket']->id;
                }
            } else {
                $data['vista'] = "VISTA_RESPUESTA";
                $data['respuesta_encontrada'] = false;
                $data['ticket_usuario'] = $this->service_ticket->obtenerNuevoTicketUsuario();
            }
        }
        $ticket_det = $this->load->view('ticket_usuario_edicion.php', $data, true);

        $respuesta = array(
            "error" => (!$data['ticket_usuario'] ? true : false), "respuesta" => $ticket_det
        );

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function ticket_solucion_guardar()
    {
        $actualizacion = false;
        $id  = $this->input->post('id');
        if ($this->input->post('ticket_id') != null) {
            $id_ticket = $this->input->post('ticket_id');
            $obj = $this->service_ticket->obtenerSolucionTecnico($id_ticket);
            if ($obj != true) {

                $obj = $this->service_ticket->obtenerNuevoTicketUsuario();
            }
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            $act = $this->input->post('actualizacion');
            if ($obj->id) {
                if ($arr['solucion'] != null) {
                    unset($arr['respuesta']);
                } else {
                    unset($arr['solucion']);
                }
                if ($arr['usuario_id'] == null) {
                    unset($arr['usuario_id']);
                }
                $actualizacion = $this->service_ticket->actualizarTicketSolucion($arr, true);

                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $ticket_id = $this->input->post('ticket_id');
                unset($arr['actualizacion']);
                $arr['fecha_creacion'] = date('Y-m-d H:i:s');
                $url = $this->api() . 'api/ticketUsuario/create';
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'POST', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la creaci&oacute;n';
                } else {
                    if ($actualizacion->status == 200) {
                        $respuesta = 'Registro creado';
                    } else {
                        $respuesta = 'No se registro el ticker, codigo 404 Not Found';
                        $actualizacion = false;
                    }
                }
            }
        } else {
            $respuesta = 'No se encuentra el registro';
        }

        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function ticket_usuario_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/ticket/findTicket/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_ticket->obtenerNuevoTicketUsuario();
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {

                $id = $this->input->post('id');
                $url = $this->api() . 'api/ticket/update/' . $id;

                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $ticket_id = $this->input->post('ticket_id');
                $arr['fecha_creacion'] = date('Y-m-d H:i:s');
                $url = $this->api() . 'api/ticketUsuario/create';
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'POST', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la creaci&oacute;n';
                } else {
                    if ($actualizacion->status == 200) {
                        $respuesta = 'Registro creado';
                    } else {
                        $respuesta = 'No se registro el ticker, codigo 404 Not Found';
                        $actualizacion = false;
                    }
                }
            }
        } else {
            $respuesta = 'No se encuentra el registro';
        }

        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    /** ---------------Lista de tickets Generados --------*/
    public function tickets_lista($orden_id = false, $orden_caja_id = false)
    {
        $data['ordenes'] = array();
        $ticketsLista = false;
        $data['sucursal_id'] = 0;
        $data['rango_busqueda'] = "";
        $data['tipo_calendario'] = 0;
        $data['orden_estado_id'] = 'A';
        $data['texto_busqueda'] = "";
        $data['finca_id'] = 0;
        $data['session_finca'] = $this->session->userSucursalesId;
        $data['tipo_caja'] = '';
        $cuantasOrdenes = 0;

        $data['orden_actual'] = 0;
        $data['sel_orden_estado'] = $this->service_ticket->obtenerSelEstadoTicket();
        $data['sel_sucursal'] = $this->service_ticket->obtenerSucursalSel();
        if ($this->input->post('btn_buscar') != null) {
            $data['estado'] = $this->input->post('orden_estado_id');
            $data['sucursal'] = $this->input->post('sucursal_id');
            $data['rango_busqueda'] = $this->input->post('rango_busqueda');
            $data['tipo_calendario'] = $this->input->post('tipo_calendario');

            //            error_log(print_r($data,true));
            if ($this->input->post('texto_busqueda') != null) {
                $data['texto_busqueda'] = $this->input->post('texto_busqueda');
            }
            $data['finca_id'] = $this->input->post('finca_id');
            $data['orden_estado_id'] = $this->input->post('orden_estado_id');
            $listadoOrdenes = false;

            //                List($listadoOrdenes, $cuantasOrdenes) = $this->service_ecommerce->obtenerOrdenes($data['store_id'], $data['tipo_calendario'], $data['rango_busqueda'], $data['order_number'], $data['texto_busqueda'], false, $tarjeta_impresa);
            $url = $this->api() . 'api/ticket/findTicketsParam';
            $method = 'GET';
            $arrRango = explode(" - ", $data['rango_busqueda']);
            $limite = $this->service_ticket->formart($ticketsLista);
            $fechaIni = date_format(DateTime::createFromFormat(FORMATO_FECHA, convertirFechaBD($arrRango[0])), 'Y-m-d 00:00:00');
            $fechaFin = date_format(DateTime::createFromFormat(FORMATO_FECHA, convertirFechaBD($arrRango[1])), 'Y-m-d 23:59:59');
            $filtroData = (object)[
                'limite' => $limite['limit'],
                'offset' => $limite['offset'],
                'estado' =>  $data['estado'],
                'texto_busqueda' => $data['texto_busqueda'],
                'fecha_ini' => $fechaIni,
                'sucursal_id' => $data['sucursal'],
                'fecha_fin' => $fechaFin
            ];
            $fields = json_encode($filtroData);
            $listadoTickets = $this->request($url, $method, $fields);
            $data['tickets'] = $listadoTickets->data->tickets;
            $data['cuantos'] = $listadoTickets->data->cuantos;
            list($listadoOrdenes, $cuantasOrdenes) = array($data['tickets'], $data['cuantos']);

            $filtro = array(
                "texto_busqueda" => $data['texto_busqueda'], "session_finca" => $data['session_finca'],
                "finca_id" => $data['finca_id'],
            );
            if ($listadoOrdenes) {

                foreach ($listadoOrdenes as $orden => $value) {

                    $value->card = $this->orden_card($value->id, $filtro); // $this->load->view('orden_card.php', $orden, true);
                    if ($value->card != false) {
                        $data['ordenes'][] = $value;
                    }
                }
            }
            $filtro = array(
                "tipo_calendario" => $data['tipo_calendario'],
                "rango_busqueda" => $data['rango_busqueda'],
                "session_finca" => $data['session_finca'],
                "finca_id" => $data['finca_id'],
                "orden_estado_id" => $data['orden_estado_id'],
            );
        }

        $data['cuantos'] = $cuantasOrdenes;
        $data['sel_tipo_caja'] = false;
        $data['url_busqueda'] = "tickets/ticket/tickets_lista";

        $this->mostrarVista('tickets_lista.php', $data);
    }
    private function orden_card($orden_id, $filtro = array())
    {
        $urlBusquedaText = $this->api() . 'api/ticket/findbyid/' . $orden_id;
        $tickets = $this->request($urlBusquedaText, 'GET', array());
        $orden =  $tickets->data->tickets[0];
        //por cada caja vamos a ver su contenido

        /*         * ************************* */
        $items = '';
        // if ($orden->detalle) {
        //      $todosEnCaja = true;

        $items .= '<div class="row small rounded ' . (2 === 0 ? "fila_par" : "fila_impar") . '">'
            . '<div class="col-12 text-left text-truncate">' . $orden->nombre_ticket . '</div>'
            . '<div class="col-7 offset-1 text-left text-truncate">' . $orden->resumen . '</div>'
            . '<div class="col-2 text-right">x' . $orden->nombre_dep . '</div>'
            . '<div class="col-2 text-right">' . ($orden->nombre_entidad == 'S' ? '<i class="fas fa-spa col-12"></i>' : '') . '</div>'
            . '</div>';

        // }

        $card = $this->load->view('tickets/ticket_card.php', $orden, true);

        return $card;
    }
    public function obtenerTicket()
    {
        $data['session_finca'] = $this->session->userFincaId;
        $data['ecommerce'] = true;

        $ticket_id = $this->input->post('id');
        $orden_caja_id = $this->input->post('orden_caja_id');
        $finca_id = $this->input->post('finca_id');
        $session_finca = $this->input->post('session_finca');

        $urlBusquedaText = $this->api() . 'api/ticket/findbyid/' . $ticket_id;
        $tickets = $this->request($urlBusquedaText, 'GET', array());
        $ticket =  $tickets->data->tickets[0];

        if ($ticket) {
            $data['ticket'] = $ticket;
            $detalle_ticket = $this->load->view('ticket_detalle.php', $data, true);
            $respuesta = array(
                "error" => false,
                "ticket_id" => $ticket->id,
                "detalle_ticket" => $detalle_ticket
            );
        } else {
            $respuesta = array("error" => "No existe informacion de la orden $ticket_id");
        }

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    /*---------------PROCESO PARA IMPRIMIR LOS TICKETS GENERADOS----------- */
    public function imprimir_tickets_masivos()
    {
        $tickets_id = json_decode($this->input->get('tickets_id'), true);
        $detalle = '';
        $arr = explode("-", substr($tickets_id, 1));
        $ids = array();
        $filename = "resumenTickets_" . fechaActual('YmdHis') . ".xls";

        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        foreach ($arr as $a) {
            $ids[] = $a;
        }
        $lista_tickets = $this->service_ticket->buscarTickets($ids);
        $tabla = $this->load->view('tickets_resumen_mensual.php', array('arrListaTickets' => $lista_tickets, 'excel' => true), true);
        $ruta_pdf = FCPATH . "uploads/xls/preparacion/";
        file_put_contents($ruta_pdf . $filename, $detalle);
        print_r($tabla);
    }
}
