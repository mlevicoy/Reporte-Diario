<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	require("conexion/conexion.php");

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
	
	// Validamos la id de session y el id de usuario
	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id()){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: comunicacion/cerrar_sesion.php");
			die();
		}
		else{
			if($_SESSION["id_tipo"] == 1){
				header("Location: interfaz/administracion.php");
				die();
			}
			else if($_SESSION["id_tipo"] == 2){
				header("Location: interfaz/menu.php");
				die();
			}
			else{
				header("Location: interfaz/jefe.php");
				die();	
			}
		}		
	}
	else{
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<title>Reporte Diario</title>
		<style type="text/css">
			html,body {
					height: 100%;
			}
			body {
				display: flex;
				align-items: center;
			  	padding-top: 40px;
			  	padding-bottom: 40px;
			  	background-color: rgba(0,161,76,.1);
			}
			.form-signin {
				width: 100%;
				max-width: 330px;
				padding: 15px;
				margin: auto;
			}
			.form-signin .checkbox {
				font-weight: 400;
			}
			.form-signin .form-floating:focus-within {
				z-index: 2;
			}
			.form-signin input[type="text"] {
				margin-bottom: -1px;
				border-bottom-right-radius: 0;
				border-bottom-left-radius: 0;
			}
			.form-signin input[type="password"] {
				margin-bottom: 10px;
				border-top-left-radius: 0;
				border-top-right-radius: 0;
			}
			.bd-placeholder-img {
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				user-select: none;
			}
			@media (min-width: 768px) {
				.bd-placeholder-img-lg {
			    	font-size: 3.5rem;
				}
			}
			.fw-normal {
				color: rgba(34,72,117,255);
				font-weight: bolder;
			}
			.btn-primary {
				background-color: rgba(34,72,117,255);
			}
		</style>			
	</head>
<?php
		if(isset($_POST["validar"]) and $_POST["validar"] == 6072){
			$nombre_usuario = strtolower(htmlentities(strval(trim($_POST["userName"])), ENT_QUOTES));
			$contrasena = trim($_POST["passwd"]);
			
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();
			$query = "SELECT id_usuario, id_tipo, contrasena_usuario FROM usuario WHERE nombre_usuario = '" . $nombre_usuario . "'";
			$result = $nueva_conexion->ejecutarConsulta($query);
			if($result){
				if($nueva_conexion->obtenerFilasAfectadas($result) > 0){
					$fila_usuario = $nueva_conexion->obtenerFilas($result);
					if(password_verify($contrasena, $fila_usuario[2])){
						$query = "SELECT nombre_persona, apellido_persona, cargo_persona FROM persona WHERE id_usuario = " . $fila_usuario[0];
						$result = $nueva_conexion->ejecutarConsulta($query);
						$fila_persona = $nueva_conexion->obtenerFilas($result);

						$_SESSION["identificador"] = session_id();
						$_SESSION["usuario"] = $nombre_usuario;
						$_SESSION["tiempo_inicio"] = time();
						$_SESSION["id_usuario"] = $fila_usuario[0];
						$_SESSION["id_tipo"] = $fila_usuario[1];
						$_SESSION["nombre_persona"] = $fila_persona[0];
						$_SESSION["apellido_persona"] = $fila_persona[1];
						$_SESSION["cargo_persona"] = $fila_persona[2];

						//Movimiento
						$fecha = date("Y-m-d");
						$titulo = "INICIO DE SESION";
						$descripcion = "Usuario ha iniciado sesi&oacute;n el " . $nueva_conexion->enviarFechaHora();
						$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
						$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);
						$resultado	= $nueva_conexion->ejecutarConsulta($query);
						$nueva_conexion->cerrarConexion();
						//Fin Movimiento

						if($_SESSION["id_tipo"] == 1){
							header("Location: interfaz/administracion.php");
							die();
						}
						else if($_SESSION["id_tipo"] == 2){
							header("Location: interfaz/menu.php");
							die();
						}
						else{
							header("Location: interfaz/jefe.php");
							die();	
						}
					}
				}
			}
?>
	<body class="text-center">
		<main class="form-signin">
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Informaci&oacute;n Incorrecta.</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
  			<form name="formulario" action="index.php" method="POST" autocomplete="off">
			    <img class="mb-4" src="imagenes/logos/Bogado.jpg" alt="" width="300">
			    <h1 class="h3 mb-3 fw-normal">Iniciar Sesi&oacute;n</h1>
				<div class="form-floating mb-1">
			    	<input type="email" class="form-control" id="floatingInput" name="userName" placeholder="Correo Electr&oacute;nico" autofocus required>
			    	<label for="floatingInput">Correo Electr&oacute;nico</label>
			    </div>
			    <div class="form-floating">
			    	<input type="password" class="form-control" id="floatingPassword" name="passwd" placeholder="Contrase&ntilde;a" required>
			    	<label for="floatingPassword">Contrase&ntilde;a</label>
			    </div>
    			<button class="mb-3 mt-3 w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>	    			
    			<input type="hidden" name="validar" value="6072">
  			</form>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
	</body>
</html>
<?php				
		}
		else{
?>
	<body class="text-center">
		<main class="form-signin">
			<form name="formulario" action="index.php" method="POST" autocomplete="off">
			    <img class="mb-4" src="imagenes/logos/Bogado.png" alt="" width="300">
			    <h1 class="h3 mb-3 fw-normal">Iniciar Sesi&oacute;n</h1>
				<div class="form-floating mb-1">
			    	<input type="email" class="form-control" id="floatingInput" name="userName" placeholder="Correo Electr&oacute;nico" onkeyup="javascript:this.value=this.value.toLowerCase();" autofocus required>
			    	<label for="floatingInput">Correo Electr&oacute;nico</label>
			    </div>
			    <div class="form-floating">
			    	<input type="password" class="form-control" id="floatingPassword" name="passwd" placeholder="Contrase&ntilde;a" required>
			    	<label for="floatingPassword">Contrase&ntilde;a</label>
			    </div>
    			<button class="mb-3 mt-3 w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>	    			
    			<input type="hidden" name="validar" value="6072">
  			</form>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
	</body>
</html>
<?php
		}
	}	
?>