<?php
$showcaseProducts = $categorias;
if (isset($urlParams[1])) {
    if (!is_numeric($urlParams[1])) {
        echo '<script> window.location = "' . base_url() . '"/inicio/productos/"' . $urlParams[0] . '" </script>';
    }
}
if($noString){
    echo '<script> window.location = "' . base_url() . '"/inicio/productos/"' . $urlParams[0] . '" </script>';

}
if($noEncontro){
    echo '<script> window.location = "' . base_url() . '"/inicio/productos/"' . $urlParams[0] . '" </script>';

}
?>

<!--=====================================
Load
======================================-->

<div id="showcase" class="ps-shopping ps-tab-root preloadFalse">

    <!--=====================================
	Shoping Header
	======================================-->

    <div class="ps-shopping__header">

        <p><strong> <?php echo $totalProducts ?></strong> Productos Encontrados</p>

        <div class="ps-shopping__actions">

            <select class="select2" data-placeholder="Sort Items" onchange="sortProduct(event)">

                <?php if (isset($urlParams[2])) : ?>

                    <?php if ($urlParams[2] == "new") : ?>

                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+new">Ordenar Nuevo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+latest">Ordenar Antiguo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+low">Ordenar Precio mas Bajo-Alto</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+high">Ordenar Precio mas Alto-Bajo</option>

                    <?php endif ?>

                    <?php if ($urlParams[2] == "latest") : ?>

                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+latest">Ordenar Antiguo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+low">Ordenar Precio mas Bajo-Alto</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+high">Ordenar Precio mas Alto-Bajo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+new">Ordenar Nuevo</option>

                    <?php endif ?>

                    <?php if ($urlParams[2] == "low") : ?>

                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+low">Ordenar Precio mas Bajo-Alto</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+high">Ordenar Precio mas Alto-Bajo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+new">Ordenar Nuevo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+latest">Ordenar Antiguo</option>


                    <?php endif ?>

                    <?php if ($urlParams[2] == "high") : ?>

                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+high">Ordenar Precio mas Alto-Bajo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+low">Ordenar Precio mas Bajo-Alto</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+new">Ordenar Nuevo</option>
                        <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+latest">Ordenar Antiguo</option>

                    <?php endif ?>

                <?php else : ?>

                    <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+new">Ordenar Nuevo</option>
                    <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+latest">Ordenar Antiguo</option>
                    <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+low">Ordenar Precio mas Bajo-Alto</option>
                    <option value="<?php echo $_SERVER["REQUEST_URI"] ?>+high">Ordenar Precio mas Alto-Bajo</option>

                <?php endif ?>

            </select>

            <div class="ps-shopping__view">

                <p>View</p>

                <ul class="ps-tab-list">

                    <?php if (isset($_COOKIE["tab"])) : ?>

                        <?php if ($_COOKIE["tab"] == "grid") : ?>

                            <li class="active" type="grid">
                                <a href="#tab-1">
                                    <i class="icon-grid"></i>
                                </a>
                            </li>

                            <li type="list">
                                <a href="#tab-2">
                                    <i class="icon-list4"></i>
                                </a>
                            </li>

                        <?php else : ?>

                            <li type="grid">
                                <a href="#tab-1">
                                    <i class="icon-grid"></i>
                                </a>
                            </li>

                            <li class="active" type="list">
                                <a href="#tab-2">
                                    <i class="icon-list4"></i>
                                </a>
                            </li>

                        <?php endif ?>

                    <?php else : ?>

                        <li class="active" type="grid">
                            <a href="#tab-1">
                                <i class="icon-grid"></i>
                            </a>
                        </li>

                        <li type="list">
                            <a href="#tab-2">
                                <i class="icon-list4"></i>
                            </a>
                        </li>

                    <?php endif ?>



                </ul>

            </div>

        </div>

    </div>

    <!--=====================================
	Shoping Body
	======================================-->

    <div class="ps-tabs">

        <!--=====================================
		Grid View
		======================================-->

        <?php if (isset($_COOKIE["tab"])) : ?>

            <?php if ($_COOKIE["tab"] == "grid") : ?>

                <div class="ps-tab active" id="tab-1">

                <?php else : ?>

                    <div class="ps-tab" id="tab-1">

                    <?php endif ?>

                <?php else : ?>

                    <div class="ps-tab active" id="tab-1">

                    <?php endif ?>

                    <div class="ps-shopping-product">

                        <div class="row3">
                            <?php if($showcaseProducts){?>
                                <?php foreach ($showcaseProducts as $key => $item) : ?>

                                    <!--=====================================
                                    Product
                                    ======================================-->

                                    <div class="col-lg-2 col-md-4 col-6">

                                        <div class="ps-product">

                                            <div class="ps-product__thumbnail">

                                                <!--=====================================
                                    Imagen del producto
                                    ======================================-->

                                                <a href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>">

                                                    <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

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
                                                        <a class="btn" data-toggle="tooltip" data-placement="top" title="Add to Cart">
                                                            <i class="icon-bag2"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
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

                                                    <a class="ps-product__title" href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>">

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

                                    </div><!-- End Product -->

                                <?php endforeach ?>
                            <?php }?>                        
                        </div>

                    </div>

                    <div class="ps-pagination">

                        <?php

                        if (isset($urlParams[1])) {

                            $currentPage = $urlParams[1];
                        } else {

                            $currentPage = 1;
                        }

                        ?>

                        <ul class="pagination" data-total-pages="<?php echo ceil($totalProducts / 6) ?>" data-current-page="<?php echo $currentPage ?>" data-url-page="<?php echo $_SERVER["REQUEST_URI"]  ?>">

                        </ul>

                    </div>

                    </div><!-- End Grid View-->

                    <!--=====================================
		            List View
		            ======================================-->

                    <?php if (isset($_COOKIE["tab"])) : ?>

                        <?php if ($_COOKIE["tab"] == "list") : ?>

                            <div class="ps-tab active" id="tab-2">

                            <?php else : ?>

                                <div class="ps-tab" id="tab-2">

                                <?php endif ?>

                            <?php else : ?>

                                <div class="ps-tab" id="tab-2">

                                <?php endif ?>

                                <div class="ps-shopping-product">
                                    <?php if($showcaseProducts){?>
                                        <?php foreach ($showcaseProducts as $key => $item) : ?>

                                            <!--=====================================
                                            Product
                                            ======================================-->

                                            <div class="ps-product ps-product--wide">

                                                <div class="ps-product__thumbnail">

                                                    <!--=====================================
                                                    Imagen del producto
                                                    ======================================-->

                                                    <a href="<?php echo base_url() . "producto/product/"  . $item->url_producto ?>">

                                                        <img src="<?php echo base_url() . "assets/productos/img/products/" . $item->url_categoria ?>/<?php echo $item->image_producto ?>" alt="<?php echo $item->nombre_producto ?>">

                                                    </a>

                                                </div>

                                                <div class="ps-product__container">

                                                    <div class="ps-product__content">

                                                        <!--=====================================
                                                        nombre del producto
                                                        ======================================-->

                                                        <a class="ps-product__title" href="<?php echo base_url() . "producto/product/" . $item->url_producto ?>">
                                                            <?php echo $item->nombre_producto ?>
                                                        </a>


                                                        <!--=====================================
                                                        El nombre de la tienda
                                                        ======================================-->



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
                                                        El resumen del producto
                                                        ======================================-->

                                                        <ul class="ps-product__desc">

                                                            <?php

                                                            $summary = json_decode($item->resumen_producto, true);

                                                            ?>

                                                            <?php foreach ($summary as $key => $value) : ?>

                                                                <li> <?php echo $value ?></li>

                                                            <?php endforeach ?>

                                                        </ul>

                                                    </div>

                                                    <div class="ps-product__shopping">

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

                                                        <a class="ps-btn">Add to cart</a>

                                                        <ul class="ps-product__actions">
                                                            <li><a href="<?php echo base_url() . "producto/product/"  . $item->url_producto ?>"><i class="icon-eye"></i>View</a></li>
                                                            <li><a class="btn"><i class="icon-heart"></i> Wishlist</a></li>
                                                        </ul>

                                                    </div>

                                                </div>

                                            </div> <!-- End Product -->

                                        <?php endforeach ?>
                                     <?php }?>                   
                                </div>

                                <div class="ps-pagination">

                                    <ul class="pagination" data-total-pages="<?php echo ceil($totalProducts / 6) ?>" data-current-page="<?php echo $currentPage ?>" data-url-page="<?php echo $_SERVER["REQUEST_URI"] ?>">

                                    </ul>

                                </div>

                                </div>

                                </div>

                            </div>