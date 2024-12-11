<div class="main">
    <div class="wrap">
        <section class="d-flex justify-content-center">
            <div class="card col-sm-6 p-3">
                <div class="mb-3">
                    <h4>Contáctanos</h4>
                </div>
                <div class="mb-2">
                    <?= form_open(base_url() . "inicio/contactanos/contacto", array("id" => "form_modal_edicion_variante")); ?>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" class="form-control" name="correo" id="correo" placeholder="empresa@info.com" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="empresa" id="empresa" placeholder="Empresa" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad" required>
                    </div>
                    <div class="mb-2">
                        <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Telefono" required>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Comentarios" id="comentarios" name="comentarios" required></textarea>
                    </div>


                    <?= form_close(); ?>
                    <div class="centrar_button">
                        <button class=" boton_mensaje btn-enviar-mensaje btn_buscar"><i class="fas fa-location-arrow"></i> Enviar Mensaje</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(document).on('click', '.btn-enviar-mensaje', function(e) {
        e.preventDefault();
        var campos = ["nombre", "correo", "empresa", "ciudad", "telefono", "comentarios"];
        $("#form_modal_edicion_variante").submit();
        var textoBtn = $(".btn_buscar").html();
        $(".btn_buscar").html(loadingBtn);
        $(".btn_buscar").attr('disabled', true);
        limpiarCampos(campos);
    });


    $(document).on('submit', '#form_modal_edicion_variante', function(e) {
        var textoBtn = $(".btn_buscar").html();
        e.preventDefault();
        e.stopImmediatePropagation();
        if (!unsoloclick()) {
            console.log("No hacemos el submit");
            return false;
        }

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                if (data.error) {
                    mostrarError(data.respuesta);
                    $(".btn_buscar").html(textoBtn);
                    $(".btn_buscar").attr('disabled', false);
                } else {
                    mostrarExito(data.respuesta);
                    $(".btn_buscar").html(textoBtn);
                    $(".btn_buscar").attr('disabled', false);
                }
            }
        });


    });
</script>