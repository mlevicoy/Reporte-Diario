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
			header("Location: cerrar_sesion.php");
			die();
		}
		else{
			//DB Destinatarios
			$nombre_destinatario = strtoupper(htmlentities(strval(trim($_POST["nombre_destinatario"])), ENT_QUOTES));
			$apellido_destinatario = strtoupper(htmlentities(strval(trim($_POST["apellido_destinatario"])), ENT_QUOTES));
			$cargo_destinatario = strtoupper(htmlentities(strval(trim($_POST["cargo_destinatario"])), ENT_QUOTES));
			$correo_destinatario = strtolower(htmlentities(strval(trim($_POST["correo_destinatario"])), ENT_QUOTES));
			
			//Creamos una instancia
			$nuevaConexion = new Conexion();

			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			//Guardamos la info de destinatarios
			$array_destinatario = array($_SESSION["id_usuario"], $nombre_destinatario, $apellido_destinatario, $cargo_destinatario, $correo_destinatario);
			$query = $nuevaConexion->generamosConsulta("destinatarios", $array_destinatario);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Movimiento
			$fecha = date("Y-m-d");
			$titulo = "AGREGAR DESTINATARIO";
			$descripcion = "Usuario ha agregado destinatario " . $nombre_destinatario . " " . $apellido_destinatario . "(" . $correo_destinatario ."), el " . $nuevaConexion->enviarFechaHora();
			$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
			$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			//Fin Movimiento
			
			//Cerrar la conexión
			$nuevaConexion->cerrarConexion();		

			//Redirigimos al generar_pdf.php
			header("Location: ../interfaz/destinatario.php?val=1");
			die();
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>