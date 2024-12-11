<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Producto extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("productos/service_producto");
    }

    function productosMostrar()
    {

        $data = array();
        $data['noEncontro'] = false;
        $data['noString']  = false;
        $routesArray = explode("/", $_SERVER['REQUEST_URI']);

        if (!empty(array_filter($routesArray)[4])) {
            $urlParams = explode("&", array_filter($routesArray)[4]);
            $data['urlParams'] = $urlParams;
        }

        if (isset($urlParams[1])) {
            if (is_numeric($urlParams[1])) {
                $startAt = ($urlParams[1] * 6) - 6;
            }
        } else {
            $startAt = 0;
        }
        if (isset($urlParams[2])) {
            if (is_string($urlParams[2])) {
                switch ($urlParams[2]) {
                    case 'new':
                        $oderBy = 'id';
                        $orderMode = 'DESC';
                        break;
                    case 'latest':
                        $oderBy = 'id';
                        $orderMode = 'ASC';
                        break;

                    case 'low':
                        $oderBy = 'precio_producto';
                        $orderMode = 'ASC';
                        break;
                    case 'high':
                        $oderBy = 'precio_producto';
                        $orderMode = 'DESC';
                        break;
                    default:
                        $data['noEncontro'] = true;
                        break;
                }
            } else {
                $data['noString'] = true;
            }
        } else {
            $oderBy = 'id';
            $orderMode = 'DESC';
        }
        /*---------------------FILTRAR CATEGORIAS POR EL PARAMETRO DE LA URL--------------------------- */

        list($data['categorias'], $data['totalProducts']) = $this->service_producto->categoriasUrl($urlParams[0], $oderBy, $orderMode, $startAt);

        if ($data['categorias'] == false) {
            list($data['categorias'], $data['totalProducts']) = $this->service_producto->subcategoriasUrl($urlParams[0], $oderBy, $orderMode, $startAt);

            /*=============================================
	        Actualizar las vistas de subcategorías
	        =============================================*/
            if($data['categorias'] == true){
                $views = $data['categorias'][0]->views_subcategoria + 1;
                $id_subcategoria = $data['categorias'][0]-> id_subcategoria;
                $datos = array("id"=>$id_subcategoria, "views_subcategoria"=>$views);
                $tabla = 'subcategorias';
                $data['actualizacionCategoria'] = $this->service_producto->actualizarViews($datos, $tabla );
            }
           
        } else {
            /*=============================================
	        Actualizar las vistas de categorías
	        =============================================*/
            $views = $data['categorias'][0]->views_categoria + 1;
            $id_categoria = $data['categorias'][0]-> id_categoria;
            $datos = array("id"=>$id_categoria, "views_categoria"=>$views);
            $tabla = 'categorias';
            $data['actualizacionCategoria'] = $this->service_producto->actualizarViews($datos, $tabla );
        }


        $data['breadcrumb'] = $this->load->view('breadcrumb.php', $data, true);
        /*-------------TRAER LAS CATEGORIAS MAS VENDIDAS--------------------- */
        $data['categoriasMasVendidas'] = $this->service_producto->categoriasProductosMasVendidos($urlParams[0], $startAt, $oderBy, $orderMode);
        if ($data['categoriasMasVendidas'] == false) {

            $data['categoriasMasVendidas'] = $this->service_producto->subcategoriasProductosMasVendidos($urlParams[0], $startAt, $oderBy, $orderMode);
        }
        $data['best_sales_items'] = $this->load->view('best-sales-items.php', $data, true);

        /*-------------TRAER LOS PRODUCTOS MAS VISTOS DE LA CATEGORIA--------------------- */
        $data['categoriasMasVistas'] = $this->service_producto->categoriasProductosMasVistos($urlParams[0], $startAt, $oderBy, $orderMode);
        if ($data['categoriasMasVistas'] == false) {
            $data['categoriasMasVistas'] = $this->service_producto->subcategoriasProductosMasVistas($urlParams[0], $startAt, $oderBy, $orderMode);
        }

        $data['recommend_items'] = $this->load->view('recommend-items.php', $data, true);
        $data['showcase'] = $this->load->view('showcase.php', $data, true);
        $this->mostrarVista2('productos.php', $data, true);
    }
}
