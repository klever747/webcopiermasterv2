
<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
    <div class="container">

        <div class="ph-item border-0">

            <div class="ph-col-6">

                <div class="ph-picture"></div>

            </div>

            <div class="ph-col-6">

                <div class="ph-picture"></div>

            </div>

        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-promotions preloadFalse">

    <div class="container">

        <div class="row2">

            <?php $productsDefaultBanner = array_slice($prodCategorias, 0,2); 
                foreach ($productsDefaultBanner as $key => $value): ?>

                <div class="col-md-6 col-12 ">
                    <a class="ps-collection" href="<?php echo base_url()."producto/product/".$value->url_producto ?>">
                        <img src="<?php  echo base_url()."assets/productos/img/products/". $value->url_categoria ?>/default/<?php echo $value->default_baner_producto ?>" alt="<?php echo $value->nombre_producto ?>">
                    </a>
                </div>
                
            <?php endforeach ?>

        </div>

    </div>

</div><!-- End Home Promotions-->