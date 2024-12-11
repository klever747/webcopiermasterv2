<?php

class Service_login_user extends My_Model {

    public function crearUsuario($obj) {
        $id = $this->ingresar("users", $obj, true, false);
        
        return $id;
    }

    public function verificarUsuario($correo){
        $this->db->select('u.id, u.metodo_user');
        $this->db->from('users u');
        if($correo){
            $this->db->where('u.email_user',$correo);
        }
        $usuario = $this->retornarUno(true);
        return $usuario;
    }
    public function actualizarVerificacionUsuario($datosActualizar = array()){
        $id = $this->actualizar("users", $datosActualizar, "id", false);
        return $id;
    }
    public function buscarUsuarioLogin($datos){
        $this->db->select('u.id, u.metodo_user, u.verificacion_user, u.password_user, u.email_user');
        $this->db->from('users u');
        if($datos){
            $this->db->where('u.email_user',$datos['email_user']);
        }
        $usuario = $this->retornarUno(true);
        return $usuario;
    }
    public function actualizarTokenUser($datosActualizar = array()){
        $id = $this->actualizar("users", $datosActualizar, "id", false);
        return $id;
    }
}
