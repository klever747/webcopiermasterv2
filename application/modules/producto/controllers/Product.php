<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("producto/service_producto_single");
        $this->load->model("inicio/service_productos_venta");
    }

    function mostrarProducto()
    {

        $data = array();
        $routesArray = explode("/", $_SERVER['REQUEST_URI']);

        $data['categorias_completas'] = $this->mostrarHeader();

        if (!empty(array_filter($routesArray)[4])) {
            $urlParams = explode("&", array_filter($routesArray)[4]);
            $data['urlParams'] = $urlParams;
        }
        /*=============================================
	        TRAER EL PRODUCTO DE ACUERDO A LA URL QUE SE ENVIE
	        =============================================*/
        $data['producto'] = $this->service_producto_single->obtenerProducto($urlParams[0]);

        /*=============================================
	       ACTUALIZAR LA VISTA DEL PRODUCTO A VER
	    =============================================*/
        if($data['producto'] == true){
            $views = $data['producto']->views_producto + 1;
            $id_producto = $data['producto']-> id_producto;
            $datos = array("id"=>$id_producto, "views_producto"=>$views);
            $tabla = 'productos_store';
            $data['actualizacionProducto'] = $this->service_producto_single->actualizarViews($datos, $tabla );
        }
        $data['header'] = $this->load->view('inicio/header_content.php', $data, true);
        $data['call_to_action'] = $this->load->view('call-to-action.php', $data, true);
        $data['breadcrumb'] = $this->load->view('breadcrumb.php', $data, true);
        $data['gallery'] = $this->load->view('gallery.php', $data, true);
        $data['product_info'] = $this->load->view('producto-info.php', $data, true);
        /*==========================================================================
	       TRAER DATOS DEL PRODUCTO CON REFERENTE AL TITULO DE LA LSITA DEL PRODUCTO
	    ============================================================================*/
        $data['newProducto'] = $this->service_producto_single->obtenerProductoTituloLista($data['producto']-> titulo_lista_producto);
        $data['bought_together'] = $this->load->view('bought-together.php', $data, true);
        $data['menu_product'] = $this->load->view('menu-product.php', $data, true);
        /*==========================================================================
	       TRAER DATOS DEL PRODUCTO CON A LA TIENDA CON SUS PRODUCTOS QUE DISPONE
	    ============================================================================*/
        $data['productoStore'] = $this->service_producto_single->obtenerProductoTienda($data['producto']-> id_store);
        $data['right_column'] = $this->load->view('right-column.php', $data, true);
        $data['related_product'] = $this->load->view('related-product.php', $data, true);
        $this->mostrarVista2('producto-single.php', $data, true);
    }
    public function mostrarHeader(){
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
        return $arrDatosCategorias;
    }
}
