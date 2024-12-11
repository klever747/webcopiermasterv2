
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Equipo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("entidades/service_entidad");
    }
    /*------------MODULO PARA CREAR LOS EQUIPOS DE LOS DEPARTAMENTOS------------*/
    public function equipo_nuevo()
    {
        $this->equipo_obtener();
    }

    public function equipo_obtener()
    {
        $proveedores = array();
        $data = array();
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/equipo/findEquipos/' . $id;
            $data['operacion'] = false;
            $equipo = $this->request($urlBusquedaText, 'GET', array());
            unset($equipo->departamento_id);
            unset($equipo->creacion_fecha);
            unset($equipo->creacion_ip);
            $data['equipos'] =  $equipo;
            $data['equipos']->departamento_id = $this->input->post('departamento_id');
        } else {
            $data['equipos']=false;
            $data['departamento_id'] = $this->input->post('departamento_id');
            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo equipo';
            $data['equipos'] = $this->service_entidad->obtenerNuevoEquipo($data['departamento_id']);
        }

        $equipo_det = $this->load->view('equipo_edicion.php', $data, true);


        $respuesta = array("error" => (!$data['equipos'] ? true : false), "respuesta" => $equipo_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}
