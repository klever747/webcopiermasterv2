
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Producto extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("inventario/service_inventario_producto");
    }

    /*     * ******************* PRODUCTO ********************* */

    public function productos()
    {
        $texto_busqueda = "";
        $listadoProductos = false;
        $estado_id = false;
        $cuantos = 0;
        $url = $this->api() . 'api/producto/findProductos';
        $method = 'GET';
        $fields = array();

        if ($this->input->post('btn_buscar') != null) {
            $texto_busqueda = $this->input->post('texto_busqueda');
            if ($texto_busqueda == null) {
                $texto_busqueda = 'noData';
            }
            $estado_id = $this->input->post('estado_id');
            $limite = $this->service_inventario_producto->formart($listadoProductos);
            $urlBusquedaText = $this->api() . 'api/producto/findbytext/' . $texto_busqueda . '/' . $estado_id . '/' . $limite['limit'] . '/' . $limite['offset'] . '';
            $texto_busqueda = $this->input->post('texto_busqueda');
            $estado_id = $this->input->post('estado_id');
            $listadoProductos = $this->request($urlBusquedaText, $method, $fields);
            // $listadoProductos = $this->service_ecommerce_producto->obtenerProductoss();
        } else {
            $listadoProductos = $this->request($url, $method, $fields);
        }
        if ($listadoProductos->status >= 400 || $listadoProductos->status >= 500) {
            $data['productos'] = false;
        } else {
            $data['productos'] = $listadoProductos->data->productos;
            $data['cuantos'] = $listadoProductos->data->cuantos;

            $data['regpp'] = 10;
            $data['pagina'] = $this->paginacion['pagina'];
            if (isset($data['cuantos'])) {
                $data['itemsPaginacion'] = $this->calcularPaginacion($data['cuantos']);
            }
        }
        $data['estado_id'] = $estado_id;

        $data['texto_busqueda'] = $texto_busqueda;

        $this->mostrarVista('productos.php', $data);
    }

    public function producto_nuevo()
    {
        $this->producto_obtener();
    }

    public function producto_obtener()
    {
        $proveedores = array();
        $data = array();
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $urlBusquedaText = $this->api() . 'api/producto/findbyid/' . $id;
            $data['operacion'] = false;
            $data['producto'] = $this->request($urlBusquedaText, 'GET', array());
            unset($data['producto']->date_add);
            $listadoProveedores = $this->buscarProveedores();
            if ($listadoProveedores) {
                $data['proveedores'] = $this->service_ecommerce_producto->obtenerProveedores($listadoProveedores);
            } else {
                $data['proveedores'] = false;
            }
            $data['operacion'] = '<i class="fas fa-pencil my-float"></i> Edicion de producto';
        } else {
            $listadoAlmacenes = $this->buscarAlmacenes();
            if ($listadoAlmacenes) {
                
               $data['almacenes'] = $this->service_inventario_producto->obtenerAlmacenes($listadoAlmacenes);
            } else {
                $data['proveedores'] = false;
            }

            $data['operacion'] = '<i class="fa fa-plus my-float"></i> Registro de nuevo producto';
            $data['producto'] = $this->service_inventario_producto->obtenerNuevoProducto();
        }




        $producto_det = $this->load->view('productos_edicion.php', $data, true);


        $respuesta = array("error" => (!$data['producto'] ? true : false), "respuesta" => $producto_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function buscarProveedores()
    {
        $texto_busqueda = 'noData';
        $estado_id = 'A';
        $urlBusquedaText = $this->api() . 'api/proveedor/findbytext/' . $texto_busqueda . '/' . $estado_id . '';
        $method = 'GET';
        $fields = array();
        $listadoProveedores = $this->request($urlBusquedaText, $method, $fields);
        return $listadoProveedores;
    }
    public function buscarAlmacenes(){
        $url = $this->api() . 'api/almacen/findAlmacen';
        $method = 'GET';
        $fields = array();
        $listadoAlmacenes = $this->request($url, $method, $fields);
        return $listadoAlmacenes;
    }
    public function producto_guardar()
    {
        $actualizacion = false;
        if ($this->input->post('id') != null) {
            $id = $this->input->post('id');
            $url = $this->api() . 'api/ticket/findbyid/' . $id;
            $obj = $this->request($url, 'GET', array());
        } else {
            $obj = $this->service_inventario_producto->obtenerNuevoProducto();
        }
        $arr = array();
        if ($obj) {

            foreach ($obj as $field => $value) {
                if (!(strpos(strtoupper($field), 'CREACION_') === 0 || strpos(strtoupper($field), 'ACTUALIZACION_') === 0 || strpos(strtoupper($field), 'INFO_') === 0)) {
                    $arr[$field] = $this->input->post($field);
                }
            }
            if ($this->input->post('id') != null) {
                $id = $this->input->post('id');
                $url = $this->api() . 'api/ticket/update/' . $id;
                unset($arr['date_add']);
                unset($arr['usuario_id']);
                unset($arr['codproducto']);
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'PUT', $fields);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
                } else {
                    $respuesta = 'Registro actualizado';
                }
            } else {
                $arr['creacion_usu'] = $this->session->id;
                $url = $this->api() . 'api/producto/create';
                $fields = json_encode($arr);
                $actualizacion = $this->request($url, 'POST', $fields);
                //$actualizacion = $this->service_ecommerce_producto->crearProducto($arr, true);
                if (!$actualizacion) {
                    $respuesta = 'Existe un problema durante la creaci&oacute;n';
                } else {
                    if ($actualizacion->status == 200) {
                        $respuesta = 'Registro creado';
                    } else {
                        $respuesta = 'No se registro el producto codigo 404';
                        $actualizacion = false;
                    }
                }
            }
        } else {
            $respuesta = 'No se encuentra el registro';
        }

        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function agregar_cantidad()
    {
        $id = $this->input->post('id');
        $data['operacion'] = '<i class="fa fa-plus my-float"></i> Agregar Cantidad Del Producto';
        $data['producto'] = (object) ['existencia' => '', 'precio' => '', 'codproducto' => $id, 'precio_proveedor' => ''];
        $producto_det = $this->load->view('producto_agregar_cantidad.php', $data, true);
        $respuesta = array("error" => (!$data['producto'] ? true : false), "respuesta" => $producto_det);

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function producto_guardar_cantidad()
    {
        if (
            $this->input->post('codproducto') != null && $this->input->post('existencia') != null &&
            $this->input->post('precio') != null && $this->input->post('precio_proveedor') != null
        ) {
            $id = $this->input->post('codproducto');
            $existencia = $this->input->post('existencia');
            $precio = $this->input->post('precio');
            $precio_proveedor = $this->input->post('precio_proveedor');
            $url = $this->api() . 'api/producto/actualizarPrecioProducto/' . $id;
            $arr['cantidad'] = $existencia;
            $arr['precio'] = $precio;
            $arr['codigo'] = $id;
            $arr['precio_proveedor'] = $precio_proveedor;
            $fields = json_encode($arr);
            $actualizacion = $this->request($url, 'PUT', $fields);
            if (!$actualizacion) {
                $respuesta = 'Existe un problema durante la actualizaci&oacute;n';
            } else {
                $respuesta = 'Registro actualizado';
            }
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => $respuesta);
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
    public function producto_eliminar()
    {
        $id = $this->input->post('id');
        $url = $this->api() . 'api/producto/deletelogic/' . $id;
        $actualizacion = $this->request($url, 'PUT', array());
        if (!$actualizacion) {
            $respuesta = 'Existe un problema durante la inactivaci&oacute;n';
        } else {
            $respuesta = 'Registro inactivado';
        }
        $respuesta = array("error" => !$actualizacion, "respuesta" => (!$actualizacion ? 'Existe un problema durante la inactivaci&oacute;n' : 'Registro inactivado'));
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}
