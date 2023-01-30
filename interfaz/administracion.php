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
	
	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 1){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{	
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();

			$usuarios = array();
			$id_usuarios = array();
			$query = "SELECT id_usuario, nombre_persona, apellido_persona, correo_persona FROM persona";
			$resultado = $nueva_conexion->ejecutarConsulta($query);
			while($filas = $nueva_conexion->obtenerFilas($resultado)){
				$id_usuarios[] = $filas[0];
				$usuarios[] = $filas[1] . " " . $filas[2] . "(" . $filas[3] . ")";
			}
			$nueva_conexion->cerrarConexion();			
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
			   				if(isset($_GET["val"]) && ($_GET["val"] == 101 || $_GET["val"] == 102 || $_GET["val"] == 103 || 104)){
			   					if($_GET["val"] == 101){
			   						$mensaje = "La contrase&ntilde;a se cambio correctamente y se envío un correo al administrador.";	
			   					}
			   					else if($_GET["val"] == 102){
			   						$mensaje = "Ha ocurrido un error, contacte a un administrador.";
			   					}
			   					else if($_GET["val"] == 104){
			   						$mensaje = "Su contrase&ntilde;a se ha cambiado correctamente.";
			   					}			   				
			   					else{
			   						$mensaje = "Se ha eliminado correctamente el usuario.";
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
			   					</div> 					
			   				</div>
			   			</div>
			   		</nav> 
				</header>
				<section style="width: 100%;">
					<div class="container">
						<div class="accordion accordion-flush" id="accordionFlushExample">
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingOne">
									<button class="accordion-button collapsed mt-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" style="background-color:#224875 !important; color:#FFFFFF !important;">
										<label class="w-100 text-center ps-4">Usuario</label>
									</button>
    							<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
   									<div class="accordion-body">
										<div class="d-grid gap-2">
											<button class="btn btn-primary button-menu" type="button" id="fondo" onclick="window.location='crear_usuario.php'">Crear Usuario</button>
											<button class="btn btn-primary button-menu" type="button" id="fondo" data-bs-toggle="modal" data-bs-target="#modalUsuarioModificar">Modificar Informaci&oacute;n de Usuario</button>
											<button class="btn btn-primary button-menu" type="button" id="fondo" data-bs-toggle="modal" data-bs-target="#modalUsuarioContrasena">Cambiar Contraseña de Usuario</button>
											<button class="btn btn-primary button-menu" type="button" id="fondo" data-bs-toggle="modal" data-bs-target="#modalUsuarioEliminar">Eliminar Usuario</button>		
										</div>
      								</div>
    							</div>
  							</div>
  							<div class="accordion-item mt-2">
  								<button class="btn btn-primary button-menu w-100" type="button" id="fondo" onclick="window.location='buscador.php'">
									Buscador
								</button>
    						</div>
  							<div class="accordion-item mt-2">
    							<button class="btn btn-primary button-menu w-100" type="button" id="fondo" onclick="window.location='../comunicacion/reporte.php'">
									Reporte
								</button>
							</div>										
						</div>
					</div>		 
				</section>
				<!-- Modal -->
				<div class="modal fade" id="modalUsuarioModificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				        		<h5 class="modal-title" id="exampleModalLabel">Reportes</h5>
				        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				      		</div>
				      		<div class="modal-body">
				        		<p>Seleccionar <b>usuario</b> a modificar:</p>
				        		<form action="modificar_usuario.php" method="POST">
				        			 <div class="row mt-3">
										<div class="mb-0 col-md">
											<select name="id_usuario" id="" class="form-select form-select-sm form-control" aria-label=".form-select-sm" required>
												<option value="" selected></option>
												<?php
													for($i=0;$i<count($usuarios);$i++){										
														echo "<option value=$id_usuarios[$i]>$usuarios[$i]</option>";
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
				<div class="modal fade" id="modalUsuarioContrasena" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				        		<h5 class="modal-title" id="exampleModalLabel">Reportes</h5>
				        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				      		</div>
				      		<div class="modal-body">
				        		<p>Seleccionar <b>usuario</b> a modificar:</p>
				        		<form action="passwordChange.php" method="POST">
				        			 <div class="row mt-3">
										<div class="mb-0 col-md">
											<select name="id_usuario" id="" class="form-select form-select-sm form control" aria-label=".form-select-sm" required>
												<option value="" selected></option>
												<?php
													for($i=0;$i<count($usuarios);$i++){										
														echo "<option value='$id_usuarios[$i]'>$usuarios[$i]</option>";
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
				<div class="modal fade" id="modalUsuarioEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				        		<h5 class="modal-title" id="exampleModalLabel">Reportes</h5>
				        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				      		</div>
				      		<div class="modal-body">
				        		<p>Seleccionar <b>usuario</b> a eliminar:</p>
				        		<form action="../comunicacion/eliminar_usuario.php" method="POST">
				        			 <div class="row mt-3">
										<div class="mb-0 col-md">
											<select name="id_usuario" id="" class="form-select form-select-sm form control" aria-label=".form-select-sm" required>
												<option value="" selected></option>
												<?php
													for($i=0;$i<count($usuarios);$i++){										
														echo "<option value='$id_usuarios[$i]'>$usuarios[$i]</option>";
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