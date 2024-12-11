<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function empresaDigital() {
        $data = array();
        $this->mostrarVista2('empresa_digital.php', $data,true);
    }
    function infraestructuraDigital() {
        $data = array();
        $this->mostrarVista2('infraestructura_digital.php', $data,true);
    }
    function outsourcing() {
        $data = array();
        
        $this->mostrarVista2('outsourcing.php', $data,true);
    }
    function cableadoEstructurado() {
        $data = array();
        $this->mostrarVista2('cableado_estructurado.php', $data,true);
    }
    function visionCorporativa(){
        $data = array();
        $this->mostrarVista2('vision_corporativa.php', $data,true);
    }
    function inforOutsourcing(){
        $data = array();
        $this->mostrarVista2('informacion_outsourcing.php', $data,true);
    }
    function nuestrosParthners(){
        $data = array();
        $this->mostrarVista2('nuestros_parthners.php', $data,true);
    }
    function nuestroTrabajo(){
        $data = array();
        $this->mostrarVista2('nuestro_trabajo.php', $data,true);
    }
    function responsabilidadSocial(){
        $data = array();
        $this->mostrarVista2('responsabilidad_social.php', $data,true);
    }
    function desarrolloSoftware(){
        $data = array();
       
        $this->mostrarVista2('desarrollo_software.php', $data,true);
    }
    function contactanos(){
        $data = array();
        $this->mostrarVista2('contactanos.php', $data,true);
    }
}

?>