<?php

class Service_entidad extends My_Model
{

    public function obtenerNuevoEntidad()
    {
        return (object) [
            'nombre' => '',
            'descripcion' => '',
            'creacion_usu' => '',
            'sucursal_id' => '',
            'estado' => ESTADO_ACTIVO,
        ];
    }
    public function formart()
    {
        $arr = $this->retornarMuchosAPI();

        return $arr;
    }
    public function obtenerEntidades($entidades){
        if($entidades){
            $arrDatos= $entidades->data->entidades;
        }
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    public function obtenerDepartamentos($departamentos){
        if($departamentos){
            $arrDatos= $departamentos->data->departamentos;
        }
        return $this->retornarSel($arrDatos, "nombre", false);
    }
    public function obtenerEquipos($equipos){
        if($equipos){
            $arrDatos= $equipos->data->equipos;
        }
        return $this->retornarSel($arrDatos, "equipo", false);
    }
    /**---------------MODULO DE DEPARTAMENTOS------------ */
    public function obtenerNuevoDepartamento($entidad_id = false)
    {
        return (object) [
            'nombre' => '',
            'estado' => ESTADO_ACTIVO,
            'entidad_id'=>$entidad_id
        ];
    }
    /**----------------MODULO DE EQUIPOS ---------------- */
    public function obtenerNuevoEquipo($departamento_id = false)
    {
        return (object) [
            'marca' => '',
            'serie' => '',
            'modelo' => '',
            'contador' => '',
            'departamento_id'=>$departamento_id,
            'ip_equipo'=>'',
            'estado' => ESTADO_ACTIVO,  
        ];
    }
}
