<?php 


$topSalesProducts = $productos_vendidos;

$topSales = array();

/*=============================================
Organizar bloques de a 5 productos
=============================================*/

for($i = 0; $i < ceil(count($topSalesProducts)/4); $i++){

    array_push($topSales, array_slice($topSalesProducts, $i*4 , 4));

}

?>


<div class="col-xl-3 col-12 ">

    

    <aside class="widget widget_best-sale" data-mh="dealhot">

        <h3 class="widget-title">Top 20 Best Seller</h3>

        <div class="widget__content">

            <div class="container-fluid preloadTrue">

            <?php 

            $blocks = [0,1,2,3];

             ?>

            <?php foreach ($blocks as $key => $value): ?>

                <!--=====================================
                Preload
                ======================================-->           
                    
                <div class="ph-item border-0 p-0 mt-0">

                    <div class="ph-col-6">

                        <div class="ph-picture" style="height:50px"></div>

                    </div>

                    <div class="ph-col-6">

                        <div class="ph-row">

                            <div class="ph-col-8"></div>
                            <div class="ph-col-4 empty"></div>

                            <div class="ph-col-12"></div>
                            <div class="ph-col-12"></div>

                            <div class="ph-col-6"></div>
                            <div class="ph-col-6 empty"></div>    

                        </div>

                    </div>         

                </div>

                
            <?php endforeach ?>

            </div>
                 
            <!--=====================================
            Load
            ======================================-->


            <div class="owl-slider preloadFalse" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="false" data-owl-dots="false" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on">

                <?php foreach ($topSales as $key => $value): ?>
                    
                    <div class="ps-product-group">

                        <?php foreach ($value as $index=> $item): ?>

                            <!--=====================================
                            Product
                            ======================================-->  

                            <div class="ps-product--horizontal">

                                <div class="ps-product__thumbnail">
                                    <a href="<?php echo base_url()."producto/product/".$item->url_producto ?>">
                                        <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto?>" alt="">
                                    </a>
                                </div>

                                <div class="ps-product__content">

                                    <a class="ps-product__title" href="<?php echo base_url()."producto/product/".$item->url_producto ?>"><?php echo $item->nombre_producto ?></a>

                                    <!--=====================================
                                    El precio en oferta del producto
                                    ======================================-->   
                                    
                                    <?php if ($item->oferta_producto != null): ?>

                                        <p class="ps-product__price sale">
                                        
                                            <?php 
                                                echo "$".offerPrice(

                                                    $item->precio_producto, 
                                                    json_decode($item->oferta_producto,true)[1], 
                                                    json_decode($item->oferta_producto,true)[0] 

                                                );

                                            ?>

                                            <del> $<?php echo $item->precio_producto ?></del>

                                        </p>

                                    <?php else: ?>

                                        <p class="ps-product__price"><?php echo "$".$item->precio_producto ?></p>

                                     <?php endif ?>

                                </div>

                            </div><!-- End Product -->

                        <?php endforeach ?>

                    </div><!-- End Product Group -->

                <?php endforeach ?>

            </div>

        </div>

    </aside><!-- End Aside --> 

</div><!-- End Columns --> 