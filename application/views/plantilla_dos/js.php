<script src="<?= base_url() . "assets/" ?>adminlte3/plugins/toastr/toastr.min.js"></script>
<script src="<?= base_url() . "assets/" ?>productos/js/main.js"></script>

<script>
    var loadingBtn = "<div class='spinner-border spinner-border-sm' role='status'><span class='sr-only'>Loading...</span></div>";
    $(document).on('click', '#btn_buscar', function(e) {
        $(this).val("presionado");
        $("#btn_buscar").html(loadingBtn);
        $("#btn_buscar").unbind('click');
    });
    var deshabilitarClick = 0;

    function unsoloclick(clase = false) {
        deshabilitarClick++;
        console.log("deshabilitarClick " + deshabilitarClick);
        if (deshabilitarClick === 1) {
            //        if (!$(clase).prop('disabled')) {
            if (clase) {
                $(clase).prop('disabled', true);
            }
            setTimeout(function() {
                console.log('quitamos lo deshabilitado js');
                if (clase) {
                    $(clase).removeAttr('disabled');
                }
                deshabilitarClick = 0;
            }, 1000);
            return true;
        } else {
            console.log("Se controla un clic innecesario");
            return false;
        }
    }

    function mostrarError($mensaje) {
        toastr.error($mensaje);
    }

    function mostrarExito($mensaje) {
        toastr.success($mensaje);
    }
    /*
    $(function() {

        $('#da-slider').cslider({
            autoplay: true,
            bgincrement: 450
        });

    });
    $(document).ready(function() {
        $('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });
    */
    function limpiarCampos(campos) {
        campos.forEach(function(campo, index) {
            $("#"+campo).val('');
        });
    }
</script>