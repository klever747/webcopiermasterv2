<?php
/*=============================================
Traer toda la información del producto
=============================================*/

$item = $producto;
?>
<header class="header header--product header--sticky" data-sticky="true">

    <nav class="navigation">

        <div class="container">

            <article class="ps-product--header-sticky">

                <div class="ps-product__thumbnail">

                    <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                </div>

                <div class="ps-product__wrapper">

                    <div class="ps-product__content">

                        <a class="ps-product__title" href="#"><?php echo $item->nombre_producto ?></a>

                    </div>

                    <div class="ps-product__shopping">

                        <!--=====================================
                        El precio en oferta del producto
                        ======================================-->

                        <?php if ($item->oferta_producto != null) : ?>

                            <p class="ps-product__price sale text-danger">

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

                        <a class="ps-btn" > Add to Cart</a>

                    </div>

                </div>

            </article>

        </div>

    </nav>

</header>