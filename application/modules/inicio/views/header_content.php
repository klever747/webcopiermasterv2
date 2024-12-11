<div class="header__content">

    <div class="container">

        <div class="header__content-left">



            <!--=====================================
            Menú
            ======================================-->

            <div class="menu--product-categories">

                <div class="menu__toggle">
                    <i class="icon-menu"></i>
                    <span> Shop by Department</span>
                </div>

                <div class="menu__content">
                    <ul class="menu--dropdown">
                        <?php
                        foreach ($categorias_completas as $categoria) {


                        ?>
                            <li class="menu-item-has-children has-mega-menu">
                                <a href="<?php echo base_url() . "inicio/productos/" . $categoria['url_categoria'] ?>"><i class="<?php echo $categoria['icon_categoria'] ?>"></i> <?php echo $categoria['nombre_categoria'] ?></a>

                                <div class="mega-menu">

                                    <?php foreach ($categoria['titulo_lista_categoria'] as $id_cat => $titulo) {

                                    ?>
                                        <div class="mega-menu__column">
                                            <h4><?php echo $titulo ?> <span class="sub-toggle"></span></h4>
                                            <ul class="mega-menu__list">
                                                <?php if ($categoria['subcategorias'][$id_cat]) { ?>
                                                    <?php foreach ($categoria['subcategorias'][$id_cat] as $subcategoria) {

                                                    ?>

                                                        <li><a href="<?php echo base_url() . "inicio/productos/" . $subcategoria->url_subcategoria ?>"><?php echo $subcategoria->nombre_subcategoria ?></a>
                                                        </li>

                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>

                                </div>
                            </li>
                        <?php }  ?>
                    </ul>

                </div>

            </div><!-- End menu-->

        </div><!-- End Header Content Left-->

        <!--=====================================
    Search
    ======================================-->

        <div class="header__content-center">

            <form class="ps-form--quick-search">

                <input class="form-control inputSearch" type="text" placeholder="I'm shopping for...">

                <button type="button" class="btnSearch" path="<?php echo base_url() . "search/search/" ?>">Search</button>

            </form>

        </div>

        <div class="header__content-right">

            <div class="header__actions">

                <!--=====================================
                Wishlist
                ======================================-->

                <a class="header__extra" href="#">
                    <i class="icon-heart"></i><span><i>0</i></span>
                </a>

                <!--=====================================
                Cart
                ======================================-->

                <div class="ps-cart--mini">

                    <a class="header__extra" href="#">
                        <i class="icon-bag2"></i><span><i>0</i></span>
                    </a>

                    <div class="ps-cart__content">

                        <div class="ps-cart__items">

                            <div class="ps-product--cart-mobile">

                                <div class="ps-product__thumbnail">
                                    <a href="#">
                                        <img src="img/products/clothing/7.jpg" alt="">
                                    </a>
                                </div>

                                <div class="ps-product__content">
                                    <a class="ps-product__remove" href="#">
                                        <i class="icon-cross"></i>
                                    </a>
                                    <a href="product-default.html">MVMTH Classical Leather Watch In Black</a>
                                    <p><strong>Sold by:</strong> YOUNG SHOP</p>
                                    <small>1 x $59.99</small>
                                </div>

                            </div>

                            <div class="ps-product--cart-mobile">

                                <div class="ps-product__thumbnail">
                                    <a href="#">
                                        <img src="img/products/clothing/5.jpg" alt="">
                                    </a>
                                </div>

                                <div class="ps-product__content">
                                    <a class="ps-product__remove" href="#">
                                        <i class="icon-cross"></i>
                                    </a>
                                    <a href="product-default.html">Sleeve Linen Blend Caro Pane Shirt</a>
                                    <p><strong>Sold by:</strong> YOUNG SHOP</p>
                                    <small>1 x $59.99</small>
                                </div>

                            </div>

                        </div>

                        <div class="ps-cart__footer">

                            <h3>Sub Total:<strong>$59.99</strong></h3>
                            <figure>
                                <a class="ps-btn" href="shopping-cart.html">View Cart</a>
                                <a class="ps-btn" href="checkout.html">Checkout</a>
                            </figure>

                        </div>

                    </div>

                </div>


                <!--=====================================
                    Cuentas de usuario
                    ======================================-->

                <?php if (isset($_SESSION["user"])) : ?>

                    <div class="ps-block--user-header">
                        <div class="ps-block__left">

                            <?php if ($_SESSION["user"]->method_user == "direct") : ?>

                                <?php if ($_SESSION["user"]->picture_user == "") : ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/default/default.png">

                                <?php else : ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">

                                <?php endif ?>

                            <?php else : ?>

                                <?php if (explode("/", $_SESSION["user"]->picture_user)[0] == "https:") : ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="<?php echo $_SESSION["user"]->picture_user ?>">

                                <?php else : ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">

                                <?php endif ?>

                            <?php endif ?>

                        </div>
                        <div class="ps-block__right">
                            <a href="<?php echo $path ?>account&wishlist">My Acccount</a>
                        </div>
                    </div>


                <?php else : ?>

                    <!--=====================================
                    Login and Register
                    ======================================-->

                    <div class="ps-block--user-header">
                        <div class="ps-block__left">
                            <i class="icon-user"></i>
                        </div>
                        <div class="ps-block__right">
                            <a href="<?php echo base_url()."account&login" ?>">Login</a>
                            <a href="<?php echo base_url()."account&enrollment" ?>">Register</a>
                        </div>
                    </div>

                <?php endif ?>
            </div><!-- End Header Actions-->

        </div><!-- End Header Content Right-->

    </div><!-- End Container-->

</div><!-- End Header Content-->