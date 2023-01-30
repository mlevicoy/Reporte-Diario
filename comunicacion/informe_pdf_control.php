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
				$numeroReporte = $_SESSION["numero_reporte"];

				//Creamos una instancia
				$nuevaConexion = new Conexion();
				//Creamos una nueva conexión
				$nuevaConexion->crearConexion();

				//Obtenemos los datos de reporte
				$query = "SELECT revision_reporte FROM reporte WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
				$result = $nuevaConexion->ejecutarConsulta($query);
				if($result){
					$fila_reporte = $nuevaConexion->obtenerFilas($result);
				}
				$nuevaConexion->cerrarConexion();
				
				//Logo Bogado
				$this->Image("../imagenes/logos/Bogado.jpg", 17.5, 15.5, 35);
				$this->SetFont("Arial", "B", 12);	
				$this->MultiCell(50, 19, " ", 1, "C");
				//Titulo
				$this->SetXY(60,10);
				$this->MultiCell(95.9, 10, "REGISTRO", "T", "C");
				$this->SetXY(60,19);
				$this->MultiCell(95.9, 10, utf8_decode("REPORTE DIARIO DE INSPECCIÓN DE OBRA"), "B", "C");		
				//Cuadro completo
				$this->SetXY(155.9,10);
				$this->MultiCell(0, 19, " ", 1, "C");
				//Logo
				$this->SetXY(155.9, 10);
				$this->Image("../imagenes/logos/esval_dellValle.png", 163.5, 13, 35);
				//Fecha
				$this->SetFont("Arial", "B", 8);
				$this->SetXY(155.9,23);
				$this->MultiCell(15, 3, utf8_decode("Fecha:"), "TR", "L");
				$this->SetFont("Arial", "", 7);
				$this->SetXY(170.9,23);
				$this->MultiCell(0, 3, date("d-m-Y"), "T", "L");
				//Revisión
				$this->SetFont("Arial", "B", 8);
				$this->SetXY(155.9,26);
				$this->MultiCell(15, 3, utf8_decode("Revisión:"), "TR", "L");
				$this->SetFont("Arial", "", 7);
				$this->SetXY(170.9,26);
				$this->MultiCell(0, 3, $fila_reporte[0], "T", "L");		
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
		$y = 35;
		$h = 10;

		$numeroReporte = $_SESSION["numero_reporte"];

		//Creamos una instancia
		$nuevaConexion = new Conexion();
		//Creamos una nueva conexión
		$nuevaConexion->crearConexion();

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
			$pdf->SetFont("Arial", "", 7);
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
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode("N°"))), "LT", "C", true);		
			$pdf->SetXY(20,$y);
			$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode("MONTO"))), "LT", "C", true);
			$pdf->SetXY(40,$y);
			$pdf->MultiCell(54.667, $h,strtoupper(utf8_decode(html_entity_decode("MANDANTE"))), "LT", "C", true);
			$pdf->SetXY(94.667,$y);
			$pdf->MultiCell(54.667, $h,strtoupper(utf8_decode(html_entity_decode("CONTRATISTA"))), "LT", "C", true);
			$pdf->SetXY(149.334,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("INSPECTOR TÉCNICO"))), "LTR", "C", true);

			$y = $y + 5.24;
			$h = 5;
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[4]))), "LT", "C");		
			$pdf->SetXY(20,$y);
			$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[5]))), "LT", "C");
			$pdf->SetXY(40,$y);
			$pdf->MultiCell(54.667, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[6]))), "LT", "C");
			$pdf->SetXY(94.667,$y);
			$pdf->MultiCell(54.667, $h,strtoupper(utf8_decode(html_entity_decode($fila_proyecto[7]))), "LT", "C");
			$pdf->SetXY(149.334,$y);
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
			$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode("N° REPORTE"))), "LT", "C", true);		
			$pdf->SetXY(30,$y);
			$pdf->MultiCell(27, $h,strtoupper(utf8_decode(html_entity_decode("FECHA REPORTE"))), "LT", "C", true);
			$pdf->SetXY(57,$y);
			$pdf->MultiCell(47.5, $h,strtoupper(utf8_decode(html_entity_decode("SECTOR DE TRABAJO/CALLE"))), "LT", "C", true);
			$pdf->SetXY(104.5,$y);
			$pdf->MultiCell(47.5, $h,strtoupper(utf8_decode(html_entity_decode("LUGAR DE TRABAJO"))), "LT", "C", true);
			$pdf->SetXY(152,$y);
			$pdf->MultiCell(26, $h,strtoupper(utf8_decode(html_entity_decode("TURNO MAÑANA"))), "LT", "C", true);
			$pdf->SetXY(178,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TURNO TARDE"))), "LTR", "C", true);
//7.5
			$y = $y + 5.24;
			$h = 5;
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(20, $h,strtoupper(utf8_decode(html_entity_decode($fila_reporte[1]))), "LT", "C");		
			$pdf->SetXY(30,$y);
			$pdf->MultiCell(27, $h,strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_reporte[3])))), "LT", "C");
			$pdf->SetXY(57,$y);
			$pdf->MultiCell(47.5, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[3]))), "LT", "C");
			$pdf->SetXY(104.5,$y);
			$pdf->MultiCell(47.5, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[4]))), "LT", "C");
			$pdf->SetXY(152,$y);
			$pdf->MultiCell(26, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[5]))), "LT", "C");
			$pdf->SetXY(178,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_trabajo[6]))), "LTR", "C");

			$nuevaConexion->liberarConsulta($result);
			$nuevaConexion->liberarConsulta($result2);
		}

		//Obtenemos los datos de plazo trabajo y horas
		$query = "SELECT * FROM plazo_trabajo WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$query2 = "SELECT * FROM horas WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$result = $nuevaConexion->ejecutarConsulta($query);
		$result2 = $nuevaConexion->ejecutarConsulta($query2);
		if($result && $result2){
			$fila_plazo_trabajo = $nuevaConexion->obtenerFilas($result);	
			$fila_horas = $nuevaConexion->obtenerFilas($result2);
			//Fecha inicio, Dias totales, Dias restantes, Avance, Trabajadores en faena, Horas
			$y = $y + 5.24;
			$h = 5;
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(19.6, $h, strtoupper(utf8_decode(html_entity_decode("FECHA INICIO"))), "LT", "C", true);		
			$pdf->SetXY(29.6, $y);
			$pdf->MultiCell(19.6, $h, strtoupper(utf8_decode(html_entity_decode("FECHA TERMINO"))), "LT", "C", true);
			$pdf->SetXY(49.2, $y);
			$pdf->MultiCell(19.6, $h,strtoupper(utf8_decode(html_entity_decode("DIAS DE PLAZO"))), "LT", "C", true);
			$pdf->SetXY(68.8,$y);
			$pdf->MultiCell(19.6, $h,strtoupper(utf8_decode(html_entity_decode("ENTREGA TERRENO"))), "LT", "C", true);
			$pdf->SetXY(88.4,$y);
			$pdf->MultiCell(19.6, $h,strtoupper(utf8_decode(html_entity_decode("DIAS RESTANTES"))), "LT", "C", true);
			$pdf->SetXY(108,$y);
			$pdf->MultiCell(0, $h+5,strtoupper(utf8_decode(html_entity_decode("INSPECCION TECNICA"))), "LTR", "C", true);	

			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY(10,$y+10);
			$pdf->MultiCell(19.6, $h+10, strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_plazo_trabajo[3])))), "LT", "C", false);		
			$pdf->SetXY(29.6, $y+10);
			$pdf->MultiCell(19.6, $h+10, strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_plazo_trabajo[4])))), "LT", "C", false);
			$pdf->SetXY(49.2, $y+10);
			$pdf->MultiCell(19.6, $h+10,strtoupper(utf8_decode(html_entity_decode($fila_plazo_trabajo[5]))), "LT", "C", false);
			$pdf->SetXY(68.8,$y+10);
			$pdf->MultiCell(19.6, $h+10,strtoupper(utf8_decode(html_entity_decode(girarFecha($fila_plazo_trabajo[6])))), "LT", "C", false);
			$pdf->SetXY(88.4,$y+10);
			$pdf->MultiCell(19.6, $h+10,strtoupper(utf8_decode(html_entity_decode($fila_plazo_trabajo[7]))), "LT", "C", false);
			
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(108,$y+10);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("HH"))), "LT", "C", true);
			$pdf->SetXY(124.4,$y+10);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("INICIO"))), "LT", "C", true);
			$pdf->SetXY(140.8,$y+10);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("TERMINO"))), "LT", "C", true);
			$pdf->SetXY(157.2,$y+10);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("INFORME"))), "LT", "C", true);
			$pdf->SetXY(173.6,$y+10);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("TRAYECTO"))), "LT", "C", true);
			$pdf->SetXY(190,$y+10);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TOTAL"))), "LTR", "C", true);

			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetXY(108,$y+15);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("NORMAL"))), "LT", "C", true);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetXY(124.4,$y+15);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[3]))), "LT", "C", false);
			$pdf->SetXY(140.8,$y+15);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[5]))), "LT", "C", false);
			$pdf->SetXY(157.2,$y+15);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[7]))), "LT", "C", false);
			$pdf->SetXY(173.6,$y+15);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[11]))), "LT", "C", false);
			$pdf->SetXY(190,$y+15);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[9]))), "LTR", "C", false);
			
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(108,$y+20);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode("EXTRAS"))), "LT", "C", true);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetXY(124.4,$y+20);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[4]))), "LT", "C", false);
			$pdf->SetXY(140.8,$y+20);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[6]))), "LT", "C", false);
			$pdf->SetXY(157.2,$y+20);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[8]))), "LT", "C", false);
			$pdf->SetXY(173.6,$y+20);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[12]))), "LT", "C", false);
			$pdf->SetXY(190,$y+20);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($fila_horas[10]))), "LTR", "C", false);

			$nuevaConexion->liberarConsulta($result);
			$nuevaConexion->liberarConsulta($result2);	
		}

		//Obtenemos los datos de personalecc, equipoeecc y trabajadores
		$query = "SELECT * FROM personaleecc WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$query2 = "SELECT * FROM equipoeecc WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$query3 = "SELECT * FROM trabajadores WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];

		$result = $nuevaConexion->ejecutarConsulta($query);
		$result2 = $nuevaConexion->ejecutarConsulta($query2);
		$result3 = $nuevaConexion->ejecutarConsulta($query3);

		if($result && $result2 && $result3){
			$fila_personaleecc = $nuevaConexion->obtenerFilas($result);
			$fila_equipoeecc = $nuevaConexion->obtenerFilas($result2);
			$fila_trabajadores = $nuevaConexion->obtenerFilas($result3);	

			//equipos ee.cc y personal ee.cc
			$y = $y + 25;
			$h = 5;
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(65.33, $h, strtoupper(utf8_decode(html_entity_decode("PERSONAL CONTRATISTA"))), "LT", "C", true);	
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetXY(10,$y+5);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("DESCRIPCION"))), "LT", "C", true);		
			$pdf->SetXY(55.328,$y+5);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode("SI"))), "LT", "C", true);	
			$pdf->SetXY(65.328,$y+5);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode("NO"))), "LT", "C", true);
			$pdf->SetXY(10,$y+10);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("ADMINISTRADOR"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(55.328,$y+10);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[3]))), "LTR", "C", false);	
			$pdf->SetXY(65.328,$y+10);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[4]))), "LTR", "C", false);
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y+15);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("JEFE DE TERRENO"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(55.328,$y+15);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[5]))), "LTR", "C", false);	
			$pdf->SetXY(65.328,$y+15);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[6]))), "LTR", "C", false);
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y+20);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("ENCARGADO DE CALIDAD"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(55.328,$y+20);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[7]))), "LTR", "C", false);	
			$pdf->SetXY(65.328,$y+20);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[8]))), "LTR", "C", false);
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y+25);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("SUPERVISOR OOCC"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(55.328,$y+25);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[9]))), "LTR", "C", false);	
			$pdf->SetXY(65.328,$y+25);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[10]))), "LTR", "C", false);
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y+30);
			$pdf->MultiCell(45.328, $h, strtoupper(utf8_decode(html_entity_decode("PREVENCIONISTA"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(55.328,$y+30);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[11]))), "LTR", "C", false);	
			$pdf->SetXY(65.328,$y+30);
			$pdf->MultiCell(10, $h, strtoupper(utf8_decode(html_entity_decode($fila_personaleecc[12]))), "LTR", "C", false);
			
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(75.33,$y);
			$pdf->MultiCell(65.33, $h+5, strtoupper(utf8_decode(html_entity_decode("EQUIPOS CONTRATISTA"))), "LT", "C", true);
			$pdf->SetFont("Arial", "", 6);
			$pdf->SetTextColor(0);
			$pdf->SetXY(75.33,$y+10);
			$h_aux = 0;
			$pdf->MultiCell(65.33, $h+$h_aux, strtoupper(utf8_decode(html_entity_decode($fila_equipoeecc[3]))), "T", "L", false);
			
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(140.66,$y);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode("PERMISOS BNUP"))), "LRT", "C", true);	
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetXY(140.66,$y+5);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode("FECHA DE INICIO"))), "LTR", "C", true);
			$pdf->SetXY(173.66,$y+5);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode("FECHA DE TERMINO"))), "TR", "C", true);

			$pdf->SetFont("Arial", "", 6);
			$pdf->SetTextColor(0);
			$pdf->SetXY(140.66,$y+10);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode($fila_trabajadores[3]))), "LRT", "C", false);
			$pdf->SetXY(173.66,$y+10);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode($fila_trabajadores[4]))), "TR", "C", false);

			$pdf->SetXY(140.66,$y+15);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LRT", "L", false);
			$pdf->SetXY(173.66,$y+15);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode(""))), "RT", "C", false);
			
			$pdf->SetXY(140.66,$y+20);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LRT", "L", false);
			$pdf->SetXY(173.66,$y+20);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode(""))), "RT", "C", false);	
			
			$pdf->SetXY(140.66,$y+25);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LRT", "L", false);
			$pdf->SetXY(173.66,$y+25);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode(""))), "RT", "C", false);	
			
			$pdf->SetXY(140.66,$y+30);
			$pdf->MultiCell(33, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LRT", "L", false);
			$pdf->SetXY(173.66,$y+30);
			$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode(""))), "RT", "C", false);	

			$nuevaConexion->liberarConsulta($result);
			$nuevaConexion->liberarConsulta($result2);
			$nuevaConexion->liberarConsulta($result3);
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
			$y = $y + 35;
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
			$pdf->MultiCell(67.8, $h,strtoupper(utf8_decode(html_entity_decode("CUMPLIMIENTOS"))), "LT", "C", true);		
			$pdf->SetXY(175.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode("C"))), "LT", "C", true);
			$pdf->SetXY(185.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode("NC"))), "LT", "C", true);
			$pdf->SetXY(195.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode("NA"))), "LTR", "C", true);
			
			$y = $y + 5;
			$h = 5;
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(48, $h,strtoupper(utf8_decode(html_entity_decode("TIENE PTS"))), "LT", "L", true);		
			$pdf->SetXY(108.1,$y);
			$pdf->MultiCell(67.8, $h,strtoupper(utf8_decode(html_entity_decode("CUMPLE PROCEDIMIENTO U OTRO DOCUMENTO"))), "LT", "L", true);		
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(58,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[3]))), "LT", "C");
			$pdf->SetXY(74.4,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[4]))), "LT", "C");
			$pdf->SetXY(90.8,$y);
			$pdf->MultiCell(17.3, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[5]))), "LT", "C");
			$pdf->SetXY(175.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[3]))), "LT", "C");
			$pdf->SetXY(185.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[4]))), "LT", "C");
			$pdf->SetXY(195.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[5]))), "LTR", "C");
			
			$y = $y + 5;
			$h = 5;
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(48, $h,strtoupper(utf8_decode(html_entity_decode("PREVENCIONISTA EN TERRENO"))), "LT", "L", true);		
			$pdf->SetXY(108.1,$y);
			$pdf->MultiCell(67.8, $h,strtoupper(utf8_decode(html_entity_decode("PERSONAL CONOCE LOS PROCEDIMIENTOS"))), "LT", "L", true);			
			$pdf->SetFont("Arial", "", 7);
			$pdf->SetTextColor(0);
			$pdf->SetXY(58,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[6]))), "LT", "C");
			$pdf->SetXY(74.4,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[7]))), "LT", "C");
			$pdf->SetXY(90.8,$y);
			$pdf->MultiCell(17.3, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[8]))), "LT", "C");		
			$pdf->SetXY(175.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[6]))), "LT", "C");
			$pdf->SetXY(185.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[7]))), "LT", "C");
			$pdf->SetXY(195.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode($fila_cumplimiento[8]))), "LTR", "C");

			$y = $y + 5;
			$h = 5;
			$pdf->SetFont("Arial", "B", 7);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(48, $h,strtoupper(utf8_decode(html_entity_decode("SUPERVISOR EN EL ÁREA"))), "LT", "L", true);	
			$pdf->SetFont("Arial", "", 7);	
			$pdf->SetTextColor(0);
			$pdf->SetXY(58,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[9]))), "LT", "C");
			$pdf->SetXY(74.4,$y);
			$pdf->MultiCell(16.4, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[10]))), "LT", "C");
			$pdf->SetXY(90.8,$y);
			$pdf->MultiCell(17.3, $h,strtoupper(utf8_decode(html_entity_decode($fila_condicion[11]))), "LT", "C");
			$pdf->SetXY(108.1,$y);
			$pdf->MultiCell(67.8, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");		
			$pdf->SetXY(175.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
			$pdf->SetXY(185.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LT", "C");
			$pdf->SetXY(195.9,$y);
			$pdf->MultiCell(10, $h,strtoupper(utf8_decode(html_entity_decode(""))), "LTR", "C");

			$nuevaConexion->liberarConsulta($result);
			$nuevaConexion->liberarConsulta($result2);
		}

		//Obtenemos los datos de texto y localizacion
		$query = "SELECT * FROM texto WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$query2 = "SELECT * FROM localizacion WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$result = $nuevaConexion->ejecutarConsulta($query);
		$result2 = $nuevaConexion->ejecutarConsulta($query2);
		if($result && $result2){
			$fila_condicion = $nuevaConexion->obtenerFilas($result);
			$fila_ubicacion = $nuevaConexion->obtenerFilas($result2);
			//Texto
			$y = $y + 5;
			$h = 5;
			$h_texto = 19;
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);

			$texto = dividir_texto($fila_condicion[3], $y);			
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("PRINCIPALES ACTIVIDADES DEL DÍA"))), "LTR", "C", true);	
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($texto[0]))), "LTR", "L");			
			$pdf->SetXY(10,$texto[1]);
			$pdf->MultiCell(0, $texto[2],"", "LR", "L");

			$y = $y + 28.3;		
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);		
			$texto = dividir_texto($fila_condicion[4], $y);
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("HALLAZGOS"))), "LTR", "C", true);		
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($texto[0]))), "LTR", "L");
			$pdf->SetXY(10,$texto[1]);
			$pdf->MultiCell(0, $texto[2],"", "LR", "L");

			$y = $y + 28.3;		
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);	
			$texto = dividir_texto($fila_condicion[5], $y);	
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("ACTIVIDADES CONTROL DE CALIDAD"))), "LTR", "C", true);		
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);	
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($texto[0]))), "LTR", "L");
			$pdf->SetXY(10,$texto[1]);
			$pdf->MultiCell(0, $texto[2],"", "LR", "L");			

			$y = $y + 28.3;		
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);
			$texto = dividir_texto($fila_condicion[6], $y);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("TÓPICO REUNIÓN (SI EXISTE)"))), "LTR", "C", true);		
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($texto[0]))), "LTR", "L");
			$pdf->SetXY(10,$texto[1]);
			$pdf->MultiCell(0, $texto[2],"", "LR", "L");			

			$y = $y + 28.3;		
			$pdf->SetFont("Arial", "B", 8);
			$pdf->SetFillColor(0, 161, 76);
			$pdf->SetTextColor(255,255,255);	
			$texto = dividir_texto($fila_condicion[7], $y);	
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("INCIDENTES / CONDICIÓN DE RIESGOS / NO CONFORMIDAD"))), "LTR", "C", true);		
			$y = $y + 5;
			$pdf->SetTextColor(0);		
			$pdf->SetFont("Arial", "", 7);		
			$pdf->SetXY(10,$y);
			$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode($texto[0]))), "LTR", "L");
			$pdf->SetXY(10,$texto[1]);
			$pdf->MultiCell(0, $texto[2],"", "LRB", "L");

			$nuevaConexion->liberarConsulta($result);
		}

		//Firmas
		$y = $y + 28.3;		
		$pdf->SetFont("Arial", "B", 8);
		$pdf->SetFillColor(0, 161, 76);
		$pdf->SetTextColor(255,255,255);		
		$pdf->SetXY(10,$y);
		$pdf->MultiCell(107.95, $h,strtoupper(utf8_decode(html_entity_decode("ELABORADO POR:"))), "L", "C", true);	
		$pdf->SetXY(117.95,$y);
		$pdf->MultiCell(0, $h,strtoupper(utf8_decode(html_entity_decode("UBICADO EN:"))), "LR", "C", true);		
		$y = $y + 5;
		$pdf->SetTextColor(0);		
		$pdf->SetFont("Arial", "", 7);		
		$pdf->SetXY(10,$y);
		$pdf->MultiCell(107.95, $h+1, strtoupper(utf8_decode(html_entity_decode("NOMBRE:"))), "LTR", "L");
		$pdf->SetXY(117.95,$y);
		$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode($fila_ubicacion[3]))), "RT", "L");
		$pdf->SetXY(117.95,$y+5);
		$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode("REGION " . $fila_ubicacion[4]))), "R", "L");
		$pdf->SetXY(117.95,$y+10);
		$pdf->MultiCell(0, $h, strtoupper(utf8_decode(html_entity_decode($fila_ubicacion[5]))), "RB", "L");
		$y = $y + 6;
		$pdf->SetXY(10,$y);
		$pdf->MultiCell(107.95, $h, strtoupper(utf8_decode(html_entity_decode("FIRMA:"))), "LR", "L");
		$y = $y + 4;
		$pdf->SetXY(10,$y);
		$pdf->MultiCell(107.95, $h, strtoupper(utf8_decode(html_entity_decode(""))), "LRB", "L");

		$pdf->AddPage("P", "Legal", 0);

		//Obtenemos los datos de imagenes
		$query = "SELECT * FROM imagenes WHERE id_reporte = $numeroReporte AND id_usuario = " . $_SESSION["numero_usuario"];
		$result = $nuevaConexion->ejecutarConsulta($query);
		$filas_afectadas = $nuevaConexion->obtenerFilasAfectadas();
		
		if($result && $filas_afectadas != 0){		
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
		$filas_afectadas = $nuevaConexion->obtenerFilasAfectadas();
		
		if($result && $filas_afectadas != 0){		
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
		//$pdf->Output();

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
	if(strlen($valor) > 340){
		$partes = strlen($valor) / 170;
		$array_partes = explode(",", $partes);
		if($array_partes[1] > 0){ $h_aux = ($array_partes[0] + 1) * 4; }
	}
	else if(strlen($valor) >= 170 && strlen($valor) <= 340){
		$h_aux = 9.6;
	}
	else{
		$h_aux = -9.2;
	}
	return($h_aux);	
}

function dividir_texto($texto, $y){		
	$texto_devolver = [];			
	if(empty($texto)){
		$texto_devolver[0] = "";
		$texto_devolver[1] = $y + 0;
		$texto_devolver[2] = 30;
	}
	else{
		$array_texto = explode(" ", $texto);
		$aux = "";
		$sum = 0;
		$j=0;
		for($i=0;$i<count($array_texto);$i++){
			if($sum < 125){
				$sum = $sum + (strlen($array_texto[$i]) + 1);
				$aux = $aux . $array_texto[$i] . " ";					
			}
			else if($sum == 130){
				$aux = $aux . "\n";
				$j++;
				$sum = 0;
				
				$sum += (strlen($array_texto[$i]) + 1);
				$aux = $aux . $array_texto[$i] . " ";
			}
			else{
				$aux2 = explode(" ", $aux);
				$ultimo = array_pop($aux2);
				$aux = implode(" ", $aux2);
				$aux = $aux . "\n";
				$j++;
				$sum = 0;
				
				$sum += (strlen($ultimo) + 1);
				$aux = $aux . $ultimo . " ";

				$sum += (strlen($array_texto[$i]) + 1);
				$aux = $aux . $array_texto[$i] . " ";
			}
		}			

		if(($j+1) == 5){ 
			$relleno_y = $y + 30;
			$relleno_h = 5;
		}
		else if(($j+1) == 4){ 
			$relleno_y = $y + 25;
			$relleno_h = 10;
		}
		else if(($j+1) == 3){ 
			$relleno_y = $y + 20;
			$relleno_h = 15;
		}
		else if(($j+1) == 2){ 
			$relleno_y = $y + 15;
			$relleno_h = 20;
		}
		else if(($j+1) == 1){ 
			$relleno_y = $y + 10;
			$relleno_h = 25;
		}

		$texto_devolver[0] = $aux;
		$texto_devolver[1] = $relleno_y; 
		$texto_devolver[2] = $relleno_h; 							
	}

	return $texto_devolver;
}

/*
function calculo_texto($texto){

	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//$texto = "  "
			//$texto = (string)$texto;


			  
			
			//$texto_array[0] = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque";
			//$texto_array[1] = "penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,";
			//$texto_array[2] = "sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus";
			//$texto_array[3] = "ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus";	
			//$texto_array[4] = "elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu,"; 

			
	$texto_array = array();

	//$texto_array[0]; //. "\n" . $texto_array[1];// . "\n" . $texto_array[2];// . "\n" . $texto_array[3];//. "\n" . $texto_array[4];

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/*if(count($texto_array) == 5){ 
				$relleno_y = $y + 25;
				$relleno_h = 5;
			}
			else if(count($texto_array) == 4){ 
				$relleno_y = $y + 20;
				$relleno_h = 10;
			}
			else if(count($texto_array) == 3){ 
				$relleno_y = $y + 15;
				$relleno_h = 15;
			}
			else if(count($texto_array) == 2){ 
				$relleno_y = $y + 10;
				$relleno_h = 20;
			}
			else if(count($texto_array) == 1){ 
				$relleno_y = $y + 5;
				$relleno_h = 25;
			}
			else if(count($texto_array) == 0){ 
				$relleno_y = $y + 0;
				$relleno_h = 30;
			}*/
//}
?>
