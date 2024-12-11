<?php

class Usuario_model extends My_Model {

    public function ingresarNuevoUsuario($data) {

        $datos = array(
            "nombre" => $data['email'],
            "usuario" => $data['email'],
            "correo" => $data['email'],
            "password" => $data['password'],
            "estado" => 'A');

        return $this->ingresar("usuario", $datos);
    }
    public function obtenerNuevoUsuario() {
        return (object) [
                    'nombre' => '',
                    'usuario' => '',
                    'correo' => '',
                    'password' => '',
                    'sucursal_id' => '',
                    'entidad_id' => '',
                    'departamento_id' => '',
                    'equipo_id' => '',
                    'estado' => ESTADO_ACTIVO,
                    'perfil_id' => '',
        ];
    }
    public function obtenerUsuarioPerfil($id = false) {
        $this->db->select('*');
        $this->db->from('usuario_perfil ');
        if($id){
            $this->db->where('usuario_id', $id);
        }
        $this->db->where('estado', ESTADO_ACTIVO);
        $arr = $this->retornarMuchos();
        return $arr;
    }
    public function obtenerUsuarioSucursal($id = false) {
        $this->db->select('*');
        $this->db->from('usuario_sucursal ');
        if($id){
            $this->db->where('usuario_id', $id);
        }
        
        $this->db->where('estado', ESTADO_ACTIVO);
        $arr = $this->retornarMuchos();
        return $arr;
    }

    public function obtenerUsuarioPerfiles($id, $traerid = false) {
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
    public function obtenerUsuarioSucursales($id, $traerid = false) {
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
    public function obtenerUsuario($id = false, $estado = false, $texto_busqueda = false) {
        $this->db->select('u.*');
        $this->db->from('usuario u');
        if ($id) {
            $this->db->where('u.id', $id);
            return $this->retornarUno();
        }
        if ($estado) {
            $this->db->where('u.estado', $estado);
        }
        if ($texto_busqueda) {
            $this->db->where(" (UPPER(u.usuario) LIKE '%" . strtoupper($texto_busqueda) . "%' "
                    . "OR UPPER(u.nombre) LIKE '%" . strtoupper($texto_busqueda) . "%' "
                    . "OR UPPER(u.correo) LIKE '%" . strtoupper($texto_busqueda) . "%')");
        }

        $this->db->order_by('u.usuario', 'ASC');

        $conteo = $this->retornarConteo();
        $arr = $this->retornarMuchos();
        return array($arr, $conteo);
    }
    public function crearUsuario($obj) {
        
        $obj1 = $this->prepararobjeto($obj);
        foreach ($obj['sucursal_id'] as $new) {
            $obj1['sucursal_id'] = $new;
        }
        $id = $this->ingresar("usuario", $obj1, true);
        if ($id) {
            $dato_log = array(
                "usuario_id" => $id,
                "accion" => "creacion de usuario" . json_encode($obj),
            );
            $this->registrarLog("usuario_log", $dato_log);
        }

        return $id;
    }
    public function crearUsuarioPerfil($obj, $id) {
        $obj2 = $obj['perfil_id'];

        foreach ($obj2 as $new) {
            $dato_perfil = array(
                "usuario_id" => $id,
                "perfil_id" => $new,
                "estado" => ESTADO_ACTIVO,
                "accion" => "creacion de usuario_perfil" . json_encode($obj2),
            );

            $this->registrarLog("usuario_perfil", $dato_perfil, true);
        }
        //error_log(print_r($obj2, true));
        return $id;
    }
    public function crearUsuarioSucursal($obj, $id) {
        $obj2 = $obj['sucursal_id'];

        foreach ($obj2 as $new) {
            $dato_perfil = array(
                "usuario_id" => $id,
                "sucursal_id" => $new,
                "estado" => ESTADO_ACTIVO,
                "accion" => "creacion de usuario_sucursal" . json_encode($obj2),
            );

            $this->registrarLog("usuario_sucursal", $dato_perfil, true);
        }
        return $id;
    }
    public function actualizarUsuario($obj) {
        $obj1 = $this->prepararobjeto($obj);
        if(key_exists('sucursal_id', $obj)){
            foreach ($obj['sucursal_id'] as $new) {
                $obj1['sucursal_id'] = $new;
            }
        }
        
        //actualizo la informacion
        $id = $this->actualizar("usuario", $obj1, "id");
        //error_log(print_r($id, true)); 
        if ($id) {
            $dato_log = array(
                "usuario_id" => $obj1['id'],
                "accion" => "actualizacion de usuario" . json_encode($obj1),
            );
            $this->registrarLog("usuario_log", $dato_log);
        }
        return $id;
    }
    public function actualizarUsuarioPerfil($perfilespost, $obj) {
        $perfilviejo = $this->obtenerUsuarioPerfil($obj['id']);
        //elimino los perfiles de ese usuario
        if ($perfilviejo) {
            foreach ($perfilviejo as $new) {
                $dato_perfil = array(
                    "estado" => ESTADO_INACTIVO,
                );
                $this->actualizar("usuario_perfil", $dato_perfil, ["id" => $new->id]);
            }
        }
        // registro los perfiles nuevos
        if ($perfilespost) {
            foreach ($perfilespost as $new2) {
                $dato_perfil2 = array(
                    "usuario_id" => $obj['id'],
                    "perfil_id" => $new2,
                    "estado" => ESTADO_ACTIVO,
                    "accion" => "actualizacion de perfil" . json_encode($obj),
                );
                $this->registrarLog("usuario_perfil", $dato_perfil2);
            }
        }
        return $obj;
    }
    public function actualizarUsuarioSucursal($fincaspost, $obj) {
        $fincaviejo = $this->obtenerUsuarioSucursal($obj['id']);
        //elimino los fincas de ese usuario
        if ($fincaviejo) {
            foreach ($fincaviejo as $new) {
                $dato_sucursal = array(
                    "estado" => ESTADO_INACTIVO,
                );
                $this->actualizar("usuario_sucursal", $dato_sucursal, ["id" => $new->id]);
            }
        }
        // registro las fincas nuevos
        if ($fincaspost) {
            foreach ($fincaspost as $new2) {
                $dato_sucursal2 = array(
                    "usuario_id" => $obj['id'],
                    "sucursal_id" => $new2,
                    "estado" => ESTADO_ACTIVO,
                    "accion" => "actualizacion de sucursal" . json_encode($obj),
                );
                $this->registrarLog("usuario_sucursal", $dato_sucursal2);
            }
        }
        return $obj;
    }
    public function actualizarUsuarioPassword($id, $password) {
        $actualizado = $this->actualizar("usuario", array("id" => $id, "password" => $password), "id");

        if ($actualizado) {
            $dato_log = array(
                "usuario_id" => $id,
                "accion" => "Cambio de contrasenia",
            );
            $this->registrarLog("usuario_log", $dato_log);
        }
        return $id;
    }
    public function prepararobjeto($arr) {
        unset($arr['perfil_id']);
       
        return $arr;
    }

}