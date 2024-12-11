<?php

class Service_ticket extends My_Model
{

    public function obtenerNuevoTicket()
    {
        return (object) [
            'nombre' => '',
            'entidad_id' => '',
            'fecha_creacion' => '',
            'departamento_id' => '',
            'equipo_id' => '',
            'creacion_usu' => '',
            'resumen' => '',
            'estado' => ESTADO_ACTIVO,
        ];
    }
    public function formart()
    {
        $arr = $this->retornarMuchosAPI();

        return $arr;
    }
    /*--------------------------BUSCAR ENTIDADES POR USUARIO REGISTRADO------------------------------ */
    public function obtenerTicketsPorUsuario($userPerfilId)
    {
        $this->db->select('t.id AS id, t.nombre AS nombre_ticket, t.resumen AS resumen,t.fecha_creacion AS fecha_creacion,
        t.estado AS estado, dep.nombre AS nombre_dep, e.nombre AS nombre_entidad, t.departamento_id AS departamento_id,
        t.entidad_id AS entidad_id, t.equipo_id AS equipo_id');
        $this->db->from('ticket t');
        $this->db->join('ticket_usuario tu', 'tu.ticket_id = t.id', 'left');
        $this->db->join('usuario u', 'tu.usuario_id = u.id', 'left');
        $this->db->join('usuario_perfil up', 'u.id = up.usuario_id', 'left');
        $this->db->join('departamento dep', 't.departamento_id = dep.id', 'left');
        $this->db->join('entidad e', 't.entidad_id = e.id', 'left');
        $this->db->join('equipos eq', 't.equipo_id = eq.id', 'left');
        foreach ($userPerfilId as $key => $value) {
            $this->db->where('up.perfil_id', $value->id);
        }
        
        $arr = $this->retornarMuchos();
        $cuantos = $this->retornarConteo();
        $tickets = $this->formatRespuesta(array("tickets" => $arr ,"cuantos" => $cuantos));
        return (object)$tickets;
    }
    /*--------OBTENER ENTIDADES POR USUARIO---------------- */
    public function obtenerEntidadesPorUsuario($usuarioId){
        $this->db->select('e.id, e.nombre, e.descripcion,e.estado');
        $this->db->from('entidad e');
        $this->db->join('departamento dep', 'dep.entidad_id = e.id', 'left');
        $this->db->join('usuario_departamento ud', 'ud.departamento_id = dep.id', 'left');
        $this->db->where('ud.usuario_id', $usuarioId);
        $arrDatos = $this->retornarMuchos();
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    /*--------OBTENER DEPARTAMENTOS POR USUARIO---------------- */
    public function obtenerDepartamentosPorUsuario($usuarioId){
        $this->db->select('dep.id, dep.nombre');
        $this->db->from('entidad e');
        $this->db->join('departamento dep', 'dep.entidad_id = e.id', 'left');
        $this->db->join('usuario_departamento ud', 'ud.departamento_id = dep.id', 'left');
        $this->db->where('ud.usuario_id', $usuarioId);
        $arrDatos = $this->retornarMuchos();
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    public function obtenerEntidades($entidades)
    {
        if ($entidades) {
            $arrDatos = $entidades->data->entidades;
        }
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    public function obtenerDepartamentos($departamentos)
    {
        if ($departamentos) {
            $arrDatos = $departamentos->data->departamentos;
        }
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    public function obtenerEquipos($equipos)
    {
        if ($equipos) {
            $arrDatos = $equipos->data->equipos;
        }
        return $this->retornarSel($arrDatos, "equipo", false);
    }
    /*----------------Tickets con su usuario o tecnico asignado----------*/
    public function obtenerNuevoTicketUsuario()
    {
        return (object) [
            'id' => '',
            'ticket_id' => '',
            'actualizacion' => '',
            'usuario_id' => '',
            'respuesta' => '',
            'fecha_creacion' => '',
            'solucion' => ''
        ];
    }
    public function obtenerTecnicos($sucursal_id = false)
    {
        if ($sucursal_id) {
            $this->db->select('u.id,u.nombre');
            $this->db->from('usuario u');
            $this->db->where('u.estado', ESTADO_ACTIVO);
            //$this->db->where('u.sucursal_id', $sucursal_id);
            $tecnicos = $this->retornarMuchosSinPaginacion(true);
            return $this->retornarSel($tecnicos, "nombre", true);
        }
    }
    public function obtenerSucursalSel($sucursal_id)
    {
        $this->db->select('s.id,s.nombre');
        $this->db->from('sucursal s');
        $this->db->join('usuario u', 'u.sucursal_id = s.id', 'left');
        $this->db->join('usuario_sucursal us', 'us.usuario_id = u.id', 'left');
        $this->db->where('s.estado', ESTADO_ACTIVO);
        if($sucursal_id){
            $this->db->where('us.usuario_id', $sucursal_id);
        }
        
        $sucursales = $this->retornarMuchosSinPaginacion(true);
        return $this->retornarSel($sucursales, "nombre", true);
    }
    public function obtenerEstadosTicket($tipo = 1)
    {
        $respuesta = false;
        switch ($tipo) {
            case 1:
                $respuesta = array(
                    array('id' => 'A', 'nombre' => 'Activo'),
                    array('id' => 'I', 'nombre' => 'Inactivo'),
                    array('id' => 'P', 'nombre' => 'En proceso'),
                    array('id' => 'C', 'nombre' => 'Concluido')
                );
                break;
            default:
                break;
        }
        return (!$respuesta ? $respuesta : json_decode(json_encode($respuesta), FALSE));
    }

    public function obtenerSelEstadoTicket()
    {
        $estadoSelect = $this->obtenerEstadosTicket(1);
        return $this->retornarSel($estadoSelect, "nombre");
    }
    /*----------------OBTENER SOLUCION DEL TECNICO CON ELTICKET ASIGNADO */
    public function obtenerSolucionTecnico($id_ticket = false)
    {
        $solucion = false;
        if ($id_ticket) {
            $this->db->select('tu.id, tu.ticket_id, tu.solucion, tu.respuesta, tu.usuario_id');
            $this->db->from('ticket_usuario tu');
            $this->db->where('tu.ticket_id', $id_ticket);
            $solucion = $this->retornarUno();
        }
        return $solucion;
    }
    /*---------------------ACTUALIZAR SOLUCION DEL TICKET----------------- */
    public function actualizarTicketSolucion($obj)
    {
        $id = $this->actualizar("ticket_usuario", $obj, "id", false);
        return $id;
    }
    public function obtenerTicket($id)
    {
        $ticket_encontrado = false;
        if ($id) {
            $this->db->select('t.id,t.nombre as nombre_ticket, t.resumen, t.fecha_creacion, t.fecha_cierre, tu.respuesta, tu.solucion, e.nombre as nombre_entidad,dep.nombre as nombre_departamento');
            $this->db->from('ticket t');
            $this->db->join('ticket_usuario tu', 't.id = tu.ticket_id', 'left');
            $this->db->join('entidad e', 't.entidad_id = e.id', 'left');
            $this->db->join('departamento dep', 't.departamento_id = dep.id', 'left');
            $this->db->where('t.id', $id);
            $ticket_encontrado = $this->retornarUno();
        }
        return $ticket_encontrado;
    }
    /*--------------------BUSCAR TICKETS PARA EXPORTAR EN EXCEL------------- */
    public function buscarTickets($ids)
    {
        if ($ids) {
            foreach ($ids as $k => $ticket_id) {
                $arr[$k] = $this->obtenerTicket($ticket_id);
            }
        }
        return $arr;
    }
    public function formatRespuesta($datos){
       $resp=array();
        if($datos){
             $resp = [
                'status'   => 200,
                'error'    => null,
                'data' =>(object)$datos,
                'messages' => [
                    'success' => 'Datos Encontrados'
                ]
            ];
        }else{
               $resp = [
                'status'   => 404,
                'error'    => null,
                'data' => (object)$datos,
                'messages' => [
                    'success' => 'Datos no Encontrados'
                ]
            ];
        }
        return $resp;
    }
}
