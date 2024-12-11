<?php 
    $showcaseProducts = $categorias;

?>

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">
        
            <li><a href="<?php echo base_url()."inicio/productos/categorias_obtener"?>">Home</a></li>
            <?php if($showcaseProducts){?>
                <?php if (!empty($showcaseProducts[0]->nombre_categoria)): ?>
                

                    <li><?php echo $showcaseProducts[0]->nombre_categoria ?></li>

                <?php else: ?>

                    <li><?php echo $showcaseProducts[0]->nombre_subcategoria ?></li>
                    
                <?php endif ?>       

            </ul>
       <?php }?>             
    </div>

</div>