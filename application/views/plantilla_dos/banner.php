<header>
    <div class="">

        <div class="contiene-video">
            <video class="video-data" src="<?= base_url() ?>/assets/videos/copiermasterpresentacion.mp4" autoplay muted loop  ></video>
        </div>
    </div>
</header>
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