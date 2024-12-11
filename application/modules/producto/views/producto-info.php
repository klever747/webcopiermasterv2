<?php
/*=============================================
Traer toda la información del producto
=============================================*/

$item = $producto;
?>
<div class="ps-product__info">

    <h1> <?php echo $item->nombre_producto ?></h1>

    <div class="ps-product__meta">

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

    </div>

    <!--=====================================
    El precio en oferta del producto
    ======================================-->

    <?php if ($item->oferta_producto != null) : ?>

        <h4 class="ps-product__price sale">

            <?php
            echo "$" . offerPrice(

                $item->precio_producto,
                json_decode($item->oferta_producto, true)[1],
                json_decode($item->oferta_producto, true)[0]

            );

            ?>

            <del> $<?php echo $item->precio_producto ?></del>

        </h4>

    <?php else : ?>

        <h4 class="ps-product__price"><?php echo "$" . $item->precio_producto ?></h4>

    <?php endif ?>

    <div class="ps-product__desc">

        <p>

            
            <!--=====================================
            Preguntar si el producto tiene Stock
            ======================================-->

            <?php if ($item->stock_producto == 0) : ?>

                Status:<strong class="ps-tag--out-stock"> Out of stock</strong>

            <?php else : ?>

                Status:<a href=""><strong class="ps-tag--in-stock"> In stock</strong></a>

            <?php endif ?>

        </p>

        <!--=====================================
        Resumen del producto
        ======================================-->

        <ul class="ps-list--dot">

            <?php

            $summary = json_decode($item->resumen_producto, true);

            ?>

            <?php foreach ($summary as $key => $value) : ?>

                <li> <?php echo $value ?></li>

            <?php endforeach ?>

        </ul>

        <!--=====================================
        Video del producto
        ======================================-->

        <?php if ($item->video_producto != null) : ?>

            <?php

            $video = json_decode($item->video_product, true);

            ?>

            <?php if ($video[0] == "youtube") : ?>

                <iframe class="mb-3" src="https://www.youtube.com/embed/<?php echo $video[1] ?>?rel=0&autoplay=0" height="360" frameborder="0" allowfullscreen></iframe>

            <?php else : ?>

                <iframe class="mb-3" src="https://player.vimeo.com/video/<?php echo $video[1] ?>" height="360" frameborder="0" allowfullscreen></iframe>

            <?php endif ?>

        <?php endif ?>

    </div>

    <div class="ps-product__variations">

        <!--=====================================
        Especificaciones del producto
        ======================================-->

        <?php if ($item->especificaciones_producto != null) : ?>


            <?php

            $spec = json_decode($item->especificaciones_producto, true);

            ?>

            <?php foreach ($spec as $key => $value) : ?>

                <?php if (!empty(array_keys($value)[0])) : ?>

                    <figure>

                        <figcaption><?php echo array_keys($value)[0] ?> <strong> Escoge una Opción</strong></figcaption>

                    </figure>

                <?php endif ?>

                <?php foreach ($value as $key => $i) : ?>

                    <?php foreach ($i as $key => $v) : ?>

                        <?php if (array_keys($value)[0] == "Color") : ?>

                            <div class="ps-variant round-circle mr-3 details <?php echo array_keys($value)[0] ?>" detailType="<?php echo array_keys($value)[0] ?>" detailValue="<?php echo $v ?>" style="background-color: <?php echo $v ?>; 
                                        width:30px; 
                                        height:30px; 
                                        cursor:pointer; 
                                        border:1px solid #bbb">
                                <span class="ps-variant__tooltip"><?php echo $v ?></span>
                            </div>

                        <?php else : ?>

                            <div class="ps-variant ps-variant--size details <?php echo array_keys($value)[0] ?>" detailType="<?php echo array_keys($value)[0] ?>" detailValue="<?php echo $v ?>">
                                <span class="ps-variant__tooltip"><?php echo $v ?></span>
                                <span class="ps-variant__size"><?php echo substr($v, 0, 3) ?></span>
                            </div>

                        <?php endif ?>

                    <?php endforeach ?>

                <?php endforeach ?>

            <?php endforeach ?>

        <?php endif ?>

    </div>

    <!--=====================================
    Validar ofertas del producto
    ======================================-->

    <?php

    $today = date("Y-m-d");

    if ($item->oferta_producto != null && $item->stock_producto != 0 && $today < date(json_decode($item->oferta_producto, true)[2])) : ?>

        <div class="ps-product__countdown">

            <figure>

                <figcaption> Don't Miss Out! This promotion will expires in</figcaption>

                <ul class="ps-countdown" data-time="<?php echo json_decode($item->oferta_producto, true)[2] ?>">

                    <li><span class="days"></span>
                        <p>Dias</p>
                    </li>

                    <li><span class="hours"></span>
                        <p>Horas</p>
                    </li>

                    <li><span class="minutes"></span>
                        <p>Minutos</p>
                    </li>

                    <li><span class="seconds"></span>
                        <p>Segundos</p>
                    </li>

                </ul>

            </figure>

            <figure>

                <figcaption>Sold Items</figcaption>

                <div class="ps-product__progress-bar ps-progress" data-value="<?php echo $item->stock_producto ?>">

                    <div class="ps-progress__value"><span></span></div>

                    <p><b><?php echo $item->stock_producto ?>/100</b> Sold</p>

                </div>

            </figure>

        </div>

    <?php endif ?>

    <div class="ps-product__shopping">

        <!--=====================================
        Controles para modificar la cantidad de compra del producto
        ======================================-->

        <figure>

            <figcaption>Quantity</figcaption>

            <div class="form-group--number quantity">

                <button class="up" onclick="changeQuantity($('#quant0').val(), 'up', <?php echo $item->stock_producto ?>, 0)">
                    <i class="fa fa-plus"></i>
                </button>

                <button class="down" onclick="changeQuantity($('#quant0').val(),  'down', <?php echo $item->stock_producto ?>, 0)">
                    <i class="fa fa-minus"></i>
                </button>

                <input id="quant0" class="form-control" type="text" value="1" readonly>

            </div>

        </figure>

        <a class="ps-btn ps-btn--black">Add to cart</a>

        <a class="ps-btn" >Buy Now</a>

        <div class="ps-product__actions">

            <a class="btn" >
                <i class="icon-heart"></i>
            </a>

        </div>

    </div>

    <div class="ps-product__specification">

        <a class="report" href="#">Report Abuse</a>

        <p><strong>SKU:</strong> SF1133569600-1</p>

        <p class="categories">

            <strong> Categories:</strong>

            <a href="<?php echo base_url()."inicio/productos/". $item->url_categoria ?>"><?php echo $item->nombre_categoria ?></a>,
            <a href="<?php echo base_url()."inicio/productos/". $item->url_subcategoria ?>"><?php echo $item->nombre_subcategoria ?></a>,
            <a href="<?php echo base_url()."inicio/productos/". $item->titulo_lista_producto ?>"><?php echo $item->titulo_lista_producto ?></a>

        </p>

        <p class="tags"><strong> Tags</strong>

            <?php

            $tags = json_decode($item->tags_producto, true);

            ?>

            <?php foreach ($tags as $key => $value) : ?>

                <a href="<?php echo base_url()."inicio/productos/". $value ?>"><?php echo $value ?></a>

            <?php endforeach ?>
        </p>

    </div>

    <div class="ps-product__sharing">

        <a class="facebook social-share" data-share="facebook" href="#">
            <i class="fab fa-facebook"></i>
        </a>

        <a class="twitter social-share" data-share="twitter" href="#">
            <i class="fab fa-twitter"></i>
        </a>

        <a class="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo base_url()."inicio/productos/". $item->url_producto ?>" target="_blank">
            <i class="fab fa-linkedin"></i>
        </a>

    </div>

</div> <!-- End Product Info -->