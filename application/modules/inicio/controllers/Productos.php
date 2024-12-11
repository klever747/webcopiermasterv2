
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Productos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("inicio/service_productos_venta");
    }
    /*------------MODULO PARA LISTAR SUBCATEGORIAS------------*/
    public function equipo_nuevo()
    {
        $this->equipo_obtener();
    }
    public function categorias_obtener()
    {
        $data['categorias'] = $this->service_productos_venta->obtenerListaCategorias();
        foreach ($data['categorias'] as $key => $categoria) {
            $arrDatosCategorias[$key] = array(
                "url_categoria" => $categoria->url_categoria,
                "icon_categoria" => $categoria->icon_categoria,
                "nombre_categoria" => $categoria->nombre_categoria,
                "titulo_lista_categoria" => json_decode($categoria->titulo_lista_categoria),
                "subcategorias" => array(),
            );
            foreach ($arrDatosCategorias[$key]['titulo_lista_categoria'] as $key2 =>  $categorias_buscar) {
                $arrDatosCategorias[$key]['subcategorias'][$key2]= $this->service_productos_venta->obtenerListaSubcategorias($categorias_buscar);
            }
        }

        $data['prodCategorias'] = $this->service_productos_venta->obtenerProductosCategorias();
        $data['categorias_completas']=$arrDatosCategorias;
        $data['header_content'] = $this->load->view('header_content.php', $data, true);
        $data['header_mobile'] = $this->load->view('header_mobile.php', $data, true);
        $data['horizontal_slider'] = $this->load->view('home-horizontal.php', $data, true);
        $data['home_features'] = $this->load->view('features.php', $data, true);
        $data['default_banner'] = $this->load->view('default_banner.php', $data, true);
        $data['oferta_caliente'] = $this->service_productos_venta->obtenerOfertasCalientesDia();
        $data['deal_hot_today'] = $this->load->view('deal-hot-today.php', $data, true);
        $data['productos_vendidos'] = $this->service_productos_venta->productosMasVendidos();
        $data['top_20_best_seller'] = $this->load->view('top-20-best-seller.php', $data, true);
        $data['categorias_vistas'] = $this->service_productos_venta->categoriasMasVistas();
        $data['top_categories'] = $this->load->view('top-categories.php', $data, true);
        $data['section_grey'] = $this->load->view('section-grey.php', $data, true);
        $categorias_det = $this->mostrarVista2('productos_market.php', $data, true);
       
        //$categorias_det = $this->mostrarVista2('renovacion.php', $data, true);
        $respuesta = array("error" => (!$data['categorias'] ? true : false), "respuesta" => $categorias_det);
    }
    static function obtenerSubcategoriasPorId($id){
        $ci =& get_instance();
        $subcategorias =  $ci->service_productos_venta->subcategorias($id);
        return  $subcategorias;
    }
    static function obtenerProductosPorId($id){
        $ci =& get_instance();
        $productos =  $ci->service_productos_venta->productos($id);
        return  $productos;
    }
    static function obtenerCategoriasUrl($url){
        $ci =& get_instance();
        $categorias =  $ci->service_productos_venta->categoriasUrl($url);
        return  $categorias;
    }
    public function equipo_obtener()
    {
        $proveedores = array();
        $data = array();
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/equipo/findEquipos/' . $id;
            $data['operacion'] = false;
            $equipo = $this->request($urlBusquedaText, 'GET', array());
            unset($equipo->departamento_id);
            unset($equipo->creacion_fecha);
            unset($equipo->creacion_ip);
            $data['equipos'] =  $equipo;
            $data['equipos']->departamento_id = $this->input->post('departamento_id');
        } else {
            $data['equipos'] = false;
            $data['departamento_id'] = $this->input->post('departamento_id');
            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo equipo';
            $data['equipos'] = $this->service_entidad->obtenerNuevoEquipo($data['departamento_id']);
        }

        $equipo_det = $this->load->view('equipo_edicion.php', $data, true);


        $respuesta = array("error" => (!$data['equipos'] ? true : false), "respuesta" => $equipo_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}
