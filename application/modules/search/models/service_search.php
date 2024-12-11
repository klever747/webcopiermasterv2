<?php

class Service_search extends My_Model
{

    public function productoUrl($url, $oderBy, $orderMode, $startAt)
    {
        $this->db->select('p.url_producto, c.url_categoria, p.image_producto, p.nombre_producto, p.stock_producto, p.oferta_producto, p.reviews_producto, p.precio_producto, c.views_categoria, c.nombre_categoria,c.id as id_categoria, sub.views_subcategoria, sub.nombre_subcategoria, sub.id as id_subcategoria, p.resumen_producto');
        $this->db->from('productos_store p');
        $this->db->join('categorias c', 'p.id_categoria_producto = c.id', 'left');
        $this->db->join('subcategorias sub', 'sub.id_categoria_subcategoria = c.id', 'left');
        if($url){
            $this->db->where(" (UPPER(p.nombre_producto) LIKE '%" . strtoupper($url) . "%' "
                    . "OR UPPER(p.titulo_lista_producto) LIKE '%" . strtoupper($url) . "%' "
                    . "OR UPPER(p.resumen_producto) LIKE '%" . strtoupper($url) . "%' "
                    . "OR UPPER(p.tags_producto) LIKE '%" . strtoupper($url) . "%')");
        }
        $this->db->order_by('p.' . $oderBy . '', $orderMode);
        $this->db->limit(12, $startAt);
        $conteo = $this->retornarConteo();
        $productos = $this->retornarMuchosSinPaginacion(true);
        return array($productos, $conteo);
    }
}
