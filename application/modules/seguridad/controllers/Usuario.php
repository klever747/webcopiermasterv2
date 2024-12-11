<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("seguridad/service_seguridad_perfil");
        $this->load->model("seguridad/Usuario_model");
        $this->load->library('form_validation');
    }

    /*     * ******************* USUARIO ********************* */

    public function usuarios()
    {
        $texto_busqueda = "";
        $listadoUsuarios = false;
        $estado_id = false;
        $cuantos = 0;

        if ($this->input->post('btn_buscar') != null) {
            $texto_busqueda = $this->input->post('texto_busqueda');
            $estado_id = $this->input->post('estado_id');

            list($listadoUsuarios, $cuantos) = $this->Usuario_model->obtenerUsuario(false, $estado_id, $texto_busqueda);
        }
        $data['estado_id'] = $estado_id;
        $data['usuarios'] = $listadoUsuarios;

        $data['cuantos'] = $cuantos;
        $data['texto_busqueda'] = $texto_busqueda;

        $this->mostrarVista('usuarios.php', $data);
    }

    public function usuario_nuevo()
    {
        $this->usuario_obtener();
    }

    //editar contrasena --corregir por que ejecuta dos veces 
    public function editar_password()
    {
        $data['perfil'] = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
        }
        $data['usuario'] = $this->Usuario_model->obtenerUsuario($id);
        $data['contrasena'] = true;
        $usuario_det = $this->load->view('usuario_edicion.php', $data, true);
        $respuesta = array("error" => (!$data['usuario'] ? true : false), "respuesta" => $usuario_det);
        header('Content-Type: application/json');

        echo json_encode($respuesta);
    }

    public function usuario_obtener()
    {
        $data['perfil'] = false;
        $data['sucursal'] = false;
        $error = false;
        $data['contrasena'] = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $data['usuario'] = $this->Usuario_model->obtenerUsuario($id);
           // $this->prepararDatos($data['usuario']);
            $data['perfil'] = $this->Usuario_model->obtenerUsuarioPerfil($id);
            $data['sucursal'] = $this->Usuario_model->obtenerUsuarioSucursal($id);
        } else {
            $data['usuario'] = $this->Usuario_model->obtenerNuevoUsuario();
        }
        //obtengo array de perfiles 

        $data['perfiles'] = $this->service_seguridad_perfil->obtenerSelPerfil();
        $data['sucursales'] = $this->service_seguridad_perfil->obtenerSelSucursal();

        $usuario_det = $this->load->view('usuario_edicion.php', $data, true);

        $respuesta = array("error" => (!$data['usuario'] ? true : false), "respuesta" => $usuario_det, "departamento_id" => $data['usuario']->departamento_id,
        "entidad_id" => $data['usuario']->entidad_id, "equipo_id"=>$data['usuario']->equipo_id);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }

    public function usuario_guardar()
    {
        $actualizacion = false;
        $error_validacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $obj = $this->Usuario_model->obtenerUsuario($id);
        } else {
            $obj = $this->Usuario_model->obtenerNuevoUsuario();
        }
        $arr = array();
        if ($obj) {
            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {
                $arr = $this->prepararpassword($arr);
                if (!empty($this->input->post("estado"))) {
                    $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]');
                    $this->form_validation->set_rules('usuario', 'Usuario', 'required|min_length[5]');
                    $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email');
                    $this->form_validation->set_rules('sucursal_id[]', 'Sucursal_id', 'required');
                    $this->form_validation->set_rules('perfil_id[]', 'Perfil_id', 'required');
                    if ($this->form_validation->run()) {
                        unset($arr['token']);
                        unset($arr['token_exp_user']);
                        unset($arr['foto']);
                        $actualizacion = $this->Usuario_model->actualizarUsuario($arr, true);
                        if ($this->input->post('perfil_id')) {
                            $perfilespost = $this->input->post('perfil_id');
                            $this->Usuario_model->actualizarUsuarioPerfil($perfilespost, $arr);
                        }
                        if ($this->input->post('sucursal_id')) {
                            $fincaspost = $this->input->post('sucursal_id');
                            $this->Usuario_model->actualizarUsuarioSucursal($fincaspost, $arr);
                        }
                    } else {
                        $error_validacion = true;
                    }
                } else {
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
                    if ($this->form_validation->run()) {
                        $actualizacion = $this->Usuario_model->actualizarUsuarioPassword($arr['id'], $arr['password']);
                    } else {
                        $error_validacion = true;
                    }
                }
                if ($error_validacion) {
                    $respuesta = validation_errors();
                } else {
                    if (!$actualizacion) {
                        $respuesta = 'Existe un problema durante la actualización';
                    } else {
                        $respuesta = 'Registro actualizado';
                    }
                }
            } else {
                $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]');
                $this->form_validation->set_rules('usuario', 'Usuario', 'required|min_length[5]');
                $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
                $this->form_validation->set_rules('sucursal_id[]', 'Sucursal_id', 'required');
                $this->form_validation->set_rules('perfil_id[]', 'Perfil_id', 'required');

                if ($this->form_validation->run()) {
                    //cifrar password
                    $contrasena = $arr['password'];
                    if (isset($contrasena) && $contrasena != null) {
                        $cryp =  crypt($contrasena, '$2a$07$azybxcags23425sdg23sdfhsd$');
                        $arr['password'] = $cryp;
                    }
                    //$array = array_replace($arr, $contrasena_new);

                    $actualizacion = $this->Usuario_model->crearUsuario($arr, true);
                    $this->Usuario_model->crearUsuarioPerfil($arr, $actualizacion);
                    $this->Usuario_model->crearUsuarioSucursal($arr, $actualizacion);
                    if (!$actualizacion) {
                        $respuesta = 'Existe un problema durante la creación';
                    } else {
                        $respuesta = 'Registro creado';
                    }
                } else {
                    $respuesta = validation_errors();
                }
            }
        } else {
            $respuesta = 'No se encuentra el registro';
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }

    public function usuario_eliminar()
    {
        $id = $this->input->post('id');
        $actualizacion = $this->Usuario_model->actualizarUsuario(array("id" => $id, "estado" => ESTADO_INACTIVO), true);
        if (!$actualizacion) {
            $respuesta = 'Existe un problema durante la inactivación';
        } else {
            $respuesta = 'Registro inactivado';
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => (!$actualizacion ? 'Existe un problema durante la inactivación' : 'Registro inactivado'));
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        //error_log(print_r($respuesta, true));
    }

    public function prepararpassword($arr)
    {
        $contrasena = $arr['password'];

        $obj = $this->Usuario_model->obtenerUsuario($arr['id']);

        if ($obj->password == $contrasena) {
            $contrasena_new = array('password' => $contrasena);
        } else {
            //cifrar password
            if (isset($contrasena) && $contrasena != null) {
                $cryp =  crypt($contrasena, '$2a$07$azybxcags23425sdg23sdfhsd$');
                $contrasena_new = array('password'=>$cryp);
            }
           
        }
        
        //remplazo la contraseña por la misma pero encriptada y devuelvo el array
        $array_editada = array_replace($arr, $contrasena_new);

        return $array_editada;
    }
    public function prepararDatos($datos)
    {
        unset($datos->password);
        unset($datos->token);
        unset($datos->token_exp_user);
        unset($datos->foto);
        return $datos;
    }
}
