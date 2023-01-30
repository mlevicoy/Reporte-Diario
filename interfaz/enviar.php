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
			if(isset($_GET["val"])){
				$_SESSION["numero_reporte"] = $_GET["val"];
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
			   				<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
			   					<div class="navbar-nav iconos-navbar-1">
			   						<a href="menu.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF; font-weight: bolder;">
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
			   						<a href="menu.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF !important; font-weight: bolder; text-align: center;">
				   						<i class="fa-solid fa-house-chimney-crack"></i><br>Inicio
			                		</a>
			   						<a href="https://www.bogado.cl" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-globe"></i><br>www.Bogado.cl
			   						</a>
			   						<a href="https://www.bogado.cl/intranet" target="_blank" class="nav-link" style="color:#FFFFFF; text-align: center;">
			   							<i class="fa-solid fa-file"></i><br>Intranet
			   						</a>
			   						<button class="btn btn-primary w-100" type="button" onclick="document.location.href='../comunicacion/cerrar_sesion.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i><br>Cerrar Sesi&oacute;n</button>
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
						<?php
							$nombres = array();
							$correos = array();
							//Creamos una instancia
							$nuevaConexion = new Conexion();
							//Creamos una nueva conexión
							$nuevaConexion->crearConexion();
							//Consulta
							$query = "SELECT id_destinatario, nombre_destinatario, apellido_destinatario, correo_destinatario FROM destinatarios WHERE id_usuario = " . $_SESSION["id_usuario"];
							$resultado	= $nuevaConexion->ejecutarConsulta($query);
							if($resultado){
								if($nuevaConexion->obtenerFilasAfectadas() > 0){
									while($filas = $nuevaConexion->obtenerFilas($resultado)){
										$nombres[] = $filas[1] . " " . $filas[2];
										$correos[] = $filas[3];	
									}
								}
							}							
						?>
						<div class="container text-center table-responsive">
							<form action="../comunicacion/envio_correo.php" method="POST">  								
								<table class="table table-striped mt-3">
									<thead>
										<tr>
											<th class="text-start" scope="col" colspan="4"><b>Seleccionar Destinatario (s)</b>:</th>
										</tr>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Nombre</th>
											<th scope="col">Correo</th>										
										</tr
									</thead>
									<tbody>
										<div class="form-check">    	
										<?php
	  										for($i=0;$i<count($nombres);$i++){
	  											echo "<tr>";
	  											echo "<th scope=row><input type='checkbox' class='form-check-input' id='check".($i+1)."' name='destinatarios[]' value=".$correos[$i]."></th>";
	    										echo "<td><label class='form-check-label' for='check".($i+1)."'>".ucfirst(strtolower($nombres[$i]))."</label></td>";
	    										echo "<td><label>".$correos[$i]."</label></td>";
	    										echo "</tr>";
	  										}	  									
	  									?>    											  								
	    								</div>
	    							</tbody>
								</table>
								<div class="d-grid gap-1 col-6 mx-auto">
									<button type="submit" class="btn btn-primary mt-3" style="background-color: #224875">Enviar</button>								  
								</div>								
							</form>
						</div>
					</section>
				</article>
				<footer class="bg-light text-center text-lg-start mt-auto" style="height: 3.5rem;">
			  		<div class="text-center pt-3">
			    		<p class="text-white">@Bogado SpA.</p>
			  		</div>
				</footer>
			  	<script src="https://kit.fontawesome.com/f631b9c9e0.js" crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>	
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