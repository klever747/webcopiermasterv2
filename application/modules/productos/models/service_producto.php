<?php

class Service_producto extends My_Model
{


    /*----------OBTENER CATEGORIAS POR URL---------- */
    public function categoriasUrl($url,$oderBy, $orderMode, $startAt)
    {
        $this->db->select('p.url_producto,c.url_categoria,p.image_producto,p.nombre_producto,p.stock_producto,p.oferta_producto,p.reviews_producto,p.precio_producto,c.views_categoria,c.nombre_categoria,c.id as id_categoria,p.resumen_producto');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        $this->db->where('c.url_categoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6,$startAt );
        $conteo = $this->retornarConteo();
        $categorias = $this->retornarMuchosSinPaginacion(true);
        return array($categorias,$conteo);
    }

    /*----------OBTENER SUBCATEGORIAS POR URL---------- */
    public function subcategoriasUrl($url, $oderBy, $orderMode, $startAt)
    {
        $this->db->select('p.url_producto,c.url_categoria,p.image_producto,p.nombre_producto,p.stock_producto,p.oferta_producto,p.reviews_producto,p.precio_producto,sub.views_subcategoria,sub.nombre_subcategoria,sub.id as id_subcategoria,p. resumen_producto');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        $this->db->where('sub.url_subcategoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6, $startAt);
        $conteo = $this->retornarConteo();
        $subcategorias = $this->retornarMuchosSinPaginacion(true);
        return array($subcategorias,$conteo);
    }
    /*----------OBTENER CATEGORIAS MAS VENDIDAS---------- */
    public function categoriasProductosMasVendidos($url, $startAt, $oderBy, $orderMode)
    {
        $this->db->select('*');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        $this->db->where('c.url_categoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6, $startAt);
        $productosCategoria = $this->retornarMuchosSinPaginacion(true);
        return $productosCategoria;
    }

    /*----------OBTENER SUBCATEGORIAS MAS VENDIDAS---------- */
    public function subcategoriasProductosMasVendidos($url, $startAt,$oderBy, $orderMode)
    {
        $this->db->select('*');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        $this->db->where('sub.url_subcategoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6, $startAt);
        $subcategoriasProductos = $this->retornarMuchos(true);
        return $subcategoriasProductos;
    }

    /*----------OBTENER LOS PRODUCTOS MAS VISTOS DE LA CATEGORIA---------- */
    public function categoriasProductosMasVistos($url, $startAt, $oderBy, $orderMode)
    {
        $this->db->select('*');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->where('c.url_categoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6, $startAt);
        $productos = $this->retornarMuchosSinPaginacion(true);
        return $productos;
    }

    /*----------OBTENER SUBCATEGORIAS MAS VISTAS---------- */
    public function subcategoriasProductosMasVistas($url, $startAt, $oderBy, $orderMode)
    {
        $this->db->select('*');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'p.id_subcategoria_producto = sub.id', 'left');
        $this->db->where('sub.url_subcategoria', $url);
        $this->db->order_by('p.'.$oderBy.'', $orderMode);
        $this->db->limit(6, $startAt);
        $productos = $this->retornarMuchos(true);
        return $productos;
    }

    /*---------ACTUALIZAR VIEWS ------------ */
    public function actualizarViews($datos, $tabla  ){
        $actualizacion = $this->actualizar($tabla , $datos );
        
        return $actualizacion;
    }
}
