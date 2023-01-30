<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	
	/////////////////// ERR-MISS-CACHE //////////////////////////////////
	//establecer el limitador de caché a 'private'
	//session_cache_limiter('private');
	//$cache_limiter = session_cache_limiter();

	//establecer la caducidad de la Caché a 30 minutos
	//session_cache_expire(30);
	//$cache_expire = session_cache_expire();
	/////////////////// ERR-MISS-CACHE //////////////////////////////////

	session_id();
	session_start();

	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 1){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{
			if(isset($_POST["id_usuario"])){
				$id_usuario = $_POST["id_usuario"];
			}
?>
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
				<title>Reporte Diario</title>
				<style type="text/css">
					.bg-light, #fondo{
						background-color: #224875 !important;
					}
					.iconos-navbar-1{
						display: none !important;
					}
					a{
						transition-duration: 0.5s !important;
					}
					a:hover{
						color: #00A14C !important;
					}
					@media screen and (max-width: 500px) {
						span.titulo{
							font-size: 12px !important;
						}		
					}
					@media screen and (max-width: 990px) {
						.iconos-navbar-2{
							display: none !important;
						}	
						.iconos-navbar-1{
							display: inline !important;
						}	
					}
					@media screen and (max-width:  770px){
						#margeInput{
							margin-top: 15px;
						}						
					} 					
				</style>
				<script>
					function desactivar(){
						if(document.formulario.contrasena_automatica.checked){
							document.formulario.nueva_contrasena.disabled = true;
							document.formulario.validar_nueva_contrasena.disabled = true;	
						}
						else{
							document.formulario.nueva_contrasena.disabled = false;
							document.formulario.validar_nueva_contrasena.disabled = false;		
						}
						
					}
				</script>
			</head>
			<body class="d-flex flex-column min-vh-100">
				<header>
			   		<nav class="navbar navbar-expand-lg navbar-light bg-light x">
			   			<div class="container-fluid">
			   				<span class="navbar-brand mb-0 h1 titulo" style="color:#FFFFFF; font-size: 16px;">REPORTE DIARIO DE INSPECCI&Oacute;N DE OBRA<br><br><?php echo $_SESSION["nombre_persona"] . " " . $_SESSION["apellido_persona"] . "<br>" . $_SESSION["usuario"] . "<br>" . $_SESSION["cargo_persona"] . "<br><u><a href='cambiar_contrasena.php' style='color:#FFFFFF;'>Cambiar Contrase&ntilde;a</a></u>";?><br><br></span>
			   				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			   					<span class="navbar-toggler-icon"></span>
			   				</button>
			   				<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
			   					<div class="navbar-nav iconos-navbar-1">
			   						<a href="administracion.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF; font-weight: bolder;">
				   						<i class="fa-solid fa-house-chimney-crack"></i>&nbsp;Inicio
			                		</a>
			   						<a href="https://www.bogado.cl" target="_blank" class="nav-link" style="color:#FFFFFF;">
			   							<i class="fa-solid fa-globe"></i>&nbsp;www.Bogado.cl
			   						</a>
			   						<a href="https://www.bogado.cl/intranet" target="_blank" class="nav-link" style="color:#FFFFFF;">
			   							<i class="fa-solid fa-file"></i>&nbsp;Intranet
			   						</a>
			   						<button class="btn btn-primary w-100" type="button" onclick="document.location.href='../comunicacion/cerrar_sesion.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n</button>
			   					</div>  
			   					<div class="navbar-nav iconos-navbar-2">
			   						<a href="administracion.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF !important; font-weight: bolder; text-align: center;">
				   						<i class="fa-solid fa-house-chimney-crack"></i><br>Inicio
			                		</a>
			   						<a href="https://www.bogado.cl" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-globe"></i><br>www.Bogado.cl
			   						</a>
			   						<a href="https://www.bogado.cl/intranet" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-file"></i><br>Intranet
			   						</a>
			   						<button class="btn btn-primary w-100" type="button" onclick="document.location.href='../comunicacion/cerrar_sesion.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n</button>
			   					</div> 					
			   				</div>
			   			</div>
			   		</nav> 
				</header>
				<main>					
					<section>
						<div class="container">
							<form name="formulario" action="../comunicacion/changePassword.php" method="POST">								
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-12 col-lg-12 col-12">
										<label for=""><b>Modificar Contrase&ntilde;a</b>:</label>
									</div>									
								</div>								
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-8 col-lg-8 col-12">
										<div class="form-floating">
											<input type="password" class="form-control" id="floatingInputGrid" placeholder="Nueva Contrase&ntilde;a" name="nueva_contrasena" required>
											<label for="floatingInputGrid">Nueva Contrase&ntilde;a</label>
										</div>
									</div>									
								</div>
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-8 col-lg-8 col-12">
										<div class="form-floating">
											<input type="password" class="form-control" id="floatingInputGrid" placeholder="Validar Nueva Contrase&ntilde;a" name="validar_nueva_contrasena" required>
											<label for="floatingInputGrid">Validar Nueva Contrase&ntilde;a</label>
										</div>
									</div>									
								</div>
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-8 col-lg-8 col-12">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="" name="contrasena_automatica" onclick="desactivar();">
												Generar contraseña autom&aacute;ticamente.
											</label>
										</div>
									</div>									
								</div>
								<input type="hidden" name="identificador_usuario" value="<?php echo $id_usuario; ?>">
								<div class=" row justify-content-md-center">
									<div class="col-md">
										<div class="row mt-3 d-grid gap-1 col-6 mx-auto">
											<button class="btn btn-primary" type="submit" style="background-color: #224875;">Modificar</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</section>
				</main>
				<footer class="bg-light text-center text-lg-start mt-auto" style="height: 3.5rem;"> 
			  		<div class="text-center pt-3">
			    		<p class="text-white">@Bogado SpA.</p>
			  		</div>
				</footer>
			  	<script src="https://kit.fontawesome.com/f631b9c9e0.js" crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
				<script>
					var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});
					document.onreadystatechange = function () {
					  myModal.show();
					};
				</script>
			</body>
			</html>
<?php
		}
	}
	else{
		header("Location: ../comunicacion/cerrar_sesion.php");
		die();
	}
?>