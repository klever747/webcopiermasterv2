<?php



$storeProduct =  $productoStore;



?>

<div class="ps-page__right d-block d-sm-none d-xl-block">

    <aside class="widget widget_product widget_features">

        <p><i class="icon-network"></i> Shipping worldwide</p>

        <p><i class="icon-3d-rotate"></i> Free 7-day return if eligible, so easy</p>

        <p><i class="icon-receipt"></i> Supplier give bills for this product.</p>

        <p><i class="icon-credit-card"></i> Pay online or when receiving goods</p>

    </aside>

    <aside class="widget widget_sell-on-site">

        <p><i class="icon-store"></i> Sell on MarketPlace?<a href="#"> Register Now !</a></p>

    </aside>

    <aside class="widget widget_same-brand">

        <h3>Same Store</h3>

        <div class="widget__content">

            <?php foreach ($storeProduct as $key => $item) : ?>

                <div class="ps-product">

                    <div class="ps-product__thumbnail">

                        <!--=====================================
                    Imagen del producto
                    ======================================-->

                        <a href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>">

                            <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                        </a>

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
                                <a href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
                                    <i class="icon-eye"></i>
                                </a>
                            </li>

                            <li>
                                <a class="btn">
                                    <i class="icon-heart"></i>
                                </a>
                            </li>

                        </ul>

                    </div>

                    <div class="ps-product__container">

                        <!--=====================================
                    nombre de la tienda
                    ======================================-->

                        <a class="ps-product__vendor" href="<?php echo base_url() . "inicio/productos/" . $item->url_store ?>"><?php echo $item->nombre_store ?></a>


                        <div class="ps-product__content">

                            <!--=====================================
                        nombre del producto
                        ======================================-->

                            <a class="ps-product__title" href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>">
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

                            <a class="ps-product__title" href="<?php echo base_url() . "inicio/productos/" . $item->url_producto ?>">

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

    </aside>

</div><!-- End Right Column -->