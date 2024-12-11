<?php 

$itemProd = $producto;
?>
<div class="ps-breadcrumb">

	<div class="container">

	    <ul class="breadcrumb">

	        <li><a href="<?php echo base_url()."inicio/productos/categorias_obtener/"?>">Home</a></li>

	        <li><a href="<?php echo base_url()."inicio/productos/".$itemProd->url_categoria ?>"><?php echo $itemProd->nombre_categoria ?></a></li>

	         <li><a href="<?php echo base_url()."inicio/productos/".$itemProd->url_subcategoria ?>"><?php echo $itemProd->nombre_subcategoria ?></a></li>

	        <li><?php echo $itemProd->nombre_producto ?></li>

	    </ul>

	</div>

</div>
