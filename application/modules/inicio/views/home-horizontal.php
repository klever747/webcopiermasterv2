
<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
   <!--  <div class="spinner-border text-muted my-5"></div> -->

   <div class="container">

       <div class="ph-item border-0">

            <div class="ph-col-4">
                
                <div class="ph-row">
                    
                    <div class="ph-col-10"></div>  

                    <div class="ph-col-10 big"></div>  

                    <div class="ph-col-6 big"></div>  

                    <div class="ph-col-6 empty"></div>  

                    <div class="ph-col-6 big"></div> 

                </div>

            </div>

            <div class="ph-col-8">

               <div class="ph-picture"></div> 

            </div>
            
        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-home-banner preloadFalse">
    <div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">

        <?php foreach ($prodCategorias as $key => $value): ?>

            <?php 

                $hSlider = json_decode($value->horizontal_slider_producto, true);

            ?>

            <div class="ps-banner--market-4" data-background="<?php echo base_url()."assets/productos/img/products/". $value->url_categoria ?>/horizontal/<?php echo  $hSlider["IMG tag"] ?>">
                <img src="<?php  echo base_url()."assets/productos/img/products/". $value->url_categoria ?>/horizontal/<?php echo  $hSlider["IMG tag"] ?>" alt="<?php echo $value->nombre_producto ?>">
                <div class="ps-banner__content">
                    <h4><?php echo $hSlider["H4 tag"] ?></h4>
                    <h3><?php echo $hSlider["H3-1 tag"] ?><br/> 
                        <?php echo $hSlider["H3-2 tag"] ?><br/> 
                        <p> <?php echo $hSlider["H3-3 tag"] ?> <strong>  <?php echo $hSlider["H3-4s tag"] ?></strong></p>
                    </h3>
                    <a class="ps-btn" href="<?php echo base_url()."inicio/productos/".$value->url_producto ?>"> <?php echo $hSlider["Button tag"] ?></a>
                </div>
            </div>
            
        <?php endforeach ?>
   
    </div>

</div><!-- End Home Banner-->
