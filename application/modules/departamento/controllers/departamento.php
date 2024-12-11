
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Departamento extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("entidades/service_entidad");
    }

    /*     * ******************* DEPARTAMENTOS ********************* */

    public function departamento_nuevo()
    {
        $this->departamento_obtener();
    }

    public function departamento_obtener()
    {
        $proveedores = array();
        $data = array();
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/departamento/findDepartamento/' . $id;
            $data['operacion'] = false;
            $departamento = $this->request($urlBusquedaText, 'GET', array());
            unset($departamento->entidad_id);
            unset($departamento->fecha_creacion);
            $data['departamentos'] =  $departamento;

            //Cargar los equipos
            $urlEquipos = $this->api() . 'api/equipo/listarEquiposDepartamento/' . $id;
            $equipos = $this->request($urlEquipos, 'GET', array());
            $data['equipos']=  $equipos->equipos;
            $data['equipos'] = $this->load->view('departamento_equipo_listado.php', $data, true);
            $data['operacion'] = '<i class="fas fa-pencil my-float"></i> Edicion de departamento';
        } else {
            $data['equipos']=false;
            $data['entidad_id'] = $this->input->post('entidad_id');
            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo departamento';
            $data['departamentos'] = $this->service_entidad->obtenerNuevoDepartamento($data['entidad_id']);
        }

        $entidad_det = $this->load->view('departamento_edicion.php', $data, true);


        $respuesta = array("error" => (!$data['departamentos'] ? true : false), "respuesta" => $entidad_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }

    public function departamento_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/departamento/findDepartamento/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_entidad->obtenerNuevoDepartamento();
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {
                unset($arr['entidad_id']);
                unset($arr['fecha_creacion']);
                $id = $this->input->post('id');
                $url = $this->api() . 'api/departamento/update/' . $id;
                $fields = json_encode($arr);

                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $departamento_id = $this->input->post('entidad_id');
                $url = $this->api() . 'api/departamento/create';
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

    public function departamento_eliminar()
    {
        $id = $this->input->post('id');
        $url = $this->api() . 'api/departamento/deletelogic/' . $id;
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
            $urlBusquedaText = $this->api() . 'api/departamento/findDepartamento/' . $id;
            $data['operacion'] = false;
            $departamento = $this->request($urlBusquedaText, 'GET', array());
            unset($departamento->entidad_id);
            unset($departamento->fecha_creacion);
            $data['departamentos'] =  $departamento;

            //Cargar los equipos
            $urlEquipos = $this->api() . 'api/equipo/listarEquiposDepartamento/' . $id;
            $equipos = $this->request($urlEquipos, 'GET', array());
            $data['equipos']=  $equipos->equipos;
            $data['equipos'] = $this->load->view('departamento_equipo_listado.php', $data, true);
            $data['operacion'] = '<i class="fas fa-pencil my-float"></i> Edicion de departamento';
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
    public function equipo_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/equipo/findEquipos/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_entidad->obtenerNuevoEquipo();
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {
                unset($arr['entidad_id']);
                unset($arr['fecha_creacion']);
                $id = $this->input->post('id');
                $url = $this->api() . 'api/equipo/update/' . $id;
                $fields = json_encode($arr);

                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $departamento_id = $this->input->post('departamento_id');
                $url = $this->api() . 'api/equipo/create';
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
}
