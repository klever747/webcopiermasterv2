<?php

/*=============================================
Traer las categorías más visitadas de mayor a menor
=============================================*/
$topCategories = $categorias_vistas;
?>


<!--=====================================
Load
======================================-->

<div class="ps-section--gray preloadFalse">

    <div class="container">

        <?php foreach ($topCategories as $key => $value) : ?>

            <!--=====================================
    	    Products of category
    	    ======================================-->

            <div class="ps-block--products-of-category">

                <!--=====================================
    		    Menu subcategory
    		    ======================================-->

                <div class="ps-block__categories">

                    <h3><?php echo $value->nombre_categoria ?></h3>

                    <ul>

                        <!--=====================================
                        Traer las subcategorías según el id de la categoría
                        ======================================-->

                        <?php
                        $listSubcategories = Productos::obtenerSubcategoriasPorId($value->id);

                        ?>

                        <?php foreach ($listSubcategories as $index => $item) : ?>

                            <li><a href="<?php echo  base_url() ."inicio/productos/". $item->url_subcategoria ?>"><?php echo $item->nombre_subcategoria ?></a></li>

                        <?php endforeach ?>



                    </ul>

                    <a class="ps-block__more-link" href="<?php echo base_url() ."inicio/productos/". $value->url_categoria ?>">View All</a>

                </div>


                <?php

                $listProducts = Productos::obtenerProductosPorId($value->id);

                ?>

                <!--=====================================
    		    Vertical Slider Category
    		    ======================================-->

                <div class="ps-block__slider">

                    <div class="ps-carousel--product-box owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="7000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="500" data-owl-mousedrag="off">

                        <?php foreach ($listProducts as $index => $item) : ?>

                            <a href="<?php echo base_url() ."producto/product/". $item->url_producto ?>">

                                <img src="<?php echo base_url() . "assets/productos/img/products/" . $value->url_categoria ?>/vertical/<?php echo $item->vertical_slider_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                            </a>

                        <?php endforeach ?>


                    </div>

                </div>

                <!--=====================================
    		Block Product Box
    		======================================-->

                <div class="ps-block__product-box">

                    <!--=====================================
    			Product Simple
    			======================================-->

                    <?php foreach ($listProducts as $index => $item) : ?>

                        <div class="ps-product ps-product--simple">

                            <div class="ps-product__thumbnail">

                                <!--=====================================
                                Imagen del producto
                                ======================================-->

                                <a href="<?php echo base_url() ."producto/product/". $item->url_producto ?>">

                                    <img src="<?php echo base_url() . "assets/productos/img/products/" . $value->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                                </a>

                                <!--=====================================
                                    Descuento de oferta o fuera de stock
                                ======================================-->

                                <?php if ($item->stock_producto == 0) : ?>

                                    <div class="ps-product__badge out-stock">Out Of Stock</div>

                                <?php else : ?>

                                    <?php if ($item->oferta_producto != null) : ?>

                                        <div class="ps-product__badge">
                                            -

                                            <?php

                                           echo offerDiscount(

                                                $item->precio_producto,
                                                json_decode($item->oferta_producto, true)[1],
                                                json_decode($item->oferta_producto, true)[0]

                                            );

                                            ?>


                                            %

                                        </div>

                                    <?php endif ?>

                                <?php endif ?>

                            </div>

                            <div class="ps-product__container">

                                <div class="ps-product__content" data-mh="clothing">

                                    <!--=====================================
                                    Título del producto
                                    ======================================-->

                                    <a class="ps-product__title" href="<?php echo base_url() ."producto/product/". $item->url_producto ?>">

                                        <?php echo $item->nombre_producto ?>

                                    </a>

                                    <!--=====================================
                                    Las reseñas del producto
                                    ======================================-->

                                    <div class="ps-product__rating">

                                        <?php

                                        $reviews = averageReviews2(
                                            json_decode($item->reviews_producto, true)
                                        );

                                        ?>

                                        <select class="ps-rating" data-read-only="true">

                                            <?php

                                            if ($reviews > 0) {

                                                for ($i = 0; $i < 5; $i++) {

                                                    if ($reviews < ($i + 1)) {

                                                        echo '<option value="1">' . ($i + 1) . '</option>';
                                                    } else {

                                                        echo '<option value="2">' . ($i + 1) . '</option>';
                                                    }
                                                }
                                            } else {

                                                echo '<option value="0">0</option>';

                                                for ($i = 0; $i < 5; $i++) {

                                                    echo '<option value="1">' . ($i + 1) . '</option>';
                                                }
                                            }

                                            ?>

                                        </select>

                                        <span>(

                                            <?php

                                            if ($item->reviews_producto != null) {

                                                echo count(json_decode($item->reviews_producto, true));
                                            } else {

                                                echo 0;
                                            }

                                            ?>

                                            review's)</span>

                                    </div>

                                    <!--=====================================
                            El precio en oferta del producto
                            ======================================-->

                                    <?php if ($item->oferta_producto != null) : ?>

                                        <p class="ps-product__price sale">

                                            <?php
                                            echo "$" . offerPrice(

                                                $item->precio_producto,
                                                json_decode($item->oferta_producto, true)[1],
                                                json_decode($item->oferta_producto, true)[0]

                                            );

                                            ?>

                                            <del> $<?php echo $item->precio_producto ?></del>

                                        </p>

                                    <?php else : ?>

                                        <p class="ps-product__price"><?php echo "$" . $item->precio_producto ?></p>

                                    <?php endif ?>

                                </div>

                            </div>

                        </div> <!-- End Product Simple -->

                    <?php endforeach ?>


                </div><!-- End Block Product Box -->

            </div><!-- End Products of category -->

        <?php endforeach ?>

    </div><!-- End Container-->

</div><!-- End Section Gray-->