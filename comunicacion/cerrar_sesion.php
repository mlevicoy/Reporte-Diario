<?php
	date_default_timezone_set("America/Santiago");

	/////////////////// ERR-MISS-CACHE //////////////////////////////////
	//establecer el limitador de caché a 'private'
	//session_cache_limiter('private');
	//$cache_limiter = session_cache_limiter();

	//establecer la caducidad de la Caché a 30 minutos
	//session_cache_expire(30);
	//$cache_expire = session_cache_expire();
	/////////////////// ERR-MISS-CACHE //////////////////////////////////
	
	session_start();
	
	//Movimiento
	$fecha = date("Y-m-d");
	require("../conexion/conexion.php");
	$nueva_conexion = new Conexion();
	$nueva_conexion->crearConexion();
	$titulo = "CERRAR SESION";
	$descripcion = "Usuario ha finalizado su sesi&oacute;n el " . $nueva_conexion->enviarFechaHora();
	$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
	$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);	
	$resultado	= $nueva_conexion->ejecutarConsulta($query);
	$nueva_conexion->cerrarConexion();
	//Fin Movimiento
	
	// Destruir todas las variables de sesión.
	$_SESSION = array();

	// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
	// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	// Finalmente, destruir la sesión.
	session_destroy();

	header("Location: ../index.php");
	die();
?>