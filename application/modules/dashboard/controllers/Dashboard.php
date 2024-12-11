<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();

    }

    public function index() {
        $data['fechaactual'] = date('Y-m-d - Y-m-d');
        $data['reenviado'] = 'T';
        $data['store_id'] = 0;
        $data['rango_busqueda'] = '';
        $data['tipo_calendario'] = 0;
        $data['totales'] = false;
        $detalle = '';
        $data['detalle'] = $detalle;
        $data['claseBody'] = "hold-transition";

        $this->mostrarVista('dashboard.php', $data);
    }

}

?>
