<?php

class Service_login_usuarios extends My_Model
{

    public function crearUsuario($obj)
    {
        $id = $this->ingresar("usuario", $obj, true, false);

        return $id;
    }

    public function buscarUsuarioLogin($datos)
    {
        $this->db->select('u.id, u.nombre, u.usuario, u.correo, u.password');
        $this->db->from('usuario u');
        if ($datos) {
            $this->db->where('u.usuario', $datos['usuario']);
        }
        $usuario = $this->retornarUno(true);
        return $usuario;
    }
    public function actualizarTokenUser($data)
    {
        $id = $this->actualizar("usuario", $data, "id", false);
        return $id;
    }
    public function obtenerUsuarioPerfiles($id, $traerid = false)
    {
        if ($traerid) {
            $this->db->select('p.id');
        } else {
            $this->db->select('p.nombre');
        }
        $this->db->from('usuario_perfil up');
        $this->db->join('perfil p', 'up.perfil_id = p.id', 'left');
        $this->db->where('up.usuario_id', $id);
        $this->db->where('up.estado', ESTADO_ACTIVO);
        $arr = $this->retornarMuchos();
        return $arr;
    }
    public function obtenerUsuarioSucursales($id, $traerid = false)
    {
        if ($traerid) {
            $this->db->select('s.id');
        } else {
            $this->db->select('s.nombre');
        }

        $this->db->from('usuario_sucursal us');
        $this->db->join('sucursal s', 'us.sucursal_id = s.id', 'left');
        $this->db->where('us.usuario_id', $id);
        $this->db->where('us.estado', ESTADO_ACTIVO);
        $arr = $this->retornarMuchos();
        return $arr;
    }
}
