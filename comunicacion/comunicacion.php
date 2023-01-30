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
			$_SESSION["numero_reporte"] = $id_reporte;
			$id_usuario = $_SESSION["id_usuario"];

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
			empty($_POST["hh_inicio_normal"]) ? $inicionormal_horas = "" : $inicionormal_horas = htmlentities(strval(trim($_POST["hh_inicio_normal"])), ENT_QUOTES);			
			empty($_POST["hh_inicio_extras"]) ? $inicioextra_horas = "" : $inicioextra_horas = htmlentities(strval(trim($_POST["hh_inicio_extras"])), ENT_QUOTES);			
			empty($_POST["hh_termino_normal"]) ? $terminonormal_horas = "" : $terminonormal_horas = htmlentities(strval(trim($_POST["hh_termino_normal"])), ENT_QUOTES);			
			empty($_POST["hh_termino_extras"]) ? $terminoextra_horas = "" : $terminoextra_horas = htmlentities(strval(trim($_POST["hh_termino_extras"])), ENT_QUOTES);			
			empty($_POST["hh_informe_normal"]) ? $informenormal_horas = "" : $informenormal_horas = htmlentities(strval(trim($_POST["hh_informe_normal"])), ENT_QUOTES);			
			empty($_POST["hh_informe_extras"]) ? $informeextra_horas = "" : $informeextra_horas = htmlentities(strval(trim($_POST["hh_informe_extras"])), ENT_QUOTES);			
			empty($_POST["hh_total_normal"]) ? $totalnormal_horas = "" : $totalnormal_horas = htmlentities(strval(trim($_POST["hh_total_normal"])), ENT_QUOTES);			
			empty($_POST["hh_total_extras"]) ? $totalextra_horas = "" : $totalextra_horas = htmlentities(strval(trim($_POST["hh_total_extras"])), ENT_QUOTES);			
			empty($_POST["hh_trayecto_normal"]) ? $trayectonormal_horas = "" : $trayectonormal_horas = htmlentities(strval(trim($_POST["hh_trayecto_normal"])), ENT_QUOTES);			
			empty($_POST["hh_trayecto_extras"]) ? $trayectoextra_horas = "" : $trayectoextra_horas = htmlentities(strval(trim($_POST["hh_trayecto_extras"])), ENT_QUOTES);

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
			empty($_POST["inicio_bnup"]) ? $fechainicio_trabajadores = "" : $fechainicio_trabajadores = htmlentities(strval(trim($_POST["inicio_bnup"])), ENT_QUOTES);
			empty($_POST["termino_bnup"]) ? $fechatermino_trabajadores = "" : $fechatermino_trabajadores = htmlentities(strval(trim($_POST["termino_bnup"])), ENT_QUOTES);			
			
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

			//Creamos una instancia
			$nuevaConexion = new Conexion();

			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			//Guardar la info de proyecto
			$array_proyecto = array($id_reporte, $_SESSION["id_usuario"], $nombre_proyecto, $numero_proyecto, $monto_proyecto, $mandante_proyecto, $contratista_proyecto, $inspector_proyecto);
			$query = $nuevaConexion->generamosConsulta("proyecto", $array_proyecto);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Guardamos la info de reporte
			$array_reporte = array($id_reporte, $_SESSION["id_usuario"], $fecha_reporte, $revision_reporte);
			$query = $nuevaConexion->generamosConsulta("reporte", $array_reporte);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
					
			//Guardar la info de trabajo
			$array_trabajo = array($id_reporte, $_SESSION["id_usuario"], $sector_trabajo, $lugar_trabajo, $turnomanana_trabajo, $turnotarde_trabajo);
			$query = $nuevaConexion->generamosConsulta("trabajo", $array_trabajo);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Guardar la info de plazo_trabajo
			$array_plazo_trabajo = array($id_reporte, $_SESSION["id_usuario"], $fechainicio_plazo, $fechatermino_plazo, $plazo, $entregaterreno_plazo, $diasrestantes_plazo);
			$query = $nuevaConexion->generamosConsulta("plazo_trabajo", $array_plazo_trabajo);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Guardar la info de horas
			$array_horas = array($id_reporte, $_SESSION["id_usuario"], $inicionormal_horas, $inicioextra_horas, $terminonormal_horas, $terminoextra_horas, $informenormal_horas, $informeextra_horas, $totalnormal_horas, $totalextra_horas, $trayectonormal_horas, $trayectoextra_horas);
			$query = $nuevaConexion->generamosConsulta("horas", $array_horas);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Guardar la info de personaleecc
			$array_personaleecc = array($id_reporte, $_SESSION["id_usuario"], $administrador_si, $administrador_no, $terreno_si, $terreno_no, $calidad_si, $calidad_no, $oocc_si, $oocc_no, $prevencionista_si, $prevencionista_no);
			$query = $nuevaConexion->generamosConsulta("personaleecc", $array_personaleecc);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Guardar la info de equipoeecc
			$array_equipoeecc = array($id_reporte, $_SESSION["id_usuario"], $comentario_equipoeecc);
			$query = $nuevaConexion->generamosConsulta("equipoeecc", $array_equipoeecc);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Guardar la info de trabajadores
			$array_trabajadores = array($id_reporte, $_SESSION["id_usuario"], $fechainicio_trabajadores, $fechatermino_trabajadores);
			$query = $nuevaConexion->generamosConsulta("trabajadores", $array_trabajadores);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);			

			//Guardar la info de condición
			$array_condicion = array($id_reporte, $_SESSION["id_usuario"], $tiene_c_condicion, $tiene_nc_condicion, $tiene_na_condicion, $prevencion_c_condicion, $prevencion_nc_condicion, $prevencion_na_condicion, $supervisor_c_condicion, $supervisor_nc_condicion, $supervisor_na_condicion);
			$query = $nuevaConexion->generamosConsulta("condicion", $array_condicion);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Guardar la info de cumplimiento
			$array_cumplimiento = array($id_reporte, $_SESSION["id_usuario"], $cumple_c_cumplimiento, $cumple_nc_cumplimiento, $cumple_na_cumplimiento, $conoce_c_cumplimiento, $conoce_nc_cumplimiento, $conoce_na_cumplimiento);
			$query = $nuevaConexion->generamosConsulta("cumplimiento", $array_cumplimiento);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
		
			//Guardar la info de texto
			$array_texto = array($id_reporte, $_SESSION["id_usuario"], $actividades_texto, $hallazgos_texto, $calidad_texto, $topico_texto, $incidentes_texto);
			$query = $nuevaConexion->generamosConsulta("texto", $array_texto);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Guardar la info de avance
			//$array_avance = array($numero_reporte, $_SESSION["id_usuario"], $avance_ayer, $avance_hoy, $acumulado);
			//$query = $nuevaConexion->generamosConsulta("avance", $array_avance);
			//$resultado	= $nuevaConexion->ejecutarConsulta($query);
		
			//Subir imagenes al servidor
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
					//Guardar la info de imagenes
					$array_imagenes = array($id_reporte, $_SESSION["id_usuario"], $nombre_imagen, $ruta.$nombre_imagen, $descripcion_imagen);
					$query = $nuevaConexion->generamosConsulta("imagenes", $array_imagenes);
					$resultado	= $nuevaConexion->ejecutarConsulta($query);		
				}			
			}

			$tmp = explode('.', $_FILES['libro_obra']['name']);
			$extension = end($tmp);
			$nombre_libro = "libro_" . "R" . $id_reporte . "." . $extension;
			$descripcion_libro = htmlentities(strval(trim($_POST["descripcion_libro"])), ENT_QUOTES);
			move_uploaded_file($_FILES['libro_obra']['tmp_name'], $ruta . $nombre_libro);
			//Guardar la info de libro
			$array_libro = array($id_reporte, $_SESSION["id_usuario"], $nombre_libro, $ruta.$nombre_libro, $descripcion_libro);
			$query = $nuevaConexion->generamosConsulta("libro", $array_libro);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Guardamos la geolocalizacion
			$ubicacion = geolocalizacion();
			$region = explode(" ", $ubicacion[1]);
			if(strcmp($region[1], "Metropolitan") == 0){
				$region[1] = "metropolitana";
			}
			$array_geolocalizacion = array($_SESSION["id_usuario"], $id_reporte, $ubicacion[0], $region[1], "CHILE");
			$query = $nuevaConexion->generamosConsulta("localizacion", $array_geolocalizacion);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			
			//Cerrar la conexión
			$nuevaConexion->cerrarConexion();		
			
			//Redirigimos al generar_pdf.php
			//header("Location: generar_pdf.php");
			header("Location: informe_pdf.php");
			die();
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}

	function geolocalizacion(){
		//código para obtener la dirección IP del usuario
	 	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$user_ip = $ip;
		
		$array_user_ip = explode(".", $user_ip);
		if(intval($array_user_ip[0]) == 192 && intval($array_user_ip[1]) == 168 && intval($array_user_ip[2]) == 1){
			$user_ip = '181.43.150.90';
		}
				
		$url = "http://ipinfo.io/".$user_ip;
		$ip_info = json_decode(file_get_contents($url));

		$city = $ip_info->city;
		$region = $ip_info->region;
		$country = $ip_info->country;
		
		$ubicacion[] = $city;
		$ubicacion[] = $region;
		$ubicacion[] = $country;
		return($ubicacion);
	}
?>