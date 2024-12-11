<?php 
/*=============================================
Traer toda la información del producto
=============================================*/

$item = $producto;
?>
<div class="ps-product__thumbnail" data-vertical="true">

    <figure>

        <div class="ps-wrapper">

            <div class="ps-product__gallery" data-arrow="true">

                <?php 

                    $gallery = json_decode($item->galeria_producto,true);
                   
                ?>

                <?php foreach ($gallery as $index => $image): ?>

                <div class="item">
                    <a href="<?php echo base_url() . "assets/productos/img/products/" .$item->url_categoria ?>/gallery/<?php echo $image ?>">
                        <img src="<?php echo base_url() . "assets/productos/img/products/".$item->url_categoria ?>/gallery/<?php echo $image ?>" alt="<?php echo $item->nombre_producto ?>">
                    </a>
                </div>
                    
                <?php endforeach ?>

            </div>

        </div>

    </figure>

    <div class="ps-product__variants" data-item="4" data-md="4" data-sm="4" data-arrow="false">

        <?php foreach ($gallery as $index => $image): ?>

            <div class="item">
                <img src="<?php echo base_url() . "assets/productos/img/products/".$item->url_categoria ?>/gallery/<?php echo $image ?>" alt="<?php echo $item->nombre_producto ?>">
            </div>
            
        <?php endforeach ?>

    </div>

</div><!-- End Gallery -->