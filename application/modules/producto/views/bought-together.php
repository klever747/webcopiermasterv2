<?php

/*=============================================
Traer toda la información del producto
=============================================*/

$item = $producto;
$newProduct =  $newProducto;

?>

<?php if (count($newProduct) > 1) : ?>


    <div class="ps-block--bought-toggether">

        <h4>Frequently Bought Together</h4>

        <div class="ps-block__content">

            <div class="ps-block__items">

                <!--=====================================
            Current Product
            ======================================-->

                <div class="ps-block__item">

                    <div class="ps-product ps-product--simple">

                        <div class="ps-product__thumbnail">

                            <!--=====================================
                        Imagen del producto
                        ======================================-->

                            <a href="<?php echo base_url() . "producto/product" . $item->url_producto ?>">

                                <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                            </a>

                        </div>

                        <div class="ps-product__container">

                            <div class="ps-product__content">

                                <!--=====================================
                            nombre del producto
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
                                        $price1 = offerPrice(

                                            $item->precio_producto,
                                            json_decode($item->oferta_producto, true)[1],
                                            json_decode($item->oferta_producto, true)[0]

                                        );
                                        echo "$" . $price1;

                                        ?>

                                        <del> $<?php echo $item->precio_producto ?></del>

                                    </p>

                                <?php else : ?>

                                    <?php $price1 = $item->precio_producto  ?>

                                    <p class="ps-product__price"><?php echo "$" . $item->precio_producto ?></p>

                                <?php endif ?>

                            </div>

                        </div>

                    </div>

                </div>

                <!--=====================================
            New Product
            ======================================-->


                <?php foreach ($newProduct as $key => $value) : ?>

                    <?php if ($value->id_producto != $item->id_producto) : ?>

                        <div class="ps-block__item">

                            <div class="ps-product ps-product--simple">

                                <div class="ps-product__thumbnail">

                                    <!--=====================================
                                            Imagen del producto
                                            ======================================-->

                                    <a href="<?php echo base_url() . "producto/product" .  $value->url_producto ?>">

                                        <img src="<?php echo base_url() . "assets/productos/img/products/" . $value->url_categoria ?>/<?php echo $value->image_producto ?>" alt="<?php echo $value->nombre_producto ?>">

                                    </a>

                                </div>

                                <div class="ps-product__container">

                                    <div class="ps-product__content">

                                        <!--=====================================
                                                nombre del producto
                                                ======================================-->

                                        <a class="ps-product__title" href="<?php echo base_url() . "producto/product" . $value->url_producto ?>">
                                            <?php echo $value->nombre_producto ?>
                                        </a>

                                        <!--=====================================
                                                El precio en oferta del producto
                                                ======================================-->

                                        <?php if ($value->oferta_producto != null) : ?>

                                            <p class="ps-product__price sale">

                                                <?php
                                                $price2  =offerPrice(

                                                    $item->precio_producto,
                                                    json_decode($item->oferta_producto, true)[1],
                                                    json_decode($item->oferta_producto, true)[0]

                                                );
                                                echo "$" . $price2;

                                                ?>

                                                <del> $<?php echo $value->precio_producto ?></del>

                                            </p>

                                        <?php else : ?>

                                            <?php $price2 = $value->precio_producto  ?>

                                            <p class="ps-product__price"><?php echo "$" . $value->precio_producto ?></p>

                                        <?php endif ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="ps-block__item ps-block__total">

                            <p>Total Price:<strong> $<?php echo $price1 + $price2  ?></strong></p>

                            <a class="ps-btn" >Add All to cart</a>
                            <a class="ps-btn ps-btn--gray ps-btn--outline" >Add All to whishlist</a>

                        </div>

            </div>

        </div>

    </div>

    <?php return; ?>

<?php endif ?>

<?php endforeach ?>

<?php endif ?>