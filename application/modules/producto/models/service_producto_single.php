<?php

class Service_producto_single extends My_Model
{

    public function obtenerProducto($url)
    {
        $this->db->select('p.id as id_producto, c.url_categoria,s.id as id_store,s.url_store,s.nombre_store,s.logo_store,s.abstract_store,p.url_producto, p.image_producto, p.oferta_producto, p.nombre_producto,p.precio_producto, c.nombre_categoria, sub.nombre_subcategoria,p.galeria_producto,p.reviews_producto,p.stock_producto,p.resumen_producto, p.video_producto, p.especificaciones_producto, p.titulo_lista_producto,p.views_producto,p.tags_producto,p.descripcion_producto,p.detalle_producto, sub.url_subcategoria');
        $this->db->from('productos_store p');
        $this->db->join('stores s', 'p.id_tienda_producto = s.id', 'left');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        if($url){
            $this->db->where('p.url_producto',$url);
        }
        $producto = $this->retornarUno(true);
        return $producto;
    }
     /*---------ACTUALIZAR VIEWS DEL PRODUCTO------------ */
     public function actualizarViews($datos, $tabla  ){
        $actualizacion = $this->actualizar($tabla , $datos );
        
        return $actualizacion;
    }

    public function obtenerProductoTituloLista($titulo_lista_producto)
    {
        $this->db->select('p.id as id_producto, c.url_categoria,s.id as id_store,s.url_store,s.nombre_store,s.logo_store,s.abstract_store,p.url_producto, p.image_producto, p.oferta_producto, p.nombre_producto,p.precio_producto, c.nombre_categoria, sub.nombre_subcategoria,p.galeria_producto,p.reviews_producto,p.stock_producto,p.resumen_producto, p.video_producto, p.especificaciones_producto, p.titulo_lista_producto,p.views_producto,p.tags_producto,p.descripcion_producto,p.detalle_producto, sub.url_subcategoria');
        $this->db->from('productos_store p');
        $this->db->join('stores s', 'p.id_tienda_producto = s.id', 'left');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        if($titulo_lista_producto){
            $this->db->where('p.titulo_lista_producto',$titulo_lista_producto);
        }
        $producto = $this->retornarMuchosSinPaginacion(true);
        return $producto;
    }

    public function obtenerProductoTienda($id_store)
    {
        $this->db->select('p.id as id_producto, c.url_categoria,s.id as id_store,s.url_store,s.nombre_store,s.logo_store,s.abstract_store,p.url_producto, p.image_producto, p.oferta_producto, p.nombre_producto,p.precio_producto, c.nombre_categoria, sub.nombre_subcategoria,p.galeria_producto,p.reviews_producto,p.stock_producto,p.resumen_producto, p.video_producto, p.especificaciones_producto, p.titulo_lista_producto,p.views_producto,p.tags_producto,p.descripcion_producto,p.detalle_producto, sub.url_subcategoria');
        $this->db->from('productos_store p');
        $this->db->join('stores s', 'p.id_tienda_producto = s.id', 'left');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        if($id_store){
            $this->db->where('p.id_tienda_producto',$id_store);
        }
        $this->db->limit(4,0);
        $producto = $this->retornarMuchosSinPaginacion(true);
        return $producto;
    }
}
