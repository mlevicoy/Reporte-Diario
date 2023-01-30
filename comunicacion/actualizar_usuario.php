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

			//Datos
			$nombre_usuario = htmlentities(strval(trim($_POST["nombre_destinatario"])), ENT_QUOTES);
			$apellido_usuario = htmlentities(strval(trim($_POST["apellido_destinatario"])), ENT_QUOTES);
			$cargo_usuario = htmlentities(strval(trim($_POST["cargo_destinatario"])), ENT_QUOTES);
			$correo_usuario = htmlentities(strval(trim($_POST["correo_destinatario"])), ENT_QUOTES);
			$correo_original = htmlentities(strval(trim($_POST["correo_original"])), ENT_QUOTES);
			$tipo_cuenta = $_POST["tipo_cuenta"];
			$nombre_proyecto = htmlentities(strval(trim($_POST["nombre_proyecto"])), ENT_QUOTES);
			$numero_proyecto = htmlentities(strval(trim($_POST["numero_proyecto"])), ENT_QUOTES);

			//Verificamos el correo
			if(strcmp($correo_usuario, $correo_original) == 0){
				$actualizar_usuario = "UPDATE usuario SET id_tipo = " . $tipo_cuenta . " WHERE nombre_usuario = '" . $correo_usuario . "'";

				$actualizar_usuario_proyecto = "UPDATE usuario_proyecto SET nombre_proyecto = '" . $nombre_proyecto . "', numero_proyecto = '" . $numero_proyecto . "' WHERE id_usuario = " . $_SESSION["id_nuevoUsuario"];

				$actualizar_persona = "UPDATE persona SET nombre_persona = '" . $nombre_usuario . "', apellido_persona = '" . $apellido_usuario . "', cargo_persona = '" . $cargo_usuario . "' WHERE correo_persona = " . $correo_original;

				$nueva_conexion->ejecutarConsulta($actualizar_usuario);
				$nueva_conexion->ejecutarConsulta($actualizar_usuario_proyecto);
				$nueva_conexion->ejecutarConsulta($actualizar_persona);			

				//Movimiento
				$fecha = date("Y-m-d");
				$titulo = "ACTUALIZAR USUARIO";
				$descripcion = "Usuario ha actualizado la información de " . $nombre_usuario . " " . $apellido_usuario . "(" . $correo_usuario ."), el " . $nueva_conexion->enviarFechaHora();
				$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
				$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);
				$resultado	= $nueva_conexion->ejecutarConsulta($query);
				$nueva_conexion->cerrarConexion();
				//Fin Movimiento

				header("Location: ../interfaz/modificar_usuario.php?val=100");
				die();
			}
			else{
				//Verificamos que el nuevo correo no exista
				$query = "SELECT * FROM usuario WHERE nombre_usuario = '" . $correo_usuario . "'";
				$resultado = $nueva_conexion->ejecutarConsulta($query);
				$fila = $nueva_conexion->obtenerFilas($resultado);
				if(empty($fila)){ //Correo no existe
					$actualizar_usuario = "UPDATE usuario SET id_tipo = " . $tipo_cuenta . ", nombre_usuario = '" . $correo_usuario . "' WHERE nombre_usuario = '" . $correo_original . "'";

					$actualizar_usuario_proyecto = "UPDATE usuario_proyecto SET nombre_proyecto = '" . $nombre_proyecto . "', numero_proyecto = '" . $numero_proyecto . "' WHERE id_usuario = " . $_SESSION["id_nuevoUsuario"];

					$actualizar_persona = "UPDATE persona SET nombre_persona = '" . $nombre_usuario . "', apellido_persona = '" . $apellido_usuario . "', cargo_persona = '" . $cargo_usuario . "', correo_persona = '" . $correo_usuario . "' WHERE id_usuario = " . $_SESSION["id_nuevoUsuario"];

					$nueva_conexion->ejecutarConsulta($actualizar_usuario);
					$nueva_conexion->ejecutarConsulta($actualizar_usuario_proyecto);
					$nueva_conexion->ejecutarConsulta($actualizar_persona);
					
					//Movimiento
					$fecha = date("Y-m-d");
					$titulo = "ACTUALIZAR USUARIO";
					$descripcion = "Usuario ha actualizado la información de " . $nombre_usuario . " " . $apellido_usuario . "(" . $correo_usuario ."), el " . $nueva_conexion->enviarFechaHora();
					$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
					$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);
					$resultado	= $nueva_conexion->ejecutarConsulta($query);
					$nueva_conexion->cerrarConexion();	
					//Fin Movimiento
						
					header("Location: ../interfaz/modificar_usuario.php?val=100");
					die();				
				}
				else{
					header("Location: ../interfaz/modificar_usuario.php?val=101");
					die();
				}
			}		
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>