<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	require("../librerias/fpdf/fpdf.php");
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
			class PDF extends FPDF{
				//Cabecera de la página
				function Header(){
					$this->Image("../imagenes/logos/Bogado.jpg", 15, 15, 40);
					$this->SetFont("Arial", "B", 12);
					$this->MultiCell(50, 30, " ", 1, "C");
					$this->SetXY(60,10);
					$this->MultiCell(95.9, 20, "REGISTRO", "T", "C");
					$this->SetXY(60,20);
					$this->MultiCell(95.9, 20, utf8_decode("REPORTE DIARIO DE INSPECCIÓN DE OBRA"), "B", "C");
					$this->SetXY(155.9,10);
					$this->MultiCell(0, 30, " ", 1, "C");
					$this->SetXY(155.9, 10);
					$this->Image("../imagenes/logos/aguasdelvalle.png", 161, 14, 40);
					$this->SetFont("Arial", "B", 8);
					$this->SetXY(155.9,25);
					$this->MultiCell(15, 5, utf8_decode("Emisión:"), "TR", "L");
					$this->SetXY(170.9,25);
					$this->MultiCell(0, 5, " ", "T", "L");
					$this->SetXY(155.9,30);
					$this->MultiCell(15, 5, utf8_decode("Revisión:"), "TR", "L");
					$this->SetXY(170.9,30);
					$this->MultiCell(0, 5, " ", "T", "L");
					$this->SetXY(155.9,35);
					$this->MultiCell(15, 5, "Fecha:", "TR", "L");
					$this->SetXY(170.9,35);
					$this->MultiCell(0, 5, " ", "T", "L");
					//$this->Ln(50);
				}
				// Pie de página
				function Footer(){
				    // Posición: a 1,5 cm del final
				    $this->SetY(-15);
				    // Arial italic 8
				    $this->SetFont('Arial','',8);
				    // Número de página
				    $this->MultiCell(0,5,utf8_decode("Este es un documento confidencial de Bogado Ingenieros Consultores SpA."), "LRT", "C");
				    $this->Ln(0.1);
				    $this->MultiCell(0,5,utf8_decode("No puede por tanto, ser usado por terceros sin la expresa autorización de Bogado Ingenieros Consultores SpA."), "LRB", "C");
				}
			}

			$pdf = new PDF();
			$pdf->AddPage("P", "Legal", 0);
			$y = 45;
			$h = 10;

			//Creamos una instancia
			$nuevaConexion = new Conexion();
			//Creamos una nueva conexión
			$nuevaConexion->crearConexion();

			$numeroReporte = $_SESSION["numero_reporte"];

			//Obtenemos los datos de proyecto
			$query = "SELECT * FROM proyecto WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			if($result){
				$fila_proyecto = $nuevaConexion->obtenerFilas($result);
				//Nombre proyecto
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(25, $h,"PROYECTO", "TL", "C", true);
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetTextColor(0);
				$pdf->SetXY(35,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[3]))), "TRL", "C");

				//N°, Monto, Mandante, Contratista Y Inspector Técnico
				$y = $y + 10.24;
				$h = 5;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(29.18, $h,strtoupper(utf8_decode(html_entity_decode("N°"))), "LT", "C", true);		
				$pdf->SetXY(39.18,$y);
				$pdf->MultiCell(29.18, $h,strtoupper(utf8_decode(html_entity_decode("MONTO"))), "LT", "C", true);
				$pdf->SetXY(68.36,$y);
				$pdf->MultiCell(39.18, $h,strtoupper(utf8_decode(html_entity_decode("MANDANTE"))), "LT", "C", true);
				$pdf->SetXY(107.54,$y);
				$pdf->MultiCell(39.18, $h,strtoupper(utf8_decode(html_entity_decode("CONTRATISTA"))), "LT", "C", true);
				$pdf->SetXY(146.72,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("INSPECTOR TÉCNICO"))), "LTR", "C", true);

				$y = $y + 5.24;
				$h = 5;
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(29.18, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[4]))), "LT", "C");		
				$pdf->SetXY(39.18,$y);
				$pdf->MultiCell(29.18, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[5]))), "LT", "C");
				$pdf->SetXY(68.36,$y);
				$pdf->MultiCell(39.18, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[6]))), "LT", "C");
				$pdf->SetXY(107.54,$y);
				$pdf->MultiCell(39.18, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[7]))), "LT", "C");
				$pdf->SetXY(146.72,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[8]))), "LTR", "C");

				$nuevaConexion->liberarConsulta($result);
			}

			//Obtenemos los datos de reporte y trabajo
			$query = "SELECT * FROM reporte WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$query2 = "SELECT * FROM trabajo WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			$result2 = $nuevaConexion->ejecutarConsulta($query2);
			if($result && $result2){
				$fila_reporte = $nuevaConexion->obtenerFilas($result);
				$fila_trabajo = $nuevaConexion->obtenerFilas($result2);
				//N° Reporte, Fecha reporte, Sector trabajo, Lugar trabajo, Turno mañana, Turno tarde
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode("N° DE REPORTE"))), "LT", "C", true);		
				$pdf->SetXY(35,$y);
				$pdf->MultiCell(31, $h,strtoupper(utf8_decode(html_entity_decode("FECHA DE REPORTE"))), "LT", "C", true);
				$pdf->SetXY(66,$y);
				$pdf->MultiCell(45, $h,strtoupper(utf8_decode(html_entity_decode("SECTOR DE TRABAJO/CALLE"))), "LT", "C", true);
				$pdf->SetXY(111,$y);
				$pdf->MultiCell(36, $h,strtoupper(utf8_decode(html_entity_decode("LUGAR DE TRABAJO"))), "LT", "C", true);
				$pdf->SetXY(147,$y);
				$pdf->MultiCell(29, $h,strtoupper(utf8_decode(html_entity_decode("TURNO MAÑANA"))), "LT", "C", true);
				$pdf->SetXY(176,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TURNO TARDE"))), "LTR", "C", true);

				$y = $y + 5.24;
				$h = 5;
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_reporte[1]))), "LT", "C");		
				$pdf->SetXY(35,$y);
				$pdf->MultiCell(31, $h,strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_reporte[3])))), "LT", "C");
				$pdf->SetXY(66,$y);
				$pdf->MultiCell(45, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[3]))), "LT", "C");
				$pdf->SetXY(111,$y);
				$pdf->MultiCell(36, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[4]))), "LT", "C");
				$pdf->SetXY(147,$y);
				$pdf->MultiCell(29, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[5]))), "LT", "C");
				$pdf->SetXY(176,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[6]))), "LTR", "C");

				$nuevaConexion->liberarConsulta($result);
				$nuevaConexion->liberarConsulta($result2);
			}

			//Obtenemos los datos de trabajadores, avances y horas
			$query = "SELECT * FROM trabajadores WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$query2 = "SELECT * FROM avance WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$query3 = "SELECT * FROM horas WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			$result2 = $nuevaConexion->ejecutarConsulta($query2);
			$result3 = $nuevaConexion->ejecutarConsulta($query3);
			if($result && $result2 && $result3){
				$fila_trabajadores = $nuevaConexion->obtenerFilas($result);
				$fila_avance = $nuevaConexion->obtenerFilas($result2);
				$fila_horas = $nuevaConexion->obtenerFilas($result3);
				//Fecha inicio, Dias totales, Dias restantes, Avance, Trabajadores en faena, Horas
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(28, $h+5,strtoupper(utf8_decode(html_entity_decode("FECHA DE INICIO"))), "LT", "C", true);		
				$pdf->SetXY(38,$y);
				$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode("DÍAS TOTALES"))), "LT", "C", true);
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode("DÍAS RESTANTES"))), "LT", "C", true);
				$pdf->SetXY(78,$y);
				$pdf->MultiCell(23, $h+5,strtoupper(utf8_decode(html_entity_decode("AVANCE (%)"))), "LT", "C", true);
				$pdf->SetXY(101,$y);
				$pdf->MultiCell(36, $h,strtoupper(utf8_decode(html_entity_decode("TRABAJADORES EN FAENA"))), "LT", "C", true);
				$pdf->SetXY(137,$y);
				$pdf->MultiCell(0, $h+5,strtoupper(utf8_decode(html_entity_decode("HORAS"))), "LTR", "C", true);

				$pdf->SetFont("Arial", "B", 7);
				$pdf->SetXY(78,$y+10);
				$pdf->MultiCell(10.5, $h,strtoupper(utf8_decode(html_entity_decode("AYER"))), "LT", "C", true);
				$pdf->SetXY(78,$y+15);
				$pdf->MultiCell(10.5, $h,strtoupper(utf8_decode(html_entity_decode("HOY"))), "LT", "C", true);
				$pdf->SetXY(78,$y+20);
				$pdf->MultiCell(10.5, $h,strtoupper(utf8_decode(html_entity_decode("ACUM."))), "LT", "C", true);

				$pdf->SetXY(101,$y+10);
				$pdf->MultiCell(26, $h,strtoupper(utf8_decode(html_entity_decode("CONTRATISTA"))), "LT", "C", true);
				$pdf->SetXY(101,$y+15);
				$pdf->MultiCell(26, $h,strtoupper(utf8_decode(html_entity_decode("SUB-CONTRATISTA"))), "LT", "C", true);
				$pdf->SetXY(101,$y+20);
				$pdf->MultiCell(26, $h,strtoupper(utf8_decode(html_entity_decode("TOTAL"))), "LT", "C", true);


				$pdf->SetXY(137,$y+10);
				$pdf->MultiCell(12.8, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C", true);
				$pdf->SetXY(150,$y+10);
				$pdf->MultiCell(11, $h,strtoupper(utf8_decode(html_entity_decode("INICIO"))), "LT", "C", true);
				$pdf->SetXY(161,$y+10);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode("TERMINO"))), "LT", "C", true);
				$pdf->SetXY(177,$y+10);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode("INFORME"))), "LT", "C", true);
				$pdf->SetXY(193,$y+10);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TOTAL"))), "LTR", "C", true);
				
				$pdf->SetXY(137,$y+15);
				$pdf->MultiCell(12.8, $h,strtoupper(utf8_decode(html_entity_decode("NORMAL"))), "LT", "C", true);
				$pdf->SetXY(137,$y+20);
				$pdf->MultiCell(12.8, $h,strtoupper(utf8_decode(html_entity_decode("EXTRAS"))), "LT", "C", true);
				
				$y = $y + 10.24;
				$h = 5;
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(28, $h+10,strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_reporte[4])))), "LT", "C");		
				$pdf->SetXY(38,$y);
				$pdf->MultiCell(20, $h+10,strtoupper(utf8_decode(html_entity_decode($fila_reporte[5]))), "LT", "C");
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(20, $h+10,strtoupper(utf8_decode(html_entity_decode($fila_reporte[6]))), "LT", "C");		
				$pdf->SetXY(88.5,$y);
				$pdf->MultiCell(12.5, $h,strtoupper(utf8_decode(html_entity_decode($fila_avance[3]))), "LT", "C");
				$pdf->SetXY(88.5,$y+5);
				$pdf->MultiCell(12.5, $h,strtoupper(utf8_decode(html_entity_decode($fila_avance[4]))), "LT", "C");
				$pdf->SetXY(88.5,$y+10);
				$pdf->MultiCell(12.5, $h,strtoupper(utf8_decode(html_entity_decode($fila_avance[5]))), "LT", "C");

				$pdf->SetXY(127,$y);
				$pdf->MultiCell(9.9, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajadores[3]))), "LT", "C");
				$pdf->SetXY(127,$y+5);
				$pdf->MultiCell(9.9, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajadores[4]))), "LT", "C");
				$pdf->SetXY(127,$y+10);
				$pdf->MultiCell(9.9, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajadores[5]))), "LT", "C");

				$pdf->SetXY(149.8,$y+5);
				$pdf->MultiCell(11, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[3]))), "LT", "C");
				$pdf->SetXY(160.8,$y+5);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[5]))), "LT", "C");
				$pdf->SetXY(176.8,$y+5);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[7]))), "LT", "C");
				$pdf->SetXY(192.8,$y+5);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[9]))), "LRT", "C");

				$pdf->SetXY(149.8,$y+10);
				$pdf->MultiCell(11, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[4]))), "LT", "C");
				$pdf->SetXY(160.8,$y+10);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[6]))), "LT", "C");
				$pdf->SetXY(176.8,$y+10);
				$pdf->MultiCell(16, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[8]))), "LT", "C");
				$pdf->SetXY(192.8,$y+10);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[10]))), "LRT", "C");		

				$nuevaConexion->liberarConsulta($result);
				$nuevaConexion->liberarConsulta($result2);
				$nuevaConexion->liberarConsulta($result3);
			}


			//Obtenemos los datos de equipoeecc y personaleecc
			$query = "SELECT * FROM equipoeecc WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$query2 = "SELECT * FROM personaleecc WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			$result2 = $nuevaConexion->ejecutarConsulta($query2);
			if($result && $result2){
				$fila_equipoeecc = $nuevaConexion->obtenerFilas($result);
				$fila_personaleecc = $nuevaConexion->obtenerFilas($result2);
				//equipos ee.cc y personal ee.cc
				$y = $y + 15;
				$h = 5;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(97.95, $h,strtoupper(utf8_decode(html_entity_decode("PERSONAL EE.CC"))), "LT", "C", true);		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("EQUIPO EE.CC"))), "LRT", "C", true);
				
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h+5,strtoupper(utf8_decode(html_entity_decode("DESCRIPCIÓN"))), "LT", "C", true);		
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h+5,strtoupper(utf8_decode(html_entity_decode("CANTIDAD"))), "LT", "C", true);
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode("TIEMPO DE UTILIZACIÓN"))), "LT", "C", true);
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h+5,strtoupper(utf8_decode(html_entity_decode("DESCRIPCIÓN"))), "LT", "C", true);
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h+5,strtoupper(utf8_decode(html_entity_decode("CANTIDAD"))), "LT", "C", true);
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TIEMPO DE UTILIZACIÓN"))), "LTR", "C", true);

				$pdf->SetFont("Arial", "B", 7);
				$pdf->SetFillColor(0, 161, 76);
				$y = $y + 10.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("ADMINISTRADOR"))), "LT", "C");		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("CAMIÓN"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[3]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[4]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[3]))), "LT", "C");
				$pdf->SetXY(180.9, $y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[4]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("JEFE DE TERRENO"))), "LT", "C");		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("CAMIÓN PLUMA"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);		
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[5]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[6]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[5]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[6]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("ENCARGADO DE CALIDAD"))), "LT", "C");		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("CAMIONETAS"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[7]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[8]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[7]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[8]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("SUPERVISOR OOCC"))), "LT", "C");		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("RETRO EXCAVADORA"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[9]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[10]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[9]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[10]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("PREVENCIONISTA"))), "LT", "C");		
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("MINI CARGADOR"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[11]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[12]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[11]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[12]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("OPERADOR CAMIÓN PLUMA"))), "LT", "C");
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("TERMOFUSIONADORA"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[13]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[14]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[13]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[14]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("OPERADOR CAMIÓN TOLVA"))), "LT", "C");
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("EXCAVADORA"))), "LT", "C");
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[15]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[16]))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[15]))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[16]))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("OPERADOR MULTIFUNCIONAL"))), "LT", "C");		
				$pdf->SetXY(57.95,$y);
				$pdf->SetFont("Arial", "", 8);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[17]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[18]))), "LT", "C");
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("MAESTROS PRIMERA"))), "LT", "C");		
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[19]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[20]))), "LT", "C");
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LTR", "C");

				$pdf->SetFont("Arial", "B", 7);
				$y = $y + 5.24;
				$h = 5;
				$pdf->SetTextColor(0);
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode("JORNAL"))), "LT", "C");		
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(57.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[21]))), "LT", "C");
				$pdf->SetXY(82.95,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[22]))), "LT", "C");
				$pdf->SetXY(107.95,$y);
				$pdf->MultiCell(47.95, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(155.9,$y);
				$pdf->MultiCell(25, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(180.9,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LTR", "C");
					

				$nuevaConexion->liberarConsulta($result);
				$nuevaConexion->liberarConsulta($result2);
			}

			//Obtenemos los datos de condición y cumplimiento
			$query = "SELECT * FROM condicion WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$query2 = "SELECT * FROM cumplimiento WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			$result2 = $nuevaConexion->ejecutarConsulta($query2);
			if($result && $result2){
				$fila_condicion = $nuevaConexion->obtenerFilas($result);
				$fila_cumplimiento = $nuevaConexion->obtenerFilas($result2);
				//Condición y cumplimiento
				$y = $y + 5;
				$h = 5;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(48, $h,strtoupper(utf8_decode(html_entity_decode("CONDICIONES DE OBRA"))), "LT", "C", true);		
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("C"))), "LT", "C", true);
				$pdf->SetXY(74.4,$y);
				$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("NC"))), "LT", "C", true);
				$pdf->SetXY(90.8,$y);
				$pdf->MultiCell(17.3, $h,strtoupper(utf8_decode(html_entity_decode("NA"))), "LT", "C", true);
				$pdf->SetXY(108.1,$y);
				$pdf->MultiCell(47.7, $h,strtoupper(utf8_decode(html_entity_decode("CUMPLIMIENTOS"))), "LT", "C", true);		
				$pdf->SetXY(155.8,$y);
				$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("C"))), "LT", "C", true);
				$pdf->SetXY(172.2,$y);
				$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("NC"))), "LT", "C", true);
				$pdf->SetXY(188.6,$y);
				$pdf->MultiCell(17.3, $h,strtoupper(utf8_decode(html_entity_decode("NA"))), "LTR", "C", true);
				
				if($fila_condicion[2] == 0){
					$tiene_condicion = "TIENE ARP";
				}
				else{
					$tiene_condicion = "TIENE PTS";
				}

				$y = $y + 5;
				$h = 5;
				$pdf->SetFont("Arial", "B", 7);
				$pdf->SetTextColor(0);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(48, $h+5,strtoupper(utf8_decode(html_entity_decode($tiene_condicion))), "LT", "C");		
				$pdf->SetXY(108.1,$y);
				$pdf->MultiCell(47.7, $h,strtoupper(utf8_decode(html_entity_decode("CUMPLE PROCEDIMIENTO U OTRO DOCUMENTO"))), "LT", "C");		
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[4]))), "LT", "C");
				$pdf->SetXY(74.4,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[5]))), "LT", "C");
				$pdf->SetXY(90.8,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[6]))), "LT", "C");
				$pdf->SetXY(155.8,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[3]))), "LT", "C");
				$pdf->SetXY(172.2,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[4]))), "LT", "C");
				$pdf->SetXY(188.6,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[5]))), "LTR", "C");
				
				$y = $y + 10;
				$h = 5;
				$pdf->SetFont("Arial", "B", 7);
				$pdf->SetTextColor(0);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(48, $h+5,strtoupper(utf8_decode(html_entity_decode("PREVENCIONISTA EN TERRENO"))), "LT", "C");		
				$pdf->SetXY(108.1,$y);
				$pdf->MultiCell(47.7, $h,strtoupper(utf8_decode(html_entity_decode("PERSONAL CONOCE LOS PROCEDIMIENTOS"))), "LT", "C");		
				$pdf->SetFont("Arial", "", 8);
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[7]))), "LT", "C");
				$pdf->SetXY(74.4,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[8]))), "LT", "C");
				$pdf->SetXY(90.8,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[9]))), "LT", "C");		
				$pdf->SetXY(155.8,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[6]))), "LT", "C");
				$pdf->SetXY(172.2,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[7]))), "LT", "C");
				$pdf->SetXY(188.6,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[8]))), "LTR", "C");

				$y = $y + 10;
				$h = 5;
				$pdf->SetFont("Arial", "B", 7);
				$pdf->SetTextColor(0);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(48, $h+5,strtoupper(utf8_decode(html_entity_decode("SUPERVISOR EN EL ÁREA"))), "LT", "C");	
				$pdf->SetFont("Arial", "", 8);	
				$pdf->SetXY(58,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[10]))), "LT", "C");
				$pdf->SetXY(74.4,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[11]))), "LT", "C");
				$pdf->SetXY(90.8,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode($fila_condicion[12]))), "LT", "C");
				$pdf->SetXY(108.1,$y);
				$pdf->MultiCell(47.7, $h+5,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");		
				$pdf->SetXY(155.8,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(172.2,$y);
				$pdf->MultiCell(16.4, $h+5,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
				$pdf->SetXY(188.6,$y);
				$pdf->MultiCell(17.3, $h+5,strtoupper(utf8_decode(html_entity_decode(""))), "LTR", "C");

				$nuevaConexion->liberarConsulta($result);
				$nuevaConexion->liberarConsulta($result2);
			}

			//Obtenemos los datos de texto
			$query = "SELECT * FROM texto WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			if($result){
				$fila_condicion = $nuevaConexion->obtenerFilas($result);
				//Texto
				$y = $y + 10;
				$h = 5;
				$h_texto = 18;
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("PRINCIPALES ACTIVIDADES DEL DÍA"))), "LTR", "C", true);	
				$y = $y + 5;
				$pdf->SetTextColor(20);		
				$pdf->SetFont("Arial", "", 7);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h_texto-calculoAltura($fila_condicion[3]),strtoupper(utf8_decode(html_entity_decode($fila_condicion[3]))), "LTR", "L");
				$y = $y + 18;		
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("HALLAZGOS"))), "LTR", "C", true);		
				$y = $y + 5;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 7);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h_texto-calculoAltura($fila_condicion[4]),strtoupper(utf8_decode(html_entity_decode($fila_condicion[4]))), "LTR", "L");
				$y = $y + 18;		
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("ACTIVIDADES CONTROL DE CALIDAD"))), "LTR", "C", true);		
				$y = $y + 5;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 7);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h_texto-calculoAltura($fila_condicion[5]),strtoupper(utf8_decode(html_entity_decode($fila_condicion[5]))), "LTR", "L");
				$y = $y + 18;		
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TÓPICO REUNIÓN (SI EXISTE)"))), "LTR", "C", true);		
				$y = $y + 5;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 7);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h_texto-calculoAltura($fila_condicion[6]),strtoupper(utf8_decode(html_entity_decode($fila_condicion[6]))), "LTR", "L");
				$y = $y + 18;		
				$pdf->SetFont("Arial", "B", 8);
				$pdf->SetFillColor(0, 161, 76);
				$pdf->SetTextColor(255,255,255);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("INCIDENTES / CONDICIÓN DE RIESGOS / NO CONFORMIDAD"))), "LTR", "C", true);		
				$y = $y + 5;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 7);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(0, $h_texto-calculoAltura($fila_condicion[7]),strtoupper(utf8_decode(html_entity_decode($fila_condicion[7]))), "LTRB", "L");

				$nuevaConexion->liberarConsulta($result);
			}

			//Firmas
			$y = $y + 18;		
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(107.95, $h,strtoupper(utf8_decode(html_entity_decode("ELABORADO POR:"))), "LT", "C", true);	
			$pdf->SetXY(117.95,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("APROBADO POR:"))), "LTR", "C", true);		
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(107.95, $h+1, strtoupper(utf8_decode(html_entity_decode("NOMBRE:"))), "LTR", "L");
			$pdf->SetXY(117.95,$y);
			$pdf->MultiCell(0, $h+1, strtoupper(utf8_decode(html_entity_decode("NOMBRE:"))), "RT", "L");
			$y = $y + 6;
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(107.95, $h, strtoupper(utf8_decode(html_entity_decode("FIRMA:"))), "LRB", "L");
			$pdf->SetXY(117.95,$y);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode("FIRMA:"))), "RB", "L");
			
			$pdf->AddPage("P", "Legal", 0);
			
			//Obtenemos los datos de imagenes
			$query = "SELECT * FROM imagenes WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			if($result){		
				//Imagenes
				$y = 45;
				$h = 90;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 8);		
				$pdf->SetXY(10,$y);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");
				$pdf->SetXY(112.95,$y);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");
				$pdf->SetXY(10,$y+$h+10);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");
				$pdf->SetXY(112.95,$y+$h+10);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");
				$pdf->SetXY(10,$y+$h+$h+20);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");
				$pdf->SetXY(112.95,$y+$h+$h+20);
				$pdf->MultiCell(92.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");

				$i = 1;
				while($fila_imagenes = $nuevaConexion->obtenerFilas($result)){
					if($i == 1){
						$pdf->Image($fila_imagenes[4], 15, $y + 5, 83, 60);	
						$pdf->SetXY(10,$y+80);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
					}
					if($i == 2){
						$pdf->SetXY(113,$y+80);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
						$pdf->Image($fila_imagenes[4], 117.5, $y + 5, 83, 60);		
					}
					if($i == 3){
						$pdf->Image($fila_imagenes[4], 15, $y + 105, 83, 60);			
						$pdf->SetXY(10,$y+$h+90);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
					}
					if($i == 4){
						$pdf->Image($fila_imagenes[4], 117.5, $y + 105, 83, 60);	
						$pdf->SetXY(113,$y+$h+90);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
					}
					if($i == 5){
						$pdf->Image($fila_imagenes[4], 15, $y + 205, 83, 60);	
						$pdf->SetXY(10,$y+$h+190);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
					}
					if($i == 6){
						$pdf->Image($fila_imagenes[4], 117.5, $y + 205, 83, 60);		
						$pdf->SetXY(113,$y+$h+190);
						$pdf->MultiCell(92.95, 10, strtoupper(utf8_decode(html_entity_decode($fila_imagenes[5]))), "T", "C");
					}
					$i++;			
				}
				$nuevaConexion->liberarConsulta($result);
			}

			$pdf->AddPage("P", "Legal", 0);
			
			//Obtenemos los datos de libro
			$query = "SELECT * FROM libro WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
			$result = $nuevaConexion->ejecutarConsulta($query);
			if($result){		
				$fila_libro = $nuevaConexion->obtenerFilas($result);
				//Imagenes
				$y = 45;
				$h = 180;
				$pdf->SetTextColor(0);		
				$pdf->SetFont("Arial", "", 8);		
				$pdf->SetXY(22.95,$y);
				$pdf->MultiCell(150, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LTRB", "C");		
				$pdf->Image($fila_libro[4], 42.95, $y + 5, 110);	
				$pdf->SetXY(22.95,$y+170);
				$pdf->MultiCell(150, 10, strtoupper(utf8_decode(html_entity_decode($fila_libro[5]))), "T", "C");					
				$nuevaConexion->liberarConsulta($result);
				
			}
						
			if(!file_exists("../archivos/" . $_SESSION["numero_usuario"])){
				mkdir("../archivos/" . $_SESSION["numero_usuario"] . "/");	
			}			

			$pdf->Output("F", "../archivos/" . $_SESSION["numero_usuario"] . "/reporteDiario_N" . $numeroReporte . ".pdf");

			//Movimiento
			$fecha = date("Y-m-d");
			$titulo = "REPORTE DIARIO";
			$descripcion = "Usuario ha generado el reporte diario N&deg;" . $numeroReporte . " el " . $nuevaConexion->enviarFechaHora();
			$ruta = "../archivos/" . $_SESSION["numero_usuario"] . "/reporteDiario_N" . $numeroReporte . ".pdf";
			$array_descripcion = array($_SESSION["numero_usuario"], $titulo, $descripcion, $ruta, $fecha);
			$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			//Fin Movimiento

			//Cerrar la conexión
			$nuevaConexion->cerrarConexion();

			header("Location: ../interfaz/enviar_control.php");
		}
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}

	function girarFecha($fecha){
		$array_fecha = explode("-", $fecha);
		$nueva_fecha = $array_fecha[2] . "-" . $array_fecha[1] . "-" . $array_fecha[0];
		return $nueva_fecha;		
	}

	function calculoAltura($valor){
		if(strlen($valor) >= 170){
			$partes = strlen($valor) / 170;
			$array_partes = explode(",", $partes);
			if($array_partes[1] > 0){ $h_aux = ($array_partes[0] + 1) * 4; }
		}
		else{
			$h_aux = 0;
		}
		return($h_aux);	
	}
?>