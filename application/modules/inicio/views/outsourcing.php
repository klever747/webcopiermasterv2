<header>
    <div class="banner-area">
        <div class="single-banner">
            <div class="banner-img">
                <img src="<?= base_url() ?>assets/images/SERVICIO_OUTSOURCING.png" alt="">
            </div>
            <div class="banner-text-header">

            </div>
        </div>
        
        <div class="slider">
            <!---start-da-slider----->
            <div id="da-slider" class="banner-empresa-digital">
                <section>
                    <div>
                        <div class="elemento-left">
                            <div class="elemento-seccion">
                                <p>
                                    Servicio de Outsourcing
                                </p>
                            </div>
                            <div class="elemento-parrafo">
                                <p>
                                    Tenemos alternativas de arrendamiento de equipos de impresión y copiado, con los mejores costos del mercado. Paga solo lo que usas.
                                </p>
                            </div>
                        
                        </div>
                    </div>
                </section>

            </div>
            <!---//End-da-slider----->
        </div>

    </div>
</header>

<div class="main">
    <div class="wrap">
        <section class="seccion2">
            <div class="row2">
                <h2 class="section-heading">Servicio de Outsourcing</h2>
            </div>
            <div class="row2">
                <div class="column2">
                    <div class="card2">
                        <div class="icon-wrapper">
                            <i class="fas fa-file"></i>
                        </div>
                        <h3 class="h3">Servicio de Gestión Documental</h3>
                        <p class="p">Los equipos, mantenimiento y suministros, relacionados a tus necesidades de impresión ya no serán un gasto recurrente dentro de tu presupuesto, porque la gestión se resume en optimización y ahorro.</p>
                    </div>

                </div>
                <div class="column2">
                    <div class="card2">
                        <div class="icon-wrapper">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <h3 class="h3">Servicio de aplicaciones</h3>
                        <p class="p">Contamos con un sistema de monitoreo de cada uno de los equipos instalados en su empresa
                            permitiendo ver que equipo podria estar teniendo problemas a futuro y poder corregirlo.
                        </p>
                    </div>

                </div>
                <div class="column2">
                    <div class="card2">
                        <div class="icon-wrapper">
                            <i class="fas fa-print"></i>
                        </div>
                        <h3 class="h3">Equipamiento</h3>
                        <p class="p">Tu empresa no tendra que preocuparse por comprar equipos, nosotros contamos con los
                            mejores equipos del mercado y nos ajustamos a su presupuesto o a las necesidades que requieran
                        </p>
                        <a href="<?= base_url() ?>inicio/empresa/inforOutsourcing" class="boton_info">Mas Información</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        $('.banner-area').slick({
            infinite: true,
            slidesToShow: 1,
            dots: true,
            arrows: false,
            autoplay: true,
            speed: 0.01,
            fade: true,
        });
    });
</script>