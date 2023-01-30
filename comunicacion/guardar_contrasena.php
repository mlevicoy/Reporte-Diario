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

	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && isset($_SESSION["id_usuario"])){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: cerrar_sesion.php");
			die();
		}
		else{
			//Datos
			$contrasena_actual = htmlentities(strval(trim($_POST["contrasena_actual"])), ENT_QUOTES);
			$nueva_contrasena = htmlentities(strval(trim($_POST["nueva_contrasena"])), ENT_QUOTES);
			$validar_nueva_contrasena = htmlentities(strval(trim($_POST["validar_nueva_contrasena"])), ENT_QUOTES); 
			
			//Creamos una instancia
			$nuevaConexion = new Conexion();

			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			$query = "SELECT contrasena_usuario FROM usuario WHERE id_usuario = " . $_SESSION["id_usuario"];
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			$fila = $nuevaConexion->obtenerFilas($resultado);

			// Validamos la contraseña nueva
			if(strcmp($nueva_contrasena, $validar_nueva_contrasena) == 0){
				if(password_verify($contrasena_actual, $fila[0])){
					//Encriptamos la contraseña
					$contrasena_encriptada = password_hash($nueva_contrasena, PASSWORD_BCRYPT);

					//Actualizamos					
					$query = "UPDATE usuario SET contrasena_usuario = '$contrasena_encriptada' WHERE id_usuario = " . $_SESSION["id_usuario"];
					$resultado	= $nuevaConexion->ejecutarConsulta($query);

					//Movimiento
					$fecha = date("Y-m-d");
					$titulo = "CAMBIO DE CONTRASE&Ntilde;A";
					$descripcion = "Usuario ha cambiado su contrase&ntilde;a el " . $nuevaConexion->enviarFechaHora();
					$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
					$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);	
					$resultado	= $nuevaConexion->ejecutarConsulta($query);
					//Fin Movimiento

					//Redirigimos
					if($_SESSION["id_tipo"] == 1){
						header("Location: ../interfaz/administracion.php?val=104");
						die();
					}
					else if($_SESSION["id_tipo"] == 2){
						header("Location: ../interfaz/menu.php?val=104");
						die();	
					}
					else{
						header("Location: ../interfaz/jefe.php?val=104");
						die();
					}					
				}				
			}
			//Redirigimos
			header("Location: ../interfaz/cambiar_contrasena.php?val=2");
			die();	
										
			//Cerrar la conexión
			$nuevaConexion->cerrarConexion();					
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>