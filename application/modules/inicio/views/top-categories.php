<?php 

/*=============================================
Traer las categorías más visitadas de mayor a menor
=============================================*/

$topCategories = $categorias_vistas;

?>

<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
     <div class="container">
    
        <div class="row">

            <?php 

            $blocks = [0,1,2,3,4,5];

            ?>

            <?php foreach ($blocks as $key => $value): ?>

            <div class="col-xl-2 col-lg-3 col-sm-4 col-6">
      
                <div class="ph-item">
                    
                    <div class="ph-col-12">
                        <div class="ph-picture"></div>
                    </div> 

                    <div class="ph-col-12">
                        
                        <div class="ph-row">
                            <div class="ph-col-12 big"></div>
                        </div>

                    </div>     

                </div>
   
            </div>
                
            <?php endforeach ?>           

        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-top-categories preloadFalse">

    <div class="container">

        <h3>Top de las categorias del mes</h3>

        <div class="row2">

            <?php foreach ($topCategories as $key => $value): ?>

            <div class="col-xl-2 col-lg-3 col-sm-4 col-6 ">
                <div class="ps-block--category">
                    <a class="ps-block__overlay" href="<?php echo base_url()."inicio/productos/".$value->url_categoria ?>"></a>
                    <img src="<?php echo base_url() . "assets/productos/img/categories/".$value->imagen_categoria ?>" alt="<?php echo $value->nombre_categoria ?>">
                    <p><?php echo $value->nombre_categoria ?></p>
                </div>
            </div>
                
            <?php endforeach ?>

        </div>
    </div>

</div><!-- End Top Categories -->