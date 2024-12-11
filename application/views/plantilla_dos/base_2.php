<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>

<head>
	<title>Copiermaster |CyG</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="icon" href="<?= base_url() ."assets/"?>images/favicon.ico" type="image/png" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?= $header ?>
	<script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Lato:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
	<?= $menu ?>
	<?= $banner ?>
	<?= $soluciones ?>

	<?= $informacion ?>
	<?= $footer ?>
	<div class="container_wapp">
		<input type="checkbox" id="btn-mas">
		<div class="redes">
			<div class="titulo-redes">
				<div class="texto-p">
					<p>Ponte en contacto</p>
				</div>
				<div class="texto-clientes">
					<p>Con uno de nuestros asesores de cualquier sucursal.</p>
				</div>

			</div>
			<div class="contenedores_redes2">


				<div class="">
					<a target="_blank" href="<?= $api_whatsapp ?>" class="sucursal">
						<div><i class="fa fa-whatsapp"></i>
							<div class="suc-text1"> Matriz Shushufindi</div>
						</div>

					</a>
				</div>

			</div>
			
		</div>
		<div class="btn-mas">
			<label for="btn-mas" class="fa fa-whatsapp label-rigth"></label>
		</div>
	</div>
	<?= $includes_js ?>

</body>

</html>