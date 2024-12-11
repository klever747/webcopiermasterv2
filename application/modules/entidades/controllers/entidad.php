
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Entidad extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->debeEstarLogeado();
        $this->load->model("entidades/service_entidad");
    }

    /*     * ******************* TICKETS ********************* */

    public function entidades()
    {
        $texto_busqueda = "";
        $listadoEntidades = false;
        $estado_id = false;
        $cuantos = 0;
        $perfil_id = false;
        $perfil_id = $this->session->userPerfilId[0]->id;
        if($perfil_id != 1){
            $sucursal_id = $this->session->userSucursalesId;
        }else{
            $sucursal_id = 0;
        }
       
        $url = $this->api() . 'api/entidad/listarEntidades/'.$sucursal_id;
        $method = 'GET';
        $fields = array();

        if ($this->input->post('btn_buscar') != null) {
            $texto_busqueda = $this->input->post('texto_busqueda');
            if ($texto_busqueda == null) {
                $texto_busqueda = 'noData';
            }
            $estado_id = $this->input->post('estado_id');
            $limite = $this->service_entidad->formart();
            $urlBusquedaText = $this->api() . 'api/entidad/findbytext/'.$sucursal_id.'/' . $texto_busqueda . '/' . $estado_id . '/' . $limite['limit'] . '/' . $limite['offset'] . '';
            $listadoEntidades = $this->request($urlBusquedaText, $method, $fields);
        } else {
            $listadoEntidades = $this->request($url, $method, $fields);
        }
        if ($listadoEntidades->status >= 400 || $listadoEntidades->status >= 500) {
            $data['entidades'] = false;
        } else {
            $data['entidades'] = $listadoEntidades->data->entidades;
            $data['cuantos'] = $listadoEntidades->data->cuantos;

            $data['regpp'] = 10;
            $data['pagina'] = $this->paginacion['pagina'];
            if (isset($data['cuantos'])) {
                $data['itemsPaginacion'] = $this->calcularPaginacion($data['cuantos']);
            }
        }
        $data['estado_id'] = $estado_id;

        $data['texto_busqueda'] = $texto_busqueda;

        $this->mostrarVista('entidades.php', $data);
    }

    public function entidad_nuevo()
    {
        $this->entidad_obtener();
    }

    public function entidad_obtener()
    {
        $proveedores = array();
        $data = array();
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/entidad/findEntidad/' . $id;
            $data['operacion'] = false;
            $entidad = $this->request($urlBusquedaText, 'GET', array());
            $data['entidades']=  $entidad;
            unset($data['entidades']->sucursal_id);
            unset($data['entidades']->fecha_creacion);
            //Buscar el departamento o los departamentos  por el id de la entidad            
            $urlDepartamento = $this->api() . 'api/departamento/listarDepartamentosEntidad/' . $id;
            $departamentos = $this->request($urlDepartamento, 'GET', array());
            $data['departamentos']=  $departamentos->departamentos;
            $data['departamentos'] = $this->load->view('entidad_departamentos_listado.php', $data, true);
            $data['operacion'] = '<i class="fas fa-pencil my-float"></i> Edicion de producto';
        } else {
            $data['departamentos']= false;
            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo producto';
            $data['entidades'] = $this->service_entidad->obtenerNuevoEntidad();
            unset($data['entidades']->sucursal_id);
        }

        $entidad_det = $this->load->view('entidades_edicion.php', $data, true);


        $respuesta = array("error" => (!$data['entidades'] ? true : false), "respuesta" => $entidad_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }

    public function buscarSelDepartamentos($entidad_id =false,$retormarData=false)
    {
        if($entidad_id != true){
            $entidad_id = $this->input->post('id');
        }
        
        $url = $this->api() . 'api/entidad/listarDepartamentos/'.$entidad_id;
        $method = 'GET';
        $fields = array();
        $listadoDepartamentos = $this->request($url, $method, $fields);
        $arr = $this->service_ticket->obtenerDepartamentos($listadoDepartamentos);
        if($retormarData){
            return $arr;
        }
        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    public function buscarSelEquipos($departamento_id=false,$retormarData=false)
    {
        if($departamento_id !=true){
            $departamento_id = $this->input->post('id');
        }
        $departamento_id = $this->input->post('id');
        $url = $this->api() . 'api/entidad/listarEquipos/'.$departamento_id;
        $method = 'GET';
        $fields = array();
        $listadoEquipos = $this->request($url, $method, $fields);
        $arr = $this->service_ticket->obtenerEquipos($listadoEquipos);
        if($retormarData){
            return $arr;
        }
        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    public function buscarSelEntidades($retormarData=false){
        $sucursal_id = $this->session->userSucursalesId;
        $url = $this->api() . 'api/entidad/listarEntidades/'.$sucursal_id;
        $method = 'GET';
        $fields = array();
        $listadoEntidades = $this->request($url, $method, $fields);
        $arr_data = $this->service_ticket->obtenerEntidades($listadoEntidades);
        if($retormarData){
            return $arr_data;
        }
        header('Content-Type: application/json');
        echo json_encode($arr_data);
    }
    public function entidad_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/entidad/findEntidad/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_entidad->obtenerNuevoEntidad();
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
                unset($arr['fecha_creacion']);
                unset($arr['sucursal_id']);
                $url = $this->api() . 'api/entidad/update/'.$id ;
               
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $arr['creacion_usu'] = $this->session->id;
                $arr['sucursal_id'] = $this->session->userSucursalesId;
                $url = $this->api() . 'api/entidad/create';
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

    public function entidad_eliminar()
    {
        $id = $this->input->post('id');
        $url = $this->api() . 'api/entidad/deletelogic/' . $id;
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
    
}
