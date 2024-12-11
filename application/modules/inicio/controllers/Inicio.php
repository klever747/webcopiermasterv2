<?php

use Firebase\JWT\JWT;

defined('BASEPATH') or exit('No direct script access allowed');

class Inicio extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->debeEstarLogeado();
        $this->load->model("usuarios_departamento/service_login_usuarios");
    }

    public function index()
    {
        $data = array();
        if (isset($_GET['error'])) {
            echo $_GET['error'];
            $data['error'] = urldecode($_GET['error']);
        }

        if ($this->session->userdata("logeado") == null) {
            $this->homepage();
        } else {
            $this->homepageCopier();
        }
    }

    function ingresoSistema($data = array())
    {
        $this->load->model("seguridad/usuario_model");
        if ($this->input->post("usuario") != null) {
            $url = $this->api() . 'auth/login';
            $method = 'POST';
            $header = array();
            $email = $this->input->post("usuario");
            $password = $this->input->post("password");
            $fields = array('usuario' => $email, 'password' => $password);
            $token = $this->request($url, $method, $fields, $header);


            if ($token && $token->status == 200) {
                
                $this->session->set_userdata('logeado', true);
                $this->session->set_userdata('userName', $token->userName);
                $this->session->set_userdata('token', $token->token);
                $this->session->set_userdata('userEmail', $token->userEmail);
                $this->session->set_userdata('id', $token->id);

                $perfiles = $this->usuario_model->obtenerUsuarioPerfiles($token->id);
                $perfilesid = $this->usuario_model->obtenerUsuarioPerfiles($token->id, true);
                $sucursales = $this->usuario_model->obtenerUsuarioSucursales($token->id);
                $sucursalesid = $this->usuario_model->obtenerUsuarioSucursales($token->id, true);

                $array_sucursales = array();
                foreach ($sucursalesid as $item) {
                    $thearray = (array) $item;
                    $array_sucursales[] = $thearray['id'];
                }
                $sucursalid = implode(",", $array_sucursales);

                $this->session->set_userdata('userPerfil', $perfiles);
                $this->session->set_userdata('userPerfilId', $perfilesid);

                $this->session->set_userdata('userSucursales', $sucursales);
                $this->session->set_userdata('userSucursalesId', $sucursalid);


                redirect(base_url("dashboard"));
                return;
                
                
            } else {
                $data['error'] = 'No existe un usuario registrado con la informaci&oacute;n ingresada';
            }
        }
        $data['permtirAsociacion'] = $this->session->userdata("permitirAsociacion");
        $data['claseBody'] = "hold-transition login-page";
        $this->mostrarVista('ingreso_sistema.php', $data);
    }

    public function homepage($data = array())
    {
        //        $data['claseBody'] = "hold-transition";
        //        $data['session_username'] = $this->session->userdata("userName");
        //        $this->mostrarVista('homepage.php', $data);
        //redirect(base_url("dashboard"));
        $this->mostrarVista2('soluciones.php', $data);
    }
    public function homepageCopier($data = array())
    {
        redirect(base_url("dashboard"));
    }

    function generarTokenUser($buscarUsuario, $data)
    {
        if (!empty($buscarUsuario)) {

            /*=============================================
			Encriptamos la contraseña
			=============================================*/

            $crypt = crypt($data["password"], '$2a$07$azybxcags23425sdg23sdfhsd$');

            if ($buscarUsuario->password == $crypt) {

                /*=============================================
				Creación de JWT
				=============================================*/

                $time = time();
                $key = "azscdvfbgnhmjkl1q2w3e4r5t6y7u8i9o";

                $token = array(

                    "iat" => $time,  // Tiempo que inició el token
                    "exp" => $time + (60 * 60 * 24), // Tiempo que expirará el token (+1 dia)
                    'data' => [
                        "id" =>  $buscarUsuario->id,
                        "email" =>  $buscarUsuario->correo
                    ]
                );

                $jwt = JWT::encode($token, $key, 'HS256');
                /*=============================================
				Actualizamos la base de datos con el Token del usuario
				=============================================*/

                $data = array(
                    "token" => $jwt,
                    "token_exp_user" => $token["exp"],
                    "id" => $buscarUsuario->id
                );
                $update = $this->service_login_usuarios->actualizarTokenUser($data);

                if ($update) {

                    $response["token"] = $jwt;
                    $response['token_exp_user'] = $token["exp"];

                    return $response;
                } else {
                    return "Hubo problemas con la autenticacion";
                }
            } else {

                return "contraseña incorrecta";
            }
        } else {

            return "No se encontro ningun usuario con esos datos";
        }
    }
}
