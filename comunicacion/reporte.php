<?php
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
	
	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 1){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: cerrar_sesion.php");
			die();
		}
		else{	
			//movimiento
				//id_usuario
				//titulo_movimientos
				//descripcion_movimientos
				//ruta_movimientos
				//fecha_movimiento

			//personal
				//id_usuario
				//nombre_persona
				//apellido_persona
				//correo_persona
				//cargo_persona

			//usuario_proyecto
				//id_usuario
				//nombre_proyecto
				//numero_proyecto

			$arreglo[0] = array(
				"NOMBRE USUARIO", "APELLIDO USUARIO", "CORREO USUARIO", 
				"CARGO USUARIO", "NOMBRE PROYECTO", "NUMERO PROYECTO", 
				"MOVIMIENTO USUARIO", "DESCRIPCION MOVIMIENTO", 
				"RUTA DOCUMENTO", "FECHA MOVIMIENTO"
			);
			
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();

			$i = 1;

			//buscamos todos los usuario
			$query_usuarios = "SELECT id_usuario, nombre_persona, apellido_persona, correo_persona, cargo_persona FROM persona";
			$resultado_usuarios = $nueva_conexion->ejecutarConsulta($query_usuarios);
			if($resultado_usuarios){
				if($nueva_conexion->obtenerFilasAfectadas($resultado_usuarios) > 0){
					while($filas_usuarios = $nueva_conexion->obtenerFilas($resultado_usuarios)){
						//buscamos proyecto
						$query_proyecto = "SELECT nombre_proyecto, numero_proyecto FROM usuario_proyecto WHERE id_usuario = " . $filas_usuarios[0];
						$resultado_proyecto = $nueva_conexion->ejecutarConsulta($query_proyecto);
						if($resultado_proyecto){
							if($nueva_conexion->obtenerFilasAfectadas($resultado_proyecto) > 0){
								while($filas_proyecto = $nueva_conexion->obtenerFilas($resultado_proyecto)){
									//buscamos movimiento
									$query_movimiento = "SELECT titulo_movimientos, descripcion_movimientos, ruta_movimientos, fecha_movimiento FROM movimientos WHERE id_usuario = " . $filas_usuarios[0];
									$resultado_movimiento = $nueva_conexion->ejecutarConsulta($query_movimiento);
									if($resultado_movimiento){
										if($nueva_conexion->obtenerFilasAfectadas($resultado_movimiento) > 0){
											while($filas_movimiento = $nueva_conexion->obtenerFilas($resultado_movimiento)){
												$arreglo[$i][] = $filas_usuarios[1];
												$arreglo[$i][] = $filas_usuarios[2];
												$arreglo[$i][] = $filas_usuarios[3];
												$arreglo[$i][] = $filas_usuarios[4];
												$arreglo[$i][] = $filas_proyecto[0];
												$arreglo[$i][] = $filas_proyecto[1];
												$arreglo[$i][] = $filas_movimiento[0];
												$arreglo[$i][] = $filas_movimiento[1];
												$arreglo[$i][] = $filas_movimiento[2];
												$arreglo[$i][] = $filas_movimiento[3];

												$i++;
											}											
										}
									}
								}
							}
						}
					}
				}
			}

			$nueva_conexion->cerrarConexion();

			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename=export.csv;');

			$ruta = "../archivos/csv/reporte_" . date("d-m-Y") . ".csv";

			generarCSV($arreglo, $ruta, $delimitador = ';', $encapsulador = '"');
		}
	}

	function generarCSV($arreglo, $ruta, $delimitador, $encapsulador){
		$file_handle = fopen($ruta, 'w');
		foreach($arreglo as $linea){					
			fputcsv($file_handle, array_map("utf8_decode", array_map('html_entity_decode', $linea)), $delimitador, $encapsulador);			
		}
		rewind($file_handle);
		fclose($file_handle);

		$fileName = basename("reporte_" . date("d-m-Y") . ".csv");
		$filePath = "../archivos/csv/" . $fileName;

		header("Content-Type: text/csv; charset=UTF-8");	
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename=' . $fileName);			

		readfile($filePath);
	}
?>