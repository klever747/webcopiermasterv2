<?php

class Service_seguridad_perfil extends My_Model {

    public function obtenerNuevoPerfil() {
        return (object) [
                    'nombre' => '',
                    'estado' => ESTADO_ACTIVO,
        ];
    }

    public function existePerfil($objPerfil) {
        $this->db->select('p');
        $this->db->from('seguridad.perfil');
        $this->db->where('name', $objPerfil['name']);
        return $this->retornarUno();
    }

    public function obtenerPerfil($id = false, $estado = false, $texto_busqueda = false) {
        $this->db->select('*');
        $this->db->from('perfil p');

        if ($id) {
            $this->db->where('id', $id);
            return $this->retornarUno();
        }
        if ($estado) {
            $this->db->where('p.estado', $estado);
        }
        if ($texto_busqueda) {
            $this->db->where(" (UPPER(p.nombre) LIKE '%" . strtoupper($texto_busqueda) . "%')");
        }
        $this->db->order_by('p.nombre', 'ASC');

        $conteo = $this->retornarConteo();
        $arr = $this->retornarMuchos();

        return array($arr, $conteo);
    }

    public function obtenerPerfiles($estado = false) {
        $this->db->select('p.*');
        $this->db->from('seguridad.perfil p');
        if ($estado) {
            $this->db->where('estado', $estado);
        }
        $conteo = $this->retornarConteo();
        $arr = $this->retornarMuchos();

        return array($arr, $conteo);
    }

    public function crearPerfil($obj) {
        $id = $this->ingresar("perfil", $obj, true);
        if ($id) {
            $dato_log = array(
                "perfil_id" => $id,
                "accion" => "creacion de perfil" . json_encode($obj),
            );
            $this->registrarLog("perfil_log", $dato_log);
        }
        return $id;
    }

    public function actualizarPerfil($obj) {
        $id = $this->actualizar("perfil", $obj, "id");
        if ($id) {
            $dato_log = array(
                "perfil_id" => $obj['id'],
                "accion" => "actualizacion de perfil" . json_encode($obj),
            );
            $this->registrarLog("perfil_log", $dato_log);
        }
        return $id;
    }

    public function obtenerSelPerfil() {
        $this->db->select("*");
        $this->db->from('perfil');
        $this->db->where('estado', ESTADO_ACTIVO);
        $arrDatos = $this->retornarMuchosSinPaginacion();
        return $this->retornarSel($arrDatos, "nombre");
    }
    public function obtenerSelSucursal() {
        $sucursal = $this->session->userSucursalesId;
        $this->db->select("*");
        $this->db->from('sucursal');
        $this->db->where('estado', ESTADO_ACTIVO);
        
        $arrayfinca = explode(",", $sucursal);
        if (!in_array(FINCA_ROSAHOLICS_ID,$arrayfinca)){
            if(count($arrayfinca) > 1){
             $srt = "id in (".$sucursal.")";
           $this->db->where($srt);  
            }
        }
        
        $arrDatos = $this->retornarMuchosSinPaginacion();
        return $this->retornarSel($arrDatos, "nombre");
    }

}
