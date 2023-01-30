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
			header("Location: cerrar_sesion.php");
			die();
		}
		else{
			//DB GENERAL
			$id_reporte = $_SESSION['numero_reporte'];
			$id_usuario = $_SESSION["numero_usuario"];
			
			//DB Proyecto
			$nombre_proyecto = htmlentities(strval(trim($_POST["nombre_proyecto"])), ENT_QUOTES);
			$numero_proyecto = htmlentities(strval(trim($_POST["numero"])), ENT_QUOTES);
			$monto_proyecto = htmlentities(strval(trim($_POST["monto"])), ENT_QUOTES);
			$mandante_proyecto = htmlentities(strval(trim($_POST["mandante"])), ENT_QUOTES);
			$contratista_proyecto = htmlentities(strval(trim($_POST["contratista"])), ENT_QUOTES);
			$inspector_proyecto = htmlentities(strval(trim($_POST["inspector_tecnico"])), ENT_QUOTES);

			//DB Reporte						
			$fecha_reporte = htmlentities(strval(trim($_POST["fecha_reporte"])), ENT_QUOTES);
			$revision_reporte = htmlentities(strval(trim($_POST["revision"])), ENT_QUOTES);

			//DB trabajo
			$sector_trabajo = htmlentities(strval(trim($_POST["sector_trabajo"])), ENT_QUOTES);
			$lugar_trabajo = htmlentities(strval(trim($_POST["lugar_trabajo"])), ENT_QUOTES);
			empty($_POST["turno_manana"]) ? $turnomanana_trabajo = "" : $turnomanana_trabajo = htmlentities(strval(trim($_POST["turno_manana"])), ENT_QUOTES);
			empty($_POST["turno_tarde"]) ? $turnotarde_trabajo = "" : $turnotarde_trabajo = htmlentities(strval(trim($_POST["turno_tarde"])), ENT_QUOTES);

			//BD plazo_trabajo
			$fechainicio_plazo = htmlentities(strval(trim($_POST["fecha_inicio"])), ENT_QUOTES);
			$fechatermino_plazo = htmlentities(strval(trim($_POST["fecha_termino"])), ENT_QUOTES);
			$plazo = (int)($_POST["plazo"]);
			$entregaterreno_plazo = htmlentities(strval(trim($_POST["entrega_terreno"])), ENT_QUOTES);
			$diasrestantes_plazo = (int)($_POST["dias_restantes"]);

			//DB Horas			
				// Horario normal
			empty($_POST["hh_inicio_normal"]) ? $inicionormal_horas = "" : $inicionormal_horas = htmlentities(strval(trim($_POST["hh_inicio_normal"])), ENT_QUOTES);
			empty($_POST["hh_termino_normal"]) ? $terminonormal_horas = "" : $terminonormal_horas = htmlentities(strval(trim($_POST["hh_termino_normal"])), ENT_QUOTES);
			empty($_POST["hh_informe_normal"]) ? $informenormal_horas = "" : $informenormal_horas = htmlentities(strval(trim($_POST["hh_informe_normal"])), ENT_QUOTES);
			empty($_POST["hh_trayecto_normal"]) ? $trayectonormal_horas = "" : $trayectonormal_horas = htmlentities(strval(trim($_POST["hh_trayecto_normal"])), ENT_QUOTES);
			empty($_POST["hh_total_normal"]) ? $totalnormal_horas = "" : $totalnormal_horas = htmlentities(strval(trim($_POST["hh_total_normal"])), ENT_QUOTES);
				//Horario extra
			empty($_POST["hh_inicio_extras"]) ? $inicioextra_horas = "" : $inicioextra_horas = htmlentities(strval(trim($_POST["hh_inicio_extras"])), ENT_QUOTES);	
			empty($_POST["hh_termino_extras"]) ? $terminoextra_horas = "" : $terminoextra_horas = htmlentities(strval(trim($_POST["hh_termino_extras"])), ENT_QUOTES);						
			empty($_POST["hh_informe_extras"]) ? $informeextra_horas = "" : $informeextra_horas = htmlentities(strval(trim($_POST["hh_informe_extras"])), ENT_QUOTES);			
			empty($_POST["hh_trayecto_extras"]) ? $trayectoextra_horas = "" : $trayectoextra_horas = htmlentities(strval(trim($_POST["hh_trayecto_extras"])), ENT_QUOTES);			
			empty($_POST["hh_total_extras"]) ? $totalextra_horas = "" : $totalextra_horas = htmlentities(strval(trim($_POST["hh_total_extras"])), ENT_QUOTES);		

			//DB personaleecc
			empty($_POST["administrador_si"]) ? $administrador_si = "" : $administrador_si = htmlentities(strval(trim($_POST["administrador_si"])), ENT_QUOTES);	
			empty($_POST["administrador_no"]) ? $administrador_no = "" : $administrador_no = htmlentities(strval(trim($_POST["administrador_no"])), ENT_QUOTES);
			empty($_POST["jterreno_si"]) ? $terreno_si = "" : $terreno_si = htmlentities(strval(trim($_POST["jterreno_si"])), ENT_QUOTES);		
			empty($_POST["jterreno_no"]) ? $terreno_no = "" : $terreno_no = htmlentities(strval(trim($_POST["jterreno_no"])), ENT_QUOTES);	
			empty($_POST["ecalidad_si"]) ? $calidad_si = "" : $calidad_si = htmlentities(strval(trim($_POST["ecalidad_si"])), ENT_QUOTES);		
			empty($_POST["ecalidad_no"]) ? $calidad_no = "" : $calidad_no = htmlentities(strval(trim($_POST["ecalidad_no"])), ENT_QUOTES);		
			empty($_POST["oocc_si"]) ? $oocc_si = "" : $oocc_si = htmlentities(strval(trim($_POST["oocc_si"])), ENT_QUOTES);		
			empty($_POST["oocc_no"]) ? $oocc_no = "" : $oocc_no = htmlentities(strval(trim($_POST["oocc_no"])), ENT_QUOTES);		
			empty($_POST["prevencionista_si"]) ? $prevencionista_si = "" : $prevencionista_si = htmlentities(strval(trim($_POST["prevencionista_si"])), ENT_QUOTES);
			empty($_POST["prevencionista_no"]) ? $prevencionista_no = "" : $prevencionista_no = htmlentities(strval(trim($_POST["prevencionista_no"])), ENT_QUOTES);

			//DB equipoeecc
			$comentario_equipoeecc = htmlentities(strval(trim($_POST["equipo_contratista"])), ENT_QUOTES);

			//DB Trabajadores
			empty($_POST["inicio_bnup"]) ? $fechainicio_trabajadores = "" : $fechainicio_trabajadores = strval($_POST["inicio_bnup"]);
			empty($_POST["termino_bnup"]) ? $fechatermino_trabajadores = "" : $fechatermino_trabajadores = strval($_POST["termino_bnup"]);
		
			//DB condicion
			empty($_POST["tiene_c"]) ? $tiene_c_condicion = "" : $tiene_c_condicion = htmlentities(strval(trim($_POST["tiene_c"])), ENT_QUOTES);
			empty($_POST["tiene_nc"]) ? $tiene_nc_condicion = "" : $tiene_nc_condicion = htmlentities(strval(trim($_POST["tiene_nc"])), ENT_QUOTES);
			empty($_POST["tiene_na"]) ? $tiene_na_condicion = "" : $tiene_na_condicion = htmlentities(strval(trim($_POST["tiene_na"])), ENT_QUOTES);
			empty($_POST["prevencion_c"]) ? $prevencion_c_condicion = "" : $prevencion_c_condicion = htmlentities(strval(trim($_POST["prevencion_c"])), ENT_QUOTES);
			empty($_POST["prevencion_nc"]) ? $prevencion_nc_condicion = "" : $prevencion_nc_condicion = htmlentities(strval(trim($_POST["prevencion_nc"])), ENT_QUOTES);
			empty($_POST["prevencion_na"]) ? $prevencion_na_condicion = "" : $prevencion_na_condicion = htmlentities(strval(trim($_POST["prevencion_na"])), ENT_QUOTES);
			empty($_POST["supervisor_c"]) ? $supervisor_c_condicion = "" : $supervisor_c_condicion = htmlentities(strval(trim($_POST["supervisor_c"])), ENT_QUOTES);
			empty($_POST["supervisor_nc"]) ? $supervisor_nc_condicion = "" : $supervisor_nc_condicion = htmlentities(strval(trim($_POST["supervisor_nc"])), ENT_QUOTES);
			empty($_POST["supervisor_na"]) ? $supervisor_na_condicion = "" : $supervisor_na_condicion = htmlentities(strval(trim($_POST["supervisor_na"])), ENT_QUOTES);

			/*echo $tiene_c_condicion."<br>";
			echo $tiene_nc_condicion."<br>";
			echo $tiene_na_condicion."<br>";
			echo $prevencion_c_condicion."<br>";
			echo $prevencion_nc_condicion."<br>";
			echo $prevencion_na_condicion."<br>";
			echo $supervisor_c_condicion."<br>";
			echo $supervisor_nc_condicion."<br>";
			echo $supervisor_na_condicion."<br>";
			exit;*/


			//DB cumplimiento
			empty($_POST["cumple_c"]) ? $cumple_c_cumplimiento = "" : $cumple_c_cumplimiento = htmlentities(strval(trim($_POST["cumple_c"])), ENT_QUOTES);
			empty($_POST["cumple_nc"]) ? $cumple_nc_cumplimiento = "" : $cumple_nc_cumplimiento = htmlentities(strval(trim($_POST["cumple_nc"])), ENT_QUOTES);
			empty($_POST["cumple_na"]) ? $cumple_na_cumplimiento = "" : $cumple_na_cumplimiento = htmlentities(strval(trim($_POST["cumple_na"])), ENT_QUOTES);
			empty($_POST["conoce_c"]) ? $conoce_c_cumplimiento = "" : $conoce_c_cumplimiento = htmlentities(strval(trim($_POST["conoce_c"])), ENT_QUOTES);
			empty($_POST["conoce_nc"]) ? $conoce_nc_cumplimiento = "" : $conoce_nc_cumplimiento = htmlentities(strval(trim($_POST["conoce_nc"])), ENT_QUOTES);
			empty($_POST["conoce_na"]) ? $conoce_na_cumplimiento = "" : $conoce_na_cumplimiento = htmlentities(strval(trim($_POST["conoce_na"])), ENT_QUOTES);

			//DB texto
			empty($_POST["actividades"]) ? $actividades_texto = "" : $actividades_texto = htmlentities(strval(trim($_POST["actividades"])), ENT_QUOTES);
			empty($_POST["hallazgos"]) ? $hallazgos_texto = "" : $hallazgos_texto = htmlentities(strval(trim($_POST["hallazgos"])), ENT_QUOTES);
			empty($_POST["actividades_calidad"]) ? $calidad_texto = "" : $calidad_texto = htmlentities(strval(trim($_POST["actividades_calidad"])), ENT_QUOTES);
			empty($_POST["topico_reunion"]) ? $topico_texto = "" : $topico_texto = htmlentities(strval(trim($_POST["topico_reunion"])), ENT_QUOTES);
			empty($_POST["incidentes"]) ? $incidentes_texto = "" : $incidentes_texto = htmlentities(strval(trim($_POST["incidentes"])), ENT_QUOTES);

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//Creamos una instancia
			$nuevaConexion = new Conexion();
			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			//Actualizamos la info de proyecto
			$query = "UPDATE proyecto SET nombre_proyecto = '" . $nombre_proyecto . "', numero_proyecto = '" . $numero_proyecto . "', monto_proyecto = '" . $monto_proyecto . "', mandante_proyecto = '" . $mandante_proyecto . "', contratista_proyecto = '" . $contratista_proyecto . "', inspecto_proyecto = '" . $inspector_proyecto . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Actualizamos la info de reporte
			$query = "UPDATE reporte SET fecha_reporte = '" . $fecha_reporte . "', revision_reporte = '". $revision_reporte ."' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			//$query = "UPDATE reporte SET fecha_reporte = '" . $fecha_reporte . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Actualizar la info de trabajo
			$query = "UPDATE trabajo SET sector_trabajo = '" . $sector_trabajo . "', lugar_trabajo = '" . $lugar_trabajo . "', turnomanana_trabajo = '" . $turnomanana_trabajo. "', turnotarde_trabajo = '" . $turnotarde_trabajo . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario; 
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Actualizar la info de plazo_trabajo
			$query = "UPDATE plazo_trabajo SET fechainicio_plazo = '" . $fechainicio_plazo . "', fechatermino_plazo = '" . $fechatermino_plazo . "', plazo_plazo = " . $plazo . ", entregaterreno_plazo = '" . $entregaterreno_plazo . "', diasrestantes_plazo = " . $diasrestantes_plazo . " WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario; 
			$resultado	= $nuevaConexion->ejecutarConsulta($query);			
	
			//Actualizar la info de horas
			$query = "UPDATE horas SET inicionormal_horas = '" . $inicionormal_horas . "', inicioextra_horas = '" . $inicioextra_horas . "', terminonormal_horas = '" . $terminonormal_horas . "', terminoextra_horas = '" . $terminoextra_horas . "', informenormal_horas = '" . $informenormal_horas . "', informeextra_horas = '" . $informeextra_horas . "', totalnormal_horas = '" . $totalnormal_horas . "', totalextra_horas = '" . $totalextra_horas . "', trayectonormal_horas = '" . $trayectonormal_horas . "', trayectoextra_horas = '" . $trayectoextra_horas . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Actualizar la info de personaleecc
			$query = "UPDATE personaleecc SET administrador_si = '" . $administrador_si . "', administrador_no = '" . $administrador_no . "', terreno_si = '" . $terreno_si . "', terreno_no = '" . $terreno_no . "', calidad_si = '" . $calidad_si . "', calidad_no = '" . $calidad_no . "', oocc_si = '" . $oocc_si . "', oocc_no = '" . $oocc_no . "', prevencionista_si = '" . $prevencionista_si . "', prevencionista_no = '" . $prevencionista_no . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);			
						
			//Actualizar la info de equipoeecc
			$query = "UPDATE equipoeecc SET comentario_equipoeecc = '" . $comentario_equipoeecc . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Actualizar la info de trabajadores
			$query = "UPDATE trabajadores SET fechainicio_trabajadores = '" . $fechainicio_trabajadores . "', fechatermino_trabajadores = '" . $fechatermino_trabajadores . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . id_usuario; 
			//$query = "UPDATE trabajadores SET contratistacantidad_trabajadores = " . $contratistacantidad_trabajadores . ", contratistainicio_trabajadores = '" . $contratistainicio_trabajadores . "', contratistatermino_trabajadores = '" . $contratistatermino_trabajadores . "', subcontratistacantidad_trabajadores = " . $subcontratistacantidad_trabajadores . ", subcontratistainicio_trabajadores = '" . $subcontratistainicio_trabajadores . "', subcontratistatermino_trabajadores = '" . $subcontratistatermino_trabajadores . "', totalcantidad_trabajadores = " . $totalcantidad_trabajadores . ", totalhoras_trabajadores = '" . $totalhoras_trabajadores . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario; 
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
		
			//Actualizar la info de condición
			$query = "UPDATE condicion SET tiene_c_condicion = '" . $tiene_c_condicion . "', tiene_nc_condicion = '" . $tiene_nc_condicion . "', tiene_na_condicion = '" . $tiene_na_condicion . "', prevencion_c_condicion = '" . $prevencion_c_condicion . "', prevencion_nc_condicion = '" . $prevencion_nc_condicion . "', prevencion_na_condicion = '" . $prevencion_na_condicion . "', supervisor_c_condicion = '" . $supervisor_c_condicion . "', supervisor_nc_condicion = '" . $supervisor_nc_condicion . "', supervisor_na_condicion = '" . $supervisor_na_condicion . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
					
			//Actualizar la info de cumplimiento
			$query = "UPDATE cumplimiento SET cumple_c_cumplimiento = '" . $cumple_c_cumplimiento . "', cumple_nc_cumplimiento = '" . $cumple_nc_cumplimiento . "', cumple_na_cumplimiento = '" . $cumple_na_cumplimiento . "', conoce_c_cumplimiento = '" . $conoce_c_cumplimiento . "', conoce_nc_cumplimiento = '" . $conoce_nc_cumplimiento . "', conoce_na_cumplimiento = '" . $conoce_na_cumplimiento . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
		
			//Actualizar la info de texto
			$query = "UPDATE texto SET actividades_texo = '" . $actividades_texto . "', hallazgos_texto = '" . $hallazgos_texto . "', calidad_texto = '" . $calidad_texto . "', topico_texto = '" . $topico_texto . "', incidentes_texto = '" . $incidentes_texto . "' WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Verificamos las imagenes
			if($_FILES["foto_1"]["name"] != "" || $_FILES["foto_2"]["name"] != "" || $_FILES["foto_3"]["name"] != "" || $_FILES["foto_4"]["name"] != "" || $_FILES["foto_5"]["name"] != "" || $_FILES["foto_6"]["name"] != ""){
				//Eliminamos las imagenes
				$query = "SELECT ruta_imagen FROM imagenes WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
				$resultado = $nuevaConexion->ejecutarConsulta($query);
				if($resultado){
					while($fila = $nuevaConexion->obtenerFilas($resultado)){
						unlink($fila[0]);		
					}	
				}		
				$query = "DELETE FROM imagenes WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario; 		
				$resultado = $nuevaConexion->ejecutarConsulta($query);

				//Subimos las imagenes nuevas
				$ruta = "../imagenes/";
				for($i=1;$i<=6;$i++){
					$nombre = "foto_" . $i;
					$descripcion = "descripcion_imagen_" . $i;
					$descripcion_imagen = htmlentities(strval(trim($_POST[$descripcion])), ENT_QUOTES);
					if($_FILES[$nombre]['name'] != ""){
						$tmp = explode('.', $_FILES[$nombre]['name']);
						$extension = end($tmp);
						$nombre_imagen = "foto_" . $i . "_R_" . $id_reporte . "." . $extension;
						move_uploaded_file($_FILES[$nombre]['tmp_name'], $ruta . $nombre_imagen);
						//Actualizamos la info de imagenes
						$rutaImagen = $ruta . $nombre_imagen;
						$query = "INSERT INTO imagenes (id_reporte, id_usuario, nombre_imagen, ruta_imagen, descripcion_imagen) VALUES ('" . $id_reporte . "', " . $id_usuario . ", '" . $nombre_imagen . "', '" . $rutaImagen . "', '" . $descripcion_imagen . "')";
						$resultado	= $nuevaConexion->ejecutarConsulta($query);		
					}			
				}				
			}
			
			//Verificamos el libro de obra
			if($_FILES['libro_obra']['name'] != ""){
				//Eliminamos el libro de obra
				$query = "SELECT ruta_libro FROM libro WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
				$resultado = $nuevaConexion->ejecutarConsulta($query);
				if($resultado){
					while($fila = $nuevaConexion->obtenerFilas($resultado)){
						unlink($fila[0]);		
					}	
				}	
				$query = "DELETE FROM libro WHERE id_reporte = " . $id_reporte . " AND id_usuario = " . $id_usuario;
				$resultado = $nuevaConexion->ejecutarConsulta($query);				

				//Subimos el libro nuevo
				$tmp = explode('.', $_FILES['libro_obra']['name']);
				$extension = end($tmp);
				$nombre_libro = "libro_" . "R" . $id_reporte . "." . $extension;
				$descripcion_libro = htmlentities(strval(trim($_POST["descripcion_libro"])), ENT_QUOTES);
				move_uploaded_file($_FILES['libro_obra']['tmp_name'], $ruta . $nombre_libro);
				//Actualizamos la info de libro
				$rutaLibro = $ruta . $nombre_libro;
				$query = "INSERT INTO libro (id_reporte, id_usuario, nombre_libro, ruta_libro, descripcion_libro) VALUES ('" . $id_reporte . "', " . $id_usuario . ", '" . $nombre_libro . "', '" . $rutaLibro . "', '" . $descripcion_libro . "')";
				$resultado	= $nuevaConexion->ejecutarConsulta($query);
			}

			//Eliminamos el reporte
			$reporte = "../archivos/" . $id_usuario . "/reporteDiario_N" . $id_reporte . ".pdf";
			unlink($reporte);
			
			//Movimiento
			$fecha = date("Y-m-d");
			$titulo = "ACTUALIZAR REPORTE DIARIO";
			$descripcion = "Usuario ha actualizado el reporte diario N&deg;" . $id_reporte . " el " . $nuevaConexion->enviarFechaHora();
			$array_descripcion = array($id_usuario, $titulo, $descripcion, "", $fecha);
			$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);	
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			$nuevaConexion->cerrarConexion();
			//Fin Movimiento
			
			//Redirigimos al informe_pdf_control.php
			header("Location: informe_pdf_control.php");
			die();
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>