<?php 

/*=============================================
Recibir variable GET de cupones y convertirla en Cookie
=============================================*/
if(isset($_GET["coupon"])){

	if(isset($_COOKIE["couponsMP"])){
		
		$arrayCoupon = json_decode($_COOKIE["couponsMP"],true);

		foreach ($arrayCoupon as $key => $value) {
			
			if($value != $_GET["coupon"]){

				array_push($arrayCoupon, $_GET["coupon"]);
			}
		}

		setcookie("couponsMP", json_encode($arrayCoupon), time()+3600*24*7);

	}else{

		$arrayCoupon = array($_GET["coupon"]);
		setcookie("couponsMP", json_encode($arrayCoupon), time()+3600*24*7);

	}

}
/*=============================================
Traer toda la información del producto
=============================================*/

$item = $producto;

if($item == "N"){

	echo '<script>

        fncSweetAlert(
            "error",
            "Error: The product is not enabled",
            "'.$path.'"
        );

    </script>'; 

    return;

}

/*=============================================
Actualizar las vistas de producto
=============================================*/


$updateViewsProduct = $actualizacionProducto;

?>

<!--=====================================
Preload
======================================-->

<!-- <div id="loader-wrapper">
    <img src="img/template/loader.jpg">
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>   -->


<!--=====================================
Call to Action
======================================-->

<?=$call_to_action?>

<!--=====================================
Breadcrumb
======================================-->  

<?=$breadcrumb?>

<header class="header header--standard header--market-place-4" data-sticky="true">

    <!--=====================================
		Header TOP
		======================================-->

    <div class="header__top">

        <div class="container">

            <!--=====================================
				Social 
				======================================-->

            <div class="header__left">
                <ul class="d-flex justify-content-center">
                    <li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
                </ul>
            </div>



        </div><!-- End Container -->

    </div><!-- Header Top -->

    <!--=====================================
		Header Content
		======================================-->
<?= $header?>

</header>
<!--=====================================
Product Content
======================================--> 

<div class="ps-page--product">

    <div class="ps-container">

    	<!--=====================================
		Product Container
		======================================--> 

        <div class="ps-page__container">

    		<!--=====================================
			Left Column
			======================================--> 

            <div class="ps-page__left">

                <div class="ps-product--detail ps-product--fullwidth">

                	<!--=====================================
					Product Header
					======================================--> 

                    <div class="ps-product__header">

                    	<!--=====================================
						Gallery
						======================================--> 

						<?= $gallery?>

                        <!--=====================================
						Product Info
						======================================--> 

                      	<?= $product_info ?>

                    </div> <!-- End Product header -->

                	<!--=====================================
					Product Content
					======================================--> 
					
					<div class="ps-product__content ps-tab-root">

						<!--=====================================
						Bought Together
						======================================--> 

					    <?= $bought_together ?>

					    <!--=====================================
						Menu
						======================================--> 
						<?= $menu_product ?>		   

					</div><!--  End product content -->
                
                </div>

            </div><!-- End Left Column -->

            <!--=====================================
			Right Column
			======================================--> 

         	<?= $right_column ?>

        </div><!--  End Product Container -->

		<!--=====================================
		Customers who bought
		======================================--> 

		<?php //include "modules/customers-bought.php" ?>

        <!--=====================================
		Related products
		======================================--> 

     	<?= $related_product?>
    </div>

</div><!-- End Product Content -->