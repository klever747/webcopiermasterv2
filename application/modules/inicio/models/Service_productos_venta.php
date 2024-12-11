<?php

class Service_productos_venta extends My_Model
{

    public function obtenerNuevoTicket()
    {
        return (object) [
            'nombre' => '',
            'entidad_id' => '',
            'fecha_creacion' => '',
            'departamento_id' => '',
            'equipo_id' => '',
            'creacion_usu' => '',
            'resumen' => '',
            'estado' => ESTADO_ACTIVO,
        ];
    }

    public function obtenerListaCategorias()
    {
        $this->db->select('*');
        $this->db->from('categorias');
        //$this->db->where('u.sucursal_id', $sucursal_id);
        $categorias = $this->retornarMuchosSinPaginacion(true);
        return $categorias;
    }
    public function obtenerListaSubcategorias($nombre_subcategoria)
    {
        $this->db->select('*');
        $this->db->from('subcategorias sc');
        $this->db->where('sc.titulo_list_subcategoria', $nombre_subcategoria);
        $subcategorias = $this->retornarMuchosSinPaginacion(true);
        return $subcategorias;
    }
    public function obtenerProductosCategorias()
    {
        $this->db->select('*');
        $this->db->from('productos_store ps');
        $this->db->join('categorias c', 'ps.id_categoria_producto = c.id', 'left');
        $productos_categorias = $this->retornarMuchosSinPaginacion(true);
        return $productos_categorias;
    }
    /* Traer las ofertas del dia */
    public function obtenerOfertasCalientesDia()
    {
        $this->db->select('oferta_producto,stock_producto,galeria_producto,url_categoria,nombre_producto,oferta_producto,precio_producto,nombre_categoria,reviews_producto,url_producto');
        $this->db->from('productos_store ps');
        $this->db->join('categorias c', 'ps.id_categoria_producto = c.id', 'left');
        $productos_categorias = $this->retornarMuchosSinPaginacion(true);
        return $productos_categorias;
    }
    /*Obtener los 20 productos mas vendidos */
    public function productosMasVendidos(){
        $this->db->select('url_producto,image_producto,nombre_producto,oferta_producto,precio_producto,url_categoria');
        $this->db->from('productos_store ps');
        $this->db->join('categorias c', 'ps.id_categoria_producto = c.id', 'left');
        $this->db->order_by('ps.sales_producto','DESC');
        $this->db->limit(20,0);
        $productos_categorias = $this->retornarMuchosSinPaginacion(true);
        return $productos_categorias;
    }
    /*----------OBTENER LAS 6 CATEGORIAS MAS VISTAS---------- */
    public function categoriasMasVistas(){
        $this->db->select('imagen_categoria,nombre_categoria,url_categoria,id');
        $this->db->from('categorias c');
        $this->db->order_by('c.views_categoria','DESC');
        $this->db->limit(6,0);
        $categorias = $this->retornarMuchosSinPaginacion(true);
        return $categorias;
    }
    /*----------OBTENER LAS SUBCATEGORIAS POR ID---------- */
    public function subcategorias($id){
        $this->db->select('nombre_subcategoria,url_subcategoria');
        $this->db->from('subcategorias s');
        $this->db->where('s.id_categoria_subcategoria',$id);
        $subcategorias = $this->retornarMuchosSinPaginacion(true);
        return $subcategorias;
    }
    /*----------OBTENER PRODUCTOS POR ID---------- */
    public function productos($id){
        $this->db->select('vertical_slider_producto,nombre_producto,image_producto,stock_producto,oferta_producto,url_producto,reviews_producto,precio_producto');
        $this->db->from('productos_store ps');
        $this->db->where('ps.id_categoria_producto',$id);
        $this->db->order_by('ps.views_producto','DESC');
        $this->db->limit(6,0);
        $productos = $this->retornarMuchosSinPaginacion(true);
        return $productos;
    }
    /*----------OBTENER CATEGORIAS POR URL---------- */
    public function categoriasUrl($url){
        $this->db->select('*');
        $this->db->from('categorias c');
        $this->db->where('c.url_categoria',$url);
        $productos = $this->retornarUno(true);
        return $productos;
    }
    public function obtenerSucursalSel()
    {
        $this->db->select('s.id,s.nombre');
        $this->db->from('sucursal s');
        $this->db->where('s.estado', ESTADO_ACTIVO);
        //$this->db->where('u.sucursal_id', $sucursal_id);
        $sucursales = $this->retornarMuchosSinPaginacion(true);
        return $this->retornarSel($sucursales, "nombre", true);
    }
    public function obtenerEstadosTicket($tipo = 1)
    {
        $respuesta = false;
        switch ($tipo) {
            case 1:
                $respuesta = array(
                    array('id' => 'A', 'nombre' => 'Activo'),
                    array('id' => 'I', 'nombre' => 'Inactivo'),
                    array('id' => 'P', 'nombre' => 'En proceso'),
                    array('id' => 'C', 'nombre' => 'Concluido')
                );
                break;
            default:
                break;
        }
        return (!$respuesta ? $respuesta : json_decode(json_encode($respuesta), FALSE));
    }

    public function obtenerSelEstadoTicket()
    {
        $estadoSelect = $this->obtenerEstadosTicket(1);
        return $this->retornarSel($estadoSelect, "nombre");
    }
    /*----------------OBTENER SOLUCION DEL TECNICO CON ELTICKET ASIGNADO */
    public function obtenerSolucionTecnico($id_ticket = false)
    {
        $solucion = false;
        if ($id_ticket) {
            $this->db->select('tu.id, tu.ticket_id, tu.solucion, tu.respuesta, tu.usuario_id');
            $this->db->from('ticket_usuario tu');
            $this->db->where('tu.ticket_id', $id_ticket);
            $solucion = $this->retornarUno();
        }
        return $solucion;
    }
    /*---------------------ACTUALIZAR SOLUCION DEL TICKET----------------- */
    public function actualizarTicketSolucion($obj)
    {
        $id = $this->actualizar("ticket_usuario", $obj, "id", false);
        return $id;
    }
    public function obtenerTicket($id)
    {
        $ticket_encontrado = false;
        if ($id) {
            $this->db->select('t.id,t.nombre as nombre_ticket, t.resumen, t.fecha_creacion, t.fecha_cierre, tu.respuesta, tu.solucion, e.nombre as nombre_entidad,dep.nombre as nombre_departamento');
            $this->db->from('ticket t');
            $this->db->join('ticket_usuario tu', 't.id = tu.ticket_id', 'left');
            $this->db->join('entidad e', 't.entidad_id = e.id', 'left');
            $this->db->join('departamento dep', 't.departamento_id = dep.id', 'left');
            $this->db->where('t.id', $id);
            $ticket_encontrado = $this->retornarUno();
        }
        return $ticket_encontrado;
    }
    /*--------------------BUSCAR TICKETS PARA EXPORTAR EN EXCEL------------- */
    public function buscarTickets($ids)
    {
        if ($ids) {
            foreach ($ids as $k => $ticket_id) {
                $arr[$k] = $this->obtenerTicket($ticket_id);
            }
        }
        return $arr;
    }
}
