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
	
	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 3){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{
			//Creamos una instancia
			$nuevaConexion = new Conexion();
			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();
			
			$numReporte = (int)$_GET['val'];
			$numUsuario = (int)$_GET['val2'];
			
			$_SESSION["numero_reporte"] = $numReporte;
			$_SESSION["numero_usuario"] = $numUsuario;

			//Consulta proyecto
			$consulta_proyecto = "SELECT * FROM proyecto WHERE id_reporte = " . $_SESSION["numero_reporte"] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];
			$resultado_proyecto	= $nuevaConexion->ejecutarConsulta($consulta_proyecto);
			if($resultado_proyecto){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_proyecto = $nuevaConexion->obtenerFilas($resultado_proyecto);											
				}
			}
		
			//Consulta reporte
			$consulta_reporte = "SELECT * FROM reporte WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_reporte	= $nuevaConexion->ejecutarConsulta($consulta_reporte);
			if($resultado_reporte){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_reporte = $nuevaConexion->obtenerFilas($resultado_reporte);											
				}
			}

			//Consulta trabajo
			$consulta_trabajo = "SELECT * FROM trabajo WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_trabajo	= $nuevaConexion->ejecutarConsulta($consulta_trabajo);
			if($resultado_trabajo){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_trabajo = $nuevaConexion->obtenerFilas($resultado_trabajo);											
				}
			}			
			
			//Consulta plazo trabajo
			$consulta_plazo_trabajo = "SELECT * FROM plazo_trabajo WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_plazo_trabajo = $nuevaConexion->ejecutarConsulta($consulta_plazo_trabajo);
			if($resultado_plazo_trabajo){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_plazo_trabajo = $nuevaConexion->obtenerFilas($resultado_plazo_trabajo);											
				}
			}

			//Consulta horas
			$consulta_horas = "SELECT * FROM horas WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_horas = $nuevaConexion->ejecutarConsulta($consulta_horas);
			if($resultado_horas){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_horas = $nuevaConexion->obtenerFilas($resultado_horas);											
				}
			}

			//Consulta personaleecc
			$consulta_personaleecc = "SELECT * FROM personaleecc WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_personaleecc = $nuevaConexion->ejecutarConsulta($consulta_personaleecc);
			if($resultado_personaleecc){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_personaleecc = $nuevaConexion->obtenerFilas($resultado_personaleecc);											
				}
			}

			//Consulta equipoeecc
			$consulta_equipoeecc = "SELECT * FROM equipoeecc WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_equipoeecc = $nuevaConexion->ejecutarConsulta($consulta_equipoeecc);
			if($resultado_equipoeecc){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_equipoeecc = $nuevaConexion->obtenerFilas($resultado_equipoeecc);											
				}
			}

			//Consulta trabajadores
			$consulta_trabajadores = "SELECT * FROM trabajadores WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_trabajadores	= $nuevaConexion->ejecutarConsulta($consulta_trabajadores);
			if($resultado_trabajadores){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_trabajadores = $nuevaConexion->obtenerFilas($resultado_trabajadores);											
				}
			}

			//Consulta condicion
			$consulta_condicion = "SELECT * FROM condicion WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];
			$resultado_condicion = $nuevaConexion->ejecutarConsulta($consulta_condicion);
			if($resultado_condicion){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_condicion = $nuevaConexion->obtenerFilas($resultado_condicion);											
				}
			}

			//Consulta cumplimiento
			$consulta_cumplimiento = "SELECT * FROM cumplimiento WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_cumplimiento = $nuevaConexion->ejecutarConsulta($consulta_cumplimiento);
			if($resultado_cumplimiento){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_cumplimiento = $nuevaConexion->obtenerFilas($resultado_cumplimiento);											
				}
			}

			//Consulta texto
			$consulta_texto = "SELECT * FROM texto WHERE id_reporte = " . $_SESSION['numero_reporte'] . 
			" AND id_usuario = " . $_SESSION["numero_usuario"];			
			$resultado_texto = $nuevaConexion->ejecutarConsulta($consulta_texto);
			if($resultado_texto){
				if($nuevaConexion->obtenerFilasAfectadas() > 0){
					$fila_texto = $nuevaConexion->obtenerFilas($resultado_texto);											
				}
			}			
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="../librerias/js/formulario.js"></script>        
	<title>Reporte Diario</title>
	<style type="text/css">
		html,body{
			font-size: 1em;
		}		
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
		.active{
			color:  #00A14C !important;
			font-weight: bolder;
		}
		#valorInput{
			color: #000000 !important;
			font-weight: normal;
		}
		#fotoCorta{
			display: none;
		}
		@media screen and (max-width: 500px) {
			span.titulo{
				font-size: 12px !important;
			}		
		}
		@media screen and (max-width:  770px){
			#margeInput{
				margin-top: 15px;
			}
			#fotoLarga{
				display: none;
			}
			#fotoCorta{
				display: inline;
			}
			#divMargin{
				margin-top: 0px !important;
			}
		} 
		@media screen and (max-width: 990px) {
			.iconos-navbar-2{
				display: none !important;
			}	
			.iconos-navbar-1{
				display: inline !important;
			}
			.tamanoLetra{
				font-size: 0.8rem;
			}
			.tamanoLetra2{
				font-size: 0.7rem;
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
   							<i class="fa-solid fa-globe"></i>&nbsp;www.bogado.cl
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
   						<a href="jefe.php" class="nav-link active" aria-current="page" href="#" style="color:#FFFFFF !important; font-weight: bolder; text-align: center;">
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
	<main style="width: 100%;">		
		<section style="width: 100%;">
			<div class="container mb-5">
				<form name="formulario" action="../comunicacion/actualizar_reporte_control.php" method="POST" enctype="multipart/form-data">
					<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
		  				<li class="nav-item" role="presentation">
		    				<button class="nav-link active" id="informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">
		    					Informaci&oacute;n
		    				</button>
		  				</li>
		  				<li class="nav-item" role="presentation">
		    				<button class="nav-link" id="reporte-tab" data-bs-toggle="tab" data-bs-target="#reporte" type="button" role="tab" aria-controls="reporte" aria-selected="false">
		    					Reporte
		    				</button>
		  				</li>
		  				<li class="nav-item" role="presentation">
		    				<button class="nav-link" id="fotografia-tab" data-bs-toggle="tab" data-bs-target="#fotografia" type="button" role="tab" aria-controls="fotografia" aria-selected="false">
		    					<span id="fotoCorta">Fotograf&iacute;as</span>
		    					<span id="fotoLarga">Registros Fotogr&aacute;ficos</span>
		    					
		    				</button>
		  				</li>	  				
					</ul>
					<div class="tab-content" id="myTabContent">
						<!-- Inicio Información -->
  						<div class="tab-pane fade show active" id="informacion" role="tabpanel" aria-labelledby="informacion-tab">
  							<div class="row mt-5">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Nombre" name="nombre_proyecto" value="<?php echo $fila_proyecto[3]; ?>" required autofocus>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Nombre Proyecto</label>
								</div>
								<div class="form-floating mb-0 col-md-2" id="margeInput">
									<select class="form-control" placeholder="Revisión N°" name="revision">
									<?php	
										}
										else if($fila_reporte[4] == "Rev. B"){
									?>
											<option value=""></option>
											<option value="Rev. B" selected>Rev. B</option>	
											<option value="Rev. 0">Rev. 0</option>											
									<?php
										}
										else if($fila_reporte[4] == "Rev. 0"){
									?>
											<option value=""></option>
											<option value="Rev. B">Rev. B</option>	
											<option value="Rev. 0" selected>Rev. 0</option>	
									<?php
										}									
									?>
											
									</select>
									<!--<input type="text" class="form-control" id="floatingInput monto" placeholder="Monto" name="monto" onblur="formatearMonto()" onkeypress="return soloNumeros(event)" required>-->
									<label for="floatingInput" id="valorInput">&nbsp;(*) Revisión</label>
								</div>								
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="N&uacute;mero" name="numero" value="<?php echo $fila_proyecto[4]; ?>" onkeypress="return soloNumeros(event);" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) N°</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="text" class="form-control" id="floatingInput monto" placeholder="Monto" name="monto" value="<?php echo $fila_proyecto[5]; ?>" onblur="formatearMonto()" onkeypress="return soloNumeros(event)" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Monto</label>
								</div>	
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Mandante" name="mandante" value="<?php echo $fila_proyecto[6]; ?>" maxlength="30" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Mandante</label>
								</div>
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Contratista" name="contratista" value="<?php echo $fila_proyecto[7]; ?>" maxlength="30" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Contratista</label>
								</div>
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Inspector T&eacute;cnico" name="inspector_tecnico" value="<?php echo $fila_proyecto[8]; ?>" maxlength="30" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Inspector T&eacute;cnico</label>
								</div>
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="N&uacute;mero de Reporte" name="numero_reporte" value="<?php echo $_SESSION["numero_reporte"]; ?>" required readonly>
									<label for="floatingInput" class="tamanoLetra" id="valorInput">&nbsp;(*) N&uacute;mero de Reporte</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
								<input type="date" class="form-control" id="floatingInput" placeholder="Fecha Reporte" name="fecha_reporte" value="<?php echo $fila_reporte[3]; ?>" onblur="diasRestantes()" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Fecha Reporte</label>
								</div>									
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="text" class="form-control" id="floatingInput" placeholder="Sector de Trabajo / Calle" name="sector_trabajo" value="<?php echo $fila_trabajo[3]; ?>" maxlength="25" required>
									<label for="floatingInput" class="tamanoLetra" id="valorInput">&nbsp;(*) Sector de Trabajo / Calle</label>									
								</div>		
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="text" class="form-control" id="floatingInput" placeholder="Lugar de Trabajo" name="lugar_trabajo" value="<?php echo $fila_trabajo[4]; ?>" maxlength="25" required>
									<label for="floatingInput" class="tamanoLetra" id="valorInput">&nbsp;(*) Lugar de Trabajo</label>									
								</div>							
							</div>
  						</div>
  						<!-- Fin Información -->
  						<!-- Inicio Reporte -->
		  				<div class="tab-pane fade" id="reporte" role="tabpanel" aria-labelledby="repote-tab">
		  					<div class="row mt-5">  						
								<div class="form-floating mb-0 col-md">
									<select name="turno_manana" id="floatingInput" class="form-select form-select-sm form control" aria-label=".form-select-sm">
										<?php
											if($fila_trabajo[5] == "X"){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";
											}
										?>																				
									</select>
									<label for="floatingInput" class="tamanoLetra" id="valorInput">&nbsp;Turno Ma&ntilde;ana</label>
								</div>				
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select name="turno_tarde" id="floatingInput" class="form-select form-select-sm form control" aria-label=".form-select-sm">
										<?php
											if($fila_trabajo[6] == "X"){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";
											}
										?>																				
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;Turno Tarde</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="date" class="form-control" id="floatingInput" placeholder="Fecha de Inicio" name="fecha_inicio" value="<?php echo $fila_plazo_trabajo[3]; ?>" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Fecha de Inicio</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="date" class="form-control" id="floatingInput" placeholder="Fecha de T&eacute;rmino" name="fecha_termino" value="<?php echo $fila_plazo_trabajo[4]; ?>" onblur="diasRestantes()" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Fecha de T&eacute;rmino</label>
								</div>
							</div>
							<div class="row mt-3">
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="text" class="form-control" id="floatingInput" placeholder="Plazo" name="plazo" value="<?php echo $fila_plazo_trabajo[5]; ?>" onkeypress="return soloNumeros(event);" required>
									<label for="floatingInput" class="tamanoLetra" id="valorInput">&nbsp;(*) Plazo (D&iacute;as)</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="date" class="form-control" id="floatingInput" placeholder="Entrega de Terreno" name="entrega_terreno" value="<?php echo $fila_plazo_trabajo[6]; ?>" required>
									<label for="floatingInput" id="valorInput">&nbsp;(*) Entrega de Terreno</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="text" class="form-control" id="floatingInput" placeholder="Días Restantes" name="dias_restantes" value="<?php echo $fila_plazo_trabajo[7]; ?>" onkeypress="return soloNumeros(event);" readonly required>
									<label for="floatingInput" class="tamanoLetra2" id="valorInput">&nbsp;(*) D&iacute;as Restantes</label>
								</div>
							</div>
							<div class="row mt-3 mb-3 ms-1 me-1 bg-success pt-3 pb-3">
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Horario Normal</b></span>
								</div>
							</div>							
							<div class="row mt-0">
								<div class="form-floating mb-0 col-md">
									<input type="time" class="form-control fw-bold"  id="floatingInput" placeholder="Inicio" name="hh_inicio_normal" value="<?php echo $fila_horas[3]; ?>" onblur="restarhoras(1)">
									<label for="floatingInput" id="valorInput">&nbsp;Inicio</label>
								</div>					
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Termino" name="hh_termino_normal" value="<?php echo $fila_horas[5]; ?>" onblur="restarhoras(1)">	
									<label for="floatingInput" id="valorInput">&nbsp;Termino</label>
								</div>						
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Informe" name="hh_informe_normal" value="<?php echo $fila_horas[7]; ?>" onblur="restarhoras(1)">	
									<label for="floatingInput" id="valorInput">&nbsp;Informe</label>
								</div>										
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Trayecto" name="hh_trayecto_normal" value="<?php echo $fila_horas[11]; ?>" onblur="restarhoras(1)">	
									<label for="floatingInput" id="valorInput">&nbsp;Trayecto</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Total" name="hh_total_normal" value="<?php echo $fila_horas[9]; ?>" readonly>	
									<label for="floatingInput" id="valorInput">&nbsp;Total</label>
								</div>						
							</div>
							<div class="row mt-3 mb-3 ms-1 me-1 bg-success pt-3 pb-3">
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Horas Extras</b></span>
								</div>
							</div>								
							<div class="row mt-0">
								<div class="form-floating mb-0 col-md">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Inicio" name="hh_inicio_extras" value="<?php echo $fila_horas[4]; ?>" onblur="restarhoras(2)">
									<label for="floatingInput" id="valorInput">&nbsp;Inicio</label>
								</div>										
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Termino" name="hh_termino_extras" value="<?php echo $fila_horas[6]; ?>" onblur="restarhoras(2)">	
									<label for="floatingInput" id="valorInput">&nbsp;Termino</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Informe" name="hh_informe_extras" value="<?php echo $fila_horas[8]; ?>" onblur="restarhoras(2)">	
									<label for="floatingInput" id="valorInput">&nbsp;Informe</label>			
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Trayecto" name="hh_trayecto_extras" value="<?php echo $fila_horas[12]; ?>" onblur="restarhoras(2)">	
									<label for="floatingInput" id="valorInput">&nbsp;Trayecto</label>			
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<input type="time" class="form-control fw-bold" id="floatingInput" placeholder="Total" name="hh_total_extras" value="<?php echo $fila_horas[10]; ?>" readonly>	
									<label for="floatingInput" id="valorInput">&nbsp;Total</label>
								</div>
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Personal Contratista</b></span>
								</div>
							</div>
							<div class="row mt-3">  												
								<div class="col" id="margeInput">
									<span id="valorInput"><b>Administrador</b></span>
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="administrador_si">
									<?php
									if(strcmp($fila_personaleecc[3], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>Existe</option>";
									}
									else{
										echo "<option value='' selected>Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>										
									</select>									
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="administrador_no">
									<?php
									if(strcmp($fila_personaleecc[4], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>No Existe</option>";
									}
									else{
										echo "<option value='' selected>No Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>								
							</div>
							<div class="row mt-3">  												
								<div class="col" id="margeInput">
									<span id="valorInput"><b>Jefe de Terreno</b></span>
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="jterreno_si">
									<?php
									if(strcmp($fila_personaleecc[5], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>Existe</option>";
									}
									else{
										echo "<option value='' selected>Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="jterreno_no">
									<?php
									if(strcmp($fila_personaleecc[6], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>No Existe</option>";
									}
									else{
										echo "<option value='' selected>No Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>								
							</div>
							<div class="row mt-3">  												
								<div class="col" id="margeInput">
									<span class="tamanoLetra2" id="valorInput"><b>Encargado de Calidad</b></span>
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="ecalidad_si">
									<?php
									if(strcmp($fila_personaleecc[7], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>Existe</option>";
									}
									else{
										echo "<option value='' selected>Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="ecalidad_no">
									<?php
									if(strcmp($fila_personaleecc[8], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>No Existe</option>";
									}
									else{
										echo "<option value='' selected>No Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>								
							</div>
							<div class="row mt-3">  												
								<div class="col" id="margeInput">
									<span id="valorInput"><b>Supervisor OOCC</b></span>
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="oocc_si">
									<?php
									if(strcmp($fila_personaleecc[9], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>Existe</option>";
									}
									else{
										echo "<option value='' selected>Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="oocc_no">
									<?php
									if(strcmp($fila_personaleecc[10], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>No Existe</option>";
									}
									else{
										echo "<option value='' selected>No Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>								
							</div>
							<div class="row mt-3">  												
								<div class="col" id="margeInput">
									<span id="valorInput"><b>Prevencionista</b></span>
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="prevencionista_si">
									<?php
									if(strcmp($fila_personaleecc[11], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>Existe</option>";
									}
									else{
										echo "<option value='' selected>Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>
								<div class="col" id="margeInput">
									<select class="form-control" name="prevencionista_no">
									<?php
									if(strcmp($fila_personaleecc[12], "X") == 0){
										echo "<option value='X' selected>X</option>";
										echo "<option value=''>No Existe</option>";
									}
									else{
										echo "<option value='' selected>No Existe</option>";
										echo "<option value='X'>X</option>";										
									}
									?>
									</select>									
								</div>								
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">
								<div class="mb-0 col-md">
									<span class="text-light"><b>Equipos Contratista</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 200 caracteres" name="equipo_contratista" rows="5" maxlength="220"><?php echo $fila_equipoeecc[3]; ?></textarea>					
								</div>
							</div>							
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">						
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Permiso BNUP</b></span>
								</div>
							</div>
							<div class="row mt-3">  												
								<div class="form-floating mb-0 col">
									<input type="date" class="form-control fw-bold" id="floatingInput" placeholder="Fecha de Inicio" name="inicio_bnup" value="<?php echo $fila_trabajadores[3]; ?>">
									<label for="floatingInput" id="valorInput">&nbsp;Fecha de Inicio</label>
								</div>
								<div class="form-floating mb-0 col">
									<input type="date" class="form-control fw-bold" id="floatingInput" placeholder="Fecha de T&eacute;rmino" name="termino_bnup" value="<?php echo $fila_trabajadores[4]; ?>">
									<label for="floatingInput" id="valorInput">&nbsp;Fecha de T&eacute;rmino</label>
								</div>								
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">						
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Condiciones de Obra</b></span>
								</div>
							</div>
							<div class="row mt-3">  												
								<div class="mb-0 col-md" id="margeInput">
									<span class="tamanoLetra" id="valorInput"><b>Tiene PTS</b></span>
								</div>
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="tiene_c" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[3], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;C</label>										
								</div>					
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="tiene_nc" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[4], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NC</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="tiene_na" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[5], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NA</label>
								</div>
							</div>
							<div class="row mt-0">  												
								<div class="mb-0 col-md" id="margeInput">
									<span class="tamanoLetra" id="valorInput"><b>Prevencionista en Terreno</b></span>
								</div>
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="prevencion_c" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[6], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;C</label>	
								</div>					
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="prevencion_nc" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[7], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NC</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="prevencion_na" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[8], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NA</label>
								</div>
							</div>
							<div class="row mt-0" id="divMargin">  												
								<div class="mb-0 col-md" id="margeInput">
									<span class="tamanoLetra" id="valorInput"><b>Supervisor en el &Aacute;rea</b></span>
								</div>
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="supervisor_c" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[9], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;C</label>
								</div>					
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="supervisor_nc" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[10], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NC</label>
								</div>
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="supervisor_na" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_condicion[11], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NA</label>
								</div>
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3" id="divMargin">  												
								<div class="form-floating mb-0 col-md">
									<span class="text-light"><b>Cumplimientos</b></span>
								</div>
							</div>
							<div class="row mt-3">  												
								<div class="mb-0 col-md">
									<span class="tamanoLetra" id="valorInput"><b>Cumple Procedimiento u otro Documento</b></span>
								</div>
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="cumple_c" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[3], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;C</label>
								</div>					
								<div class="form-floating mb-3 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="cumple_nc" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[4], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NC</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="cumple_na" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[5], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NA</label>
								</div>
							</div>
							<div class="row mt-0">  												
								<div class="mb-0 col-md" id="margeInput">
									<span class="tamanoLetra" id="valorInput"><b>Personal conoce los Procedimientos</b></span>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="conoce_c" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[6], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;C</label>
								</div>					
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="conoce_nc" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[7], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp;NC</label>
								</div>
								<div class="form-floating mb-0 col-md" id="margeInput">
									<select class="form-select form-select-sm form-control" aria-label=".form-select-sm" name="conoce_na" id="floatingInput" placeholder="C">
										<?php
											if(strcmp($fila_cumplimiento[8], "X") == 0){
												echo "<option value=''></option>";
												echo "<option value='X' selected>X</option>";
											}
											else{
												echo "<option value='' selected></option>";
												echo "<option value='X'>X</option>";										
											}
										?>	
									</select>
									<label for="floatingInput" id="valorInput">&nbsp; NA</label>
								</div>
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">  												
								<div class="mb-0 col-md">
									<span class="text-light"><b>Actividades</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 600 caracteres" name="actividades" rows="5" maxlength="600"><?php echo $fila_texto[3]; ?></textarea>																	
								</div>
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">  												
								<div class="mb-0 col-md">
									<span class="text-light"><b>Hallazgos</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 600 caracteres" rows="5" name="hallazgos" maxlength="600"><?php echo $fila_texto[4]; ?></textarea>									
								</div>								
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">  												
								<div class="mb-0 col-md">
									<span class="text-light"><b>Actividades de Control de Calidad</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 600 caracteres" rows="5" name="actividades_calidad" maxlength="600"><?php echo $fila_texto[5]; ?></textarea>
								</div>								
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">  												
								<div class="mb-0 col-md">
									<span class="text-light"><b>T&oacute;pico Reuni&oacute;n (si existe)</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 600 caracteres" rows="5" name="topico_reunion" maxlength="600"><?php echo $fila_texto[6]; ?></textarea>
								</div>								
							</div>
							<div class="row mt-3 ms-1 me-1 bg-success pt-3 pb-3">  												
								<div class="mb-0 col-md">
									<span class="text-light"><b>Incidentes/Condici&oacute;n de Riesgos/No Conformidad</b></span>
								</div>
							</div>
							<div class="row mt-3">
								<div class="mb-0 col-md">
									<textarea class="form-control" id="" placeholder="M&aacute;ximo 600 caracteres" rows="5" name="incidentes" maxlength="600"><?php echo $fila_texto[7]; ?></textarea>
								</div>								
							</div>	
						</div>						
		  				<!-- Fin Reporte -->
		  				<!-- Inicio Registro Fotográfico -->
		  				<div class="tab-pane fade" id="fotografia" role="tabpanel" aria-labelledby="fotografia-tab">
		  					<div class="row mt-3">
		  						<div class="alert alert-success text-center" role="alert">
  									No modificar a no ser que quiera cambiar todas las imagenes y/o libro de este reporte.
								</div>
		  					</div>
		  					<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 1</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_1" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(1)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripci&oacute;n" value="" name="descripcion_imagen_1" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripci&oacute;n</label>
								</div>
		  					</div>		  					
							<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 2</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_2"  accept=".jpg, jpeg, png, bmp" onchange="validarExtension(2)">
								</div> 							
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripci&oacute;n" value="" name="descripcion_imagen_2" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripci&oacute;n</label>
								</div>
		  					</div>  				
		  					<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 3</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_3" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(3)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripci&oacute;n" value="" name="descripcion_imagen_3" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripci&oacute;n</label>
								</div>
		  					</div>
		  					<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 4</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_4" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(4)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripci&oacute;n" value="" name="descripcion_imagen_4" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripci&oacute;n</label>
								</div>
		  					</div>
		  					<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 5</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_5" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(5)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripción Imagen 5" value="" name="descripcion_imagen_5" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripción</label>
								</div>
		  					</div>
		  					<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Imagen 6</b></label>
									<input class="form-control" type="file" id="formFile" name="foto_6" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(6)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripción Imagen 6" value="" name="descripcion_imagen_6" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripción</label>
								</div>
		  					</div>
			  				<div class="row mt-3">
		  						<div class="mb-1">
									<label for="formFile" class="form-label" id="valorInput"><b>Libro de Obra</b></label>
									<input class="form-control" type="file" id="formFile" name="libro_obra" accept=".jpg, jpeg, png, bmp" onchange="validarExtension(7)">
								</div>
								<div class="form-floating mb-0 col-md">
									<input type="text" class="form-control" id="floatingInput" placeholder="Descripción Libro de Obra" value="" name="descripcion_libro" maxlength="40">
									<label for="floatingInput" id="valorInput">Descripción</label>
								</div>
		  					</div>		
		  					<div class="row mt-5 d-grid gap-1 col-6 mx-auto">
								<button type="submit" name="formulario" class="btn btn-primary mb-3" style="background-color: #224875 !important;">GENERAR INFORME</button>
							</div>	  						  					
		  				</div>		  				
		  				<!-- Fin Registro Fotográfico -->					
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