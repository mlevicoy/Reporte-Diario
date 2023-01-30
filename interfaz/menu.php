<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	require("../conexion/conexion.php");

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
	
	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 2){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{	
			$arreglo = array();
			//Creamos una instancia
			$nuevaConexion = new Conexion();
			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();
			//Consulta
			$query = "SELECT id_reporte FROM reporte where id_usuario = " . $_SESSION["id_usuario"];
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			if($resultado){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					while($filas = $nuevaConexion->obtenerFilas($resultado)){
						$arreglo[] = $filas[0];	
					}
				}
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
				</style>
			</head>
			<body class="d-flex flex-column min-vh-100">
				<header>
			   		<nav class="navbar navbar-expand-lg navbar-light bg-light x">
			   			<div class="container-fluid">
			   				<span class="navbar-brand mb-0 h1 titulo" style="color:#FFFFFF; font-size: 16px;">REPORTE DIARIO DE INSPECCI&Oacute;N DE OBRA<br><br><?php echo $_SESSION["nombre_persona"] . " " . $_SESSION["apellido_persona"] . "<br>" . $_SESSION["usuario"] . "<br>" . $_SESSION["cargo_persona"] . "<br><u><a href='cambiar_contrasena.php' style='color:#FFFFFF;'>Cambiar Contrase&ntilde;a</a></u>";?><br><br></span>
			   				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			   					<span class="navbar-toggler-icon"></span>
			   				</button>
			   				<?php
			   				if(isset($_GET["val"]) && ($_GET["val"] == 100 || $_GET["val"] == 104 || $_GET["val"] == 105)){
			   					if($_GET["val"] == 104){
			   						$mensaje = "Su contrase&ntilde;a se ha cambiado correctamente.";
			   					}
			   					else if($_GET["val"] == 100){
			   						$mensaje = "El <b>reporte N°" . $_SESSION["numero_reporte"] . "</b> se ha enviado correctamente.";
			   					}
								else if($_GET["val"] == 105){
			   						$mensaje = "El <b>reporte N°" . $_SESSION["numero_reporte"] . "</b> se ha eliminado correctamente.";
			   					}
			   				?>
			   					<!-- Modal -->
								<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Mensaje</h5>
								        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								      		</div>
								      		<div class="modal-body">
								        		<p><?php echo $mensaje; ?></p>
								      		</div>
								      		<div class="modal-footer">
								        		<button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>								        
								      		</div>
								    	</div>
								  	</div>
								</div>
			   				<?php
			   				}
			   				?>			   				
			   				<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
			   					<div class="navbar-nav iconos-navbar-1">
			   						<a href="#" class="nav-link active disabled" aria-current="page" href="#" style="color:#FFFFFF; font-weight: bolder;">
				   						<i class="fa-solid fa-house-chimney-crack"></i>&nbsp;Inicio
			                		</a>
			   						<a href="https://www.bogado.cl" target="_blank" class="nav-link" style="color:#FFFFFF;">
			   							<i class="fa-solid fa-globe"></i>&nbsp;www.Bogado.cl
			   						</a>
			   						<a href="https://www.bogado.cl/intranet" target="_blank" class="nav-link" style="color:#FFFFFF;">
			   							<i class="fa-solid fa-file"></i>&nbsp;Intranet
			   						</a>
			   						<button class="btn btn-primary w-100" type="button" onclick="document.location.href='../comunicacion/cerrar_sesion.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n</button>
			   						<!--<a href="#" class="nav-link btn btn-success" role="button" style="color:#FFFFFF;">
			   							<i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;Cerrar Sesi&oacute;n
			   						</a>-->
			   					</div>  
			   					<div class="navbar-nav iconos-navbar-2">
			   						<a href="#" class="nav-link active disabled" aria-current="page" href="#" style="color:#FFFFFF !important; font-weight: bolder; text-align: center;">
				   						<i class="fa-solid fa-house-chimney-crack"></i><br>Inicio
			                		</a>
			   						<a href="https://www.bogado.cl" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-globe"></i><br>www.Bogado.cl
			   						</a>
			   						<a href="https://www.bogado.cl/intranet" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-file"></i><br>Intranet
			   						</a>
			   						<button class="btn btn-primary" type="button" onclick="document.location.href='../comunicacion/cerrar_sesion.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n</button>
			   						<!--<a href="../comunicacion/cerrar_sesion.php" class="nav-link btn btn-success" role="button" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n
			   						</a>-->
			   					</div> 					
			   				</div>
			   			</div>
			   		</nav> 
				</header>
				<article style="width: 100%;">
					<section style="width: 100%;">
						<div class="container">
							<div class="pt-5 d-grid gap-2">
								<button class="btn btn-primary button-menu" type="button" id="fondo" onclick="window.location='formulario.php'">Generar Reporte Diario</button>					
								<button class="btn btn-primary button-menu" type="button" id="fondo" data-bs-toggle="modal" data-bs-target="#modalReporte">Modificar Reporte Diario</button>
								<button class="btn btn-primary button-menu" type="button" id="fondo" data-bs-toggle="modal" data-bs-target="#modalEliminarReporte">Eliminar Reporte Diario</button>								
								<button class="btn btn-primary button-menu" type="button" id="fondo" onclick="window.location='enviar_descargar.php'">Enviar / Descargar Reporte Diario</button>					
								<button class="btn btn-primary button-menu" type="button" id="fondo" onclick="window.location='destinatario.php'">Agregar Destinatarios</button>					
								<button class="btn btn-primary button-menu" type="button" id="fondo" onclick="window.location='eliminar_destinatario.php'">Eliminar Destinatarios</button>					
							</div>
						</div>		 
					</section>
				</article>
				<section>
					<!-- Modal -->
					<div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<h5 class="modal-title" id="exampleModalLabel">Reportes</h5>
					        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					      		</div>
					      		<div class="modal-body">
					        		<p>Seleccionar <b>reporte</b> a modificar:</p>
					        		<form action="modificar.php" method="POST">
					        			 <div class="row mt-3">
											<div class="mb-0 col-md">
												<select name="numero_reporte" id="" class="form-select form-select-sm form control" aria-label=".form-select-sm" required>
													<option value="" selected></option>
													<?php
														for($i=0;$i<count($arreglo);$i++){										
															echo "<option value='$arreglo[$i]'>$arreglo[$i]</option>";
														}
													?>																				
												</select>																								
											</div>
										</div>
										<div class="row pt-3 col-md-4 col-lg-4 col-4 mx-auto">
											<input type="submit" class="btn btn-primary" role="button" style="background-color: #224875;">
										</div>
									</form>		        		
					      		</div>
					      		<div class="modal-footer"></div>
					    	</div>
					  	</div>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="modalEliminarReporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<h5 class="modal-title" id="exampleModalLabel">Reportes</h5>
					        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					      		</div>
					      		<div class="modal-body">
					        		<p>Seleccionar <b>reporte</b> a eliminar:</p>
					        		<form action="../comunicacion/eliminar_reporte.php" method="POST">
										<div class="row mt-3">
											<div class="mb-0 col-md">
												<select name="numero_reporte" id="" class="form-select form-select-sm form control" aria-label=".form-select-sm" required>
													<option value="" selected></option>
													<?php
														for($i=0;$i<count($arreglo);$i++){										
															echo "<option value='$arreglo[$i]'>$arreglo[$i]</option>";
														}
													?>																				
												</select>																								
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">Si, eliminar</button>
										</div>
									</form>		        		
								</div>
							</div>
						</div>
					</div>
				</section>
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