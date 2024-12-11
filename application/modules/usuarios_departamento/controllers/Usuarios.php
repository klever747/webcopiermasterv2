
<?php

use Firebase\JWT\JWT;

defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("usuarios_departamento/service_login_usuarios");
    }

    function postRegistroUsuarioDepartamento()
    {
        $email = 'contabilidad_gad@gmail.com';
        $data['respuesta_registro'] = array();
        if (isset($email)) {

            /*=============================================
			Validamos la sintaxis de los campos
			=============================================*/
            $nombre = 'contabilidad';
            $usuario = 'gadcontabilidad';
            $password_user = 'Gad_contabilidad';

            $data = array(
                "nombre" => $nombre,
                "usuario" => $usuario,
                "correo" => strtolower($email),
                "password" => "",
                "foto"=>"",
                "estado" => "A",
                "sucursal_id" =>"1",
                "token"=>''
            );
            if (isset($password_user) && $password_user != null) {
                $cryp =  crypt($password_user, '$2a$07$azybxcags23425sdg23sdfhsd$');
                $data["password"] = $cryp;
                $registroUsuario = $this->service_login_usuarios->crearUsuario($data);
            }
            
        }
        return $data['respuesta_registro'];
    }
}
