	<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	require("../conexion/conexion.php");

	/////////////////// ERR-MISS-CACHE //////////////////////////////////
	//establecer el limitador de caché a 'private'
	session_cache_limiter('private');
	$cache_limiter = session_cache_limiter();

	//establecer la caducidad de la Caché a 30 minutos
	session_cache_expire(30);
	$cache_expire = session_cache_expire();
	/////////////////// ERR-MISS-CACHE //////////////////////////////////

	session_id();
	session_start();

	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 3){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();

			if(isset($_POST["buscador"]) && $_POST["buscador"] == 90){
				$filtro_usuario = $_POST["tipo_usuario"];

				$id_usuario = array();
				$nombre_usuario = array();
				$apellido_usuario = array();
				$correo_usuario = array();

				//Buscamos los usuario
				if($filtro_usuario == -1){
					//$query = "SELECT id_usuario, nombre_persona, apellido_persona, correo_persona FROM persona";	
					$query = "SELECT persona.id_usuario, persona.nombre_persona, persona.apellido_persona, persona.correo_persona FROM persona INNER JOIN usuario ON persona.id_usuario = usuario.id_usuario WHERE usuario.id_tipo != 1 AND usuario.id_tipo != 3";
				}
				else{
					//$query = "SELECT id_usuario, nombre_persona, apellido_persona, correo_persona FROM persona WHERE id_usuario = " . $filtro_usuario;		
					$query = "SELECT persona.id_usuario, persona.nombre_persona, persona.apellido_persona, persona.correo_persona FROM persona INNER JOIN usuario ON persona.id_usuario = usuario.id_usuario WHERE usuario.id_tipo != 1 AND usuario.id_tipo != 3 AND persona.id_usuario = " . $filtro_usuario;
				}
				$resultado = $nueva_conexion->ejecutarConsulta($query);
				if($resultado){
					while($filas = $nueva_conexion->obtenerFilas($resultado)){
						$id_usuario[] = $filas[0];
						$nombre_usuario[] = $filas[1];
						$apellido_usuario[] = $filas[2];
						$correo_usuario[] = $filas[3];
					}
				}				
			}			

			$identificacion_usuario = array();
			$descripcion_usuario = array();
			//$query = "SELECT id_usuario, nombre_persona, apellido_persona, correo_persona FROM persona";
			$query = "SELECT persona.id_usuario, persona.nombre_persona, persona.apellido_persona, persona.correo_persona FROM persona INNER JOIN usuario ON persona.id_usuario = usuario.id_usuario WHERE usuario.id_tipo != 1 AND usuario.id_tipo != 3";
			$resultado = $nueva_conexion->ejecutarConsulta($query);
			while($fila = $nueva_conexion->obtenerFilas($resultado)){
				$identificacion_usuario[] = $fila[0];
				$descripcion_usuario[] = $fila[1] . " " . $fila[2] . " (" . $fila[3] . ")";
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
					table{
						width: 100%;
					}
					.tr_titulo{
						width: 100%;
					}
					.tr_columnas{
						width: 100%;
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
						.xxx{
							font-size: .8rem;
						}
					}
					@media screen and (max-width:  770px){
						#margeInput{
							margin-top: 15px;
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
			   						<a href="jefe.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF; font-weight: bolder;">
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
			   						<a href="jefe .php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF !important; font-weight: bolder; text-align: center;">
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
						<?php
		   				if(isset($_GET["val"]) && ($_GET["val"] == 100 || $_GET["val"] == 101 || $_GET["val"] == 104)){
		   					if($_GET["val"] == 100){
		   						$mensaje = "El reporte se ha enviado correctamente.";	
		   					}
		   					else if($_GET["val"] == 101){
		   						$mensaje = "Ha ocurrido un error, contacte a un administrador.";
		   					}		   					
		   					else if($_GET["val"] == 104){
		   						$mensaje = "Contraseña cambiada correctamente.";
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
					</section>
					<section>						
						<div class="container">
							<form action="jefe.php" method="POST">								
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-12 col-lg-12 col-12">
										<label for=""><b>Filtrar</b>:</label>
									</div>									
								</div>								
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-6 col-lg-6 col-12">								
										<select class="form-control pt-3 pb-3" name="tipo_filto" required>
											<option value="">(*) Filtrar por:</option>
											<option value="1" disabled>Movimientos</option>
											<option value="2">Reportes</option>							
										</select>											
									</div>
									<div class="col-md-6 col-lg-6 col-12" id="margeInput">								
										<select class="form-control pt-3 pb-3" name="tipo_usuario" required>
											<option value="">(*) Seleccionar usuario:</option>
											<?php
											for($i=0;$i<count($descripcion_usuario);$i++){
											?>
												<option value='<?php echo $identificacion_usuario[$i]; ?>'><?php echo $descripcion_usuario[$i]; ?></option>";
											<?php
											}
											?>
											<option value="-1">Todos</option>											
										</select>											
									</div>									
								</div>
								<div class=" row justify-content-md-center mt-3 mb-3">
									<div class="col-md-6 col-lg-6 col-12">
										<div class="form-floating">
											<input type="date" class="form-control" id="floatingInputGrid" placeholder="Fecha de Inicio" name="fecha_inicio">
											<label for="floatingInputGrid">Fecha de Inicio</label>
										</div>
									</div>
									<div class="col-md-6 col-lg-6 col-12" id="margeInput">
										<div class="form-floating">
											<input type="date" class="form-control" id="floatingInputGrid" placeholder="Fecha de Termino" name="fecha_termino">
											<label for="floatingInputGrid">Fecha de Termino</label>
										</div>
									</div>
								</div>
								<input type="hidden" name="buscador" value="90">
								<div class=" row justify-content-md-center mb-3">
									<div class="col-md">
										<div class="row mt-3 d-grid gap-1 col-6 mx-auto">
											<button class="btn btn-primary" type="submit" style="background-color: #224875;">Filtrar</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</section>
					<section>
						<?php 
							if(isset($_POST["buscador"]) && $_POST["buscador"] == 90){
								$filtro_por = $_POST["tipo_filto"];
								if(empty($_POST["fecha_inicio"])){
										$fecha_inicio = date("2001-01-01");	
								}
								else{
									$fecha_inicio = $_POST["fecha_inicio"];
								}
								if(empty($_POST["fecha_termino"])){
									$fecha_termino = date("2100-01-01");
								}
								else{
									$fecha_termino = $_POST["fecha_termino"]; 
								}

								if($filtro_por == 2){ //Reporte
						?>
									<div class="container table-responsive">
										<table class="table table-striped mt-3">
											<thead>
												<tr>
													<th class="text-start" scope="col" colspan="4"><b>Seleccionar documento</b>:</th>
												</tr>									
												<tr class="text-center">
													<th scope="col" style="width: 25%;">#</th>
													<th scope="col" style="width: 25%;">Archivo</th>
													<th scope="col" style="width: 25%;">Descargar</th>
													<th scope="col" style="width: 25%;">Modificar</th>
													<th scope="col" style="width: 25%;">Enviar</th>
												</tr>
											</thead>
											<tbody class="text-center xxx">
						<?php			
												if(isset($id_usuario)){
													$x = 1;
													for($j=0;$j<count($id_usuario);$j++){
														$query = "SELECT id_reporte, id_usuario FROM reporte WHERE id_usuario = " . $id_usuario[$j] . 
														" AND fecha_reporte BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_termino . "'";
														$resultado = $nueva_conexion->ejecutarConsulta($query);
														if($resultado){
															if($nueva_conexion->obtenerFilasAfectadas($resultado)){
																echo "<tr>";
																echo "<td class='text-start' scope='row' colspan='4'>" . 
																$nombre_usuario[$j] . " " . 
																$apellido_usuario[$j] . "(" . 
																$correo_usuario[$j] . "):</th>";
																echo "</tr>";		
															}
															while($filas = $nueva_conexion->obtenerFilas($resultado)){
																$archivo = "../archivos/" . $filas[1] . "/reporteDiario_N" . $filas[0] .".pdf";
																$nombreArchivo = "reporteDiario_N" . $filas[0] .".pdf";
																echo "<tr>";
						      									echo "<th scope='row'>" . $x . "</th>";
						      									echo "<td>reporteDiario_N" . $filas[0] . ".pdf</td>";
						      									echo "<td>";
																echo "<a title='Descargar Archivo' href='" . $archivo . "' download='" . $nombreArchivo . "' style='color: #224875; font-size:18px;'>"; 
					      										echo "<i class='fa-solid fa-download'></i>";
					      										echo "</a>";
						      									echo "</td>";
						      									echo "<td>";
						      									echo "<a title='Modificar Archivo' href='modificar_reporte.php?val=" . $filas[0] . "&val2=" . $filas[1] . "' style='color: #224875; font-size:18px;'>"; 
					      										echo "<i class='fa-solid fa-pencil'></i>";
					      										echo "</a>";
						      									echo "</td>";
						      									echo "<td>";
						      									echo "<a title='Enviar Archivo' href='enviar_control.php?val=" . $filas[0] . "&val2=" . $filas[1] . "' style='color: #224875; font-size:18px;'>";
						      									echo "<i class='fa-solid fa-envelope'></i>";
						      									echo "</a>";
					      										echo "</td>";
						      									echo "</tr>";
						      									$x++;
															}
														}																		
		    										}		    									
	    										}
						?>
											</tbody>
										</table>
									</div>
						<?php
								}
								/*else{ //Movimiento
						?>
									<div class="container text-center table-responsive">
										<table class="table table-striped mt-3">
											<thead>
												<tr class="tr_titulo">
													<th class="text-center" scope="col" colspan="4"><b>Seleccionar documento</b>:</th>
												</tr>									
												<tr class="tr_columnas">
													<th scope="col" style="width: 20%;">Titulo</th>
													<th scope="col" style="width: 50%;">Descripci&oacute;n</th>
													<th scope="col" style="width: 15%;">Descargar</th>
													<th scope="col" style="width: 25%;">Fecha</th>
												</tr>
											</thead>
											<tbody class="text-start xxx">
												<?php			
												if(isset($id_usuario)){
													$x = 1;
													for($j=0;$j<count($id_usuario);$j++){
														$query = "SELECT titulo_movimientos, descripcion_movimientos, ruta_movimientos,".
														" fecha_movimiento FROM movimientos WHERE id_usuario = " . $id_usuario[$j] . 
														" AND fecha_movimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_termino . "'";
														$resultado = $nueva_conexion->ejecutarConsulta($query);
														if($resultado){
															if($nueva_conexion->obtenerFilasAfectadas($resultado) > 0){
																echo "<tr>";
																echo "<td class='text-start' scope='row' colspan='4'>" . 
																$nombre_usuario[$j] . " " . 
																$apellido_usuario[$j] . "(" . 
																$correo_usuario[$j] . "):</th>";																
																echo "</tr>";	
																while($filas = $nueva_conexion->obtenerFilas($resultado)){
																	echo "<tr>";
																	echo "<td>" . $filas[0] . "</td>";	
																	echo "<td>" . $filas[1] . "</td>";	
																	if(!empty($filas[2])){
																		echo "<td class='text-center'>";
																		echo "<a title='Descargar Archivo' href='" . $filas[2] . "' download='" . $filas[2] . "' style='color: #224875; font-size:18px;'>"; 
					      												echo "<i class='fa-solid fa-download'></i>";
					      												echo "</a>";
																		echo "</td>";		
																	}		
																	else{
																		echo "<td class='text-center'>" . $filas[2] . "</td>";	
																	}															
																	echo "<td class='text-center'>" . $filas[3] . "</td>";	
																	echo "</tr>";
																}
															}															
														}														
													}													
	    										}
						?>
											</tbody>
										</table>
									</div>
						<?php
								}*/
							}
							$nueva_conexion->cerrarConexion();	
						?>							
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