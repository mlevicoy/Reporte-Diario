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
			//DB GENERAL
			//$id_reporte = (int)(number_format(trim($_POST["numero_reporte"]), 1, ".", ""));
			$id_reporte = (int)($_POST["numero_reporte"]);
			$numero_reporte = $id_reporte;
			$_SESSION["numero_reporte"] = $id_reporte;
			$id_usuario = $_SESSION["id_usuario"];

			//Creamos una instancia
			$nuevaConexion = new Conexion();

			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			//Eliminamos las imagenes
			$query = "SELECT ruta_imagen FROM imagenes WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"];
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			if($resultado){
				while($fila = $nuevaConexion->obtenerFilas($resultado)){
					unlink($fila[0]);		
				}	
			}		
			$query = "DELETE FROM imagenes WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
 
			
			//Eliminamos el libro de obra
			$query = "SELECT ruta_libro FROM libro WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"];
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			if($resultado){
				while($fila = $nuevaConexion->obtenerFilas($resultado)){
					unlink($fila[0]);		
				}	
			}	
			$query = "DELETE FROM libro WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);				
			
			//Eliminamos el reporte
			$reporte = "../archivos/" . $_SESSION["id_usuario"] . "/reporteDiario_N" . $_SESSION["numero_reporte"] . ".pdf";
			unlink($reporte);
			
			//Eliminamos avance
			$query = "DELETE FROM avance WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos condicion
			$query = "DELETE FROM condicion WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos cumplimiento
			$query = "DELETE FROM cumplimiento WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos equipoeecc
			$query = "DELETE FROM equipoeecc WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos horas
			$query = "DELETE FROM horas WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos localizacion
			$query = "DELETE FROM localizacion WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos personaleecc
			$query = "DELETE FROM personaleecc WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos plazo_trabajo
			$query = "DELETE FROM plazo_trabajo WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos proyecto
			$query = "DELETE FROM proyecto WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos reporte
			$query = "DELETE FROM reporte WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos texto
			$query = "DELETE FROM texto WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos trabajadores
			$query = "DELETE FROM trabajadores WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Eliminamos trabajo
			$query = "DELETE FROM trabajo WHERE id_reporte = " . $_SESSION['numero_reporte'] . " AND id_usuario = " . $_SESSION["id_usuario"]; 		
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			
			//Movimiento
			$fecha = date("Y-m-d");
			$titulo = "ELIMINAR REPORTE DIARIO";
			$descripcion = "Usuario ha eliminado el reporte diario N&deg;" . $numeroReporte . " el " . $nuevaConexion->enviarFechaHora();
			$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
			$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);	
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			$nuevaConexion->cerrarConexion();
			//Fin Movimiento
			
			//Redirigimos al menu.php
			header("Location: ../interfaz/menu.php?val=105");	
			die();
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>