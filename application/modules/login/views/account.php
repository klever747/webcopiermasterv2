<!--=====================================
Breadcrumb
======================================-->

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="<?php base_url() ?>inicio/productos/categorias_obtener">Home</a></li>

            <li>Mi Cuenta</li>

        </ul>

        <?php if (isset($_SESSION["user"])) : ?>

            <a href="<?php echo $path ?>account&logout" class="float-right">Logout</a>

        <?php endif ?>

    </div>

</div>
<?php

if (isset($urlParams[1])) {

    if (
        $urlParams[1] == "enrollment" ||
        $urlParams[1] == "login" ||
        $urlParams[1] == "wishlist" ||
        $urlParams[1] == "my-shopping" ||
        $urlParams[1] == "new-store" ||
        $urlParams[1] == "my-store" ||
        $urlParams[1] == "my-sales" ||
        $urlParams[1] == "logout"
    ) {

        /*=============================================
        Filtrar el Ingreso con redes sociales
        =============================================*/

        if (isset($urlParams[2])) {

            if ($urlParams[2] == "facebook" || $urlParams[2] == "google") {

                $url = $path . "account&enrollment&" . $urlParams[2];


                $response = UsersController::socialConnect($url, $urlParams[2]);
            }
        }

        /*=============================================
        Preguntar si el usuario tiene una tienda creada
        =============================================*/

        if ($urlParams[1] == "my-store") {

            $select = "id_store";

            if (!isset($_SESSION["user"])) {

                echo '<script>

                    window.location = "' . $path . '";

                </script>';

                return;
            }

            $url = CurlController::api() . "stores?linkTo=id_user_store&equalTo=" . $_SESSION["user"]->id_user . "&select=" . $select;
            $method = "GET";
            $fields = array();
            $header = array();

            $response = CurlController::request($url, $method, $fields, $header);

            if ($response->status == 404) {

                $urlParams[1] = "new-store";
            }
        }
        
        switch ($urlParams[1]) {
            case 'login':
                ?>

                <?= $login ?>

                <?php
                break;
            case 'enrollment':
                ?>
                <?= $enrollment ?>

             <?php
                break;
        }

    } else {

        echo '<script>

            window.location = "' . base_url() . '";

        </script>';
    }
} else {

    echo '<script>

        window.location = "' . $path . '";

    </script>';
}

?>