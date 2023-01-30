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
			$destinatario = $_POST["destinatario"];
			
			//Creamos una instancia
			$nuevaConexion = new Conexion();

			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			$query_datos = "SELECT nombre_destinatario, apellido_destinatario, correo_destinatario FROM destinatarios WHERE id_destinatario = " . $destinatario . " AND id_usuario = " . $_SESSION["id_usuario"];
			$resultado_datos = $nuevaConexion->ejecutarConsulta($query_datos);
			$fila_datos = $nuevaConexion->obtenerFilas($resultado_datos);

			//Guardamos la info de destinatarios
			$query = "DELETE FROM destinatarios WHERE id_destinatario = " . $destinatario . " AND id_usuario = " . $_SESSION["id_usuario"];
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Movimiento
			$fecha = date("Y-m-d");
			$titulo = "ELIMINAR DESTINATARIO";
			$descripcion = "Usuario ha eliminado al destinatario " . $fila_datos[0] . " " . $fila_datos[1] . "(" . $fila_datos[2] ."), el " . $nuevaConexion->enviarFechaHora();
			$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
			$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			//Fin Movimiento
			
			//Cerrar la conexión
			$nuevaConexion->cerrarConexion();	

			//Redirigimos al generar_pdf.php
			header("Location: ../interfaz/eliminar_destinatario.php?val=1");
			die();
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>