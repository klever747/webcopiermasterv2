<?php
$newProduct =  $newProducto;
?>
<div class="ps-section--default">

    <div class="ps-section__header">

        <h3>Related products</h3>

    </div>

    <div class="ps-section__content">

        <div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">

            <?php foreach ($newProduct as $key => $item) : ?>

                <div class="ps-product">

                    <div class="ps-product__thumbnail">

                        <!--=====================================
                    Imagen del producto
                    ======================================-->

                        <a href="<?php echo base_url() . "producto/product" . $item->url_producto ?>">

                            <img src="<?php echo base_url() . "inicio/productos/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

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

                        <!--=====================================
                    Botones de acciones
                    ======================================-->

                        <ul class="ps-product__actions">

                            <li>
                                <a class="btn">
                                    <i class="icon-bag2"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url() . "producto/product" . $item->url_producto ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
                                    <i class="icon-eye"></i>
                                </a>
                            </li>

                            <li>
                                <a class="btn" data-toggle="tooltip" data-placement="top" title="Add to Whishlist">
                                    <i class="icon-heart"></i>
                                </a>
                            </li>

                        </ul>

                    </div>

                    <div class="ps-product__container">

                        <!--=====================================
                    nombre de la tienda
                    ======================================-->

                        <a class="ps-product__vendor" href="<?php echo base_url() . "producto/product" . $item->url_store ?>"><?php echo $item->nombre_store ?></a>


                        <div class="ps-product__content">

                            <!--=====================================
                        nombre del producto
                        ======================================-->

                            <a class="ps-product__title" href="<?php echo base_url() . "producto/product" . $item->url_producto ?>">
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

                                <span>(<?php

                                        if ($item->reviews_producto != null) {

                                            echo count(json_decode($item->reviews_producto, true));
                                        } else {

                                            echo 0;
                                        }

                                        ?>)</span>

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

                        <div class="ps-product__content hover">

                            <!--=====================================
                        El nombre del producto
                        ======================================-->

                            <a class="ps-product__title" href="<?php echo base_url() . "producto/product" . $item->url_producto ?>">

                                <?php echo $item->nombre_producto ?>

                            </a>

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

                </div>

            <?php endforeach ?>

        </div>
    </div>
</div> <!-- End Related products -->