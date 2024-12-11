<?php

class Service_inventario_producto extends My_Model {

    public function obtenerNuevoProducto() {
        return (object) [
                    'titulo' => '',
                    'descripcion' => '',
                    'numero_parte' => '',
                    'estado' => ESTADO_ACTIVO,
                    'creacion_usu' => 1,
                    'unidad_medida' => '',
                    'clasificacion' => '',
                    'almacen_id' => '',
                    'precio' => '',
                    'cantidad' => '',
        ];
    }
    public function obtenerAlmacenes($almacenes){
        if($almacenes){
            $arrDatos= $almacenes->data->almacenes;
        }
        return $this->retornarSel($arrDatos, "nombre", true);
    }
    public function formart(){
        $arr = $this->retornarMuchosAPI();

        return $arr;
    }

}