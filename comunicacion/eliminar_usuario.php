<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");	

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
			header("Location: cerrar_sesion.php");
			die();
		}
		else{
			require("../conexion/conexion.php");
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();

			$identificador_usuario = $_POST["id_usuario"];

			//Buscamos los datos del usuario a eliminar
			$query = "SELECT nombre_persona, apellido_persona, correo_persona FROM persona WHERE id_usuario = " . $identificador_usuario;
			$resultado = $nueva_conexion->ejecutarConsulta($query);
			$fila = $nueva_conexion->obtenerFilas($resultado);

			$query = "DELETE FROM usuario WHERE id_usuario = " . $identificador_usuario;
			$query_dos = "DELETE FROM usuario_proyecto WHERE id_usuario = " . $identificador_usuario;
			$query_tres = "DELETE FROM persona WHERE id_usuario = " . $identificador_usuario;

			$resultado = $nueva_conexion->ejecutarConsulta($query);
			$resultado_dos = $nueva_conexion->ejecutarConsulta($query_dos);
			$resultado_tres = $nueva_conexion->ejecutarConsulta($query_tres);

			if($resultado && $resultado_dos && $resultado_tres){
				
				//Movimiento
				$fecha = date("Y-m-d");
				$titulo = "ELIMINAR USUARIO";
				$descripcion = "Usuario ha eliminado al Usuario " . $fila[0] . " " . $fila[1] . "(" . $fila[2] ."), el " . $nueva_conexion->enviarFechaHora();
				$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
				$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);
				$resultado = $nueva_conexion->ejecutarConsulta($query);
				//Fin Movimiento
				
				//Cerrar la conexión
				$nueva_conexion->cerrarConexion();

				header("Location: ../interfaz/administracion.php?val=103");
				die();				
			}
			else{
				header("Location: ../interfaz/administracion.php?val=102");
				die();			
			}			
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>
