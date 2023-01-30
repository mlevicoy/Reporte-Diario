<?php
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");
	
	require("../librerias/PHPMailer660/src/PHPMailer.php");
	require("../librerias/PHPMailer660/src/SMTP.php");
	require("../librerias/PHPMailer660/src/Exception.php");
	require("../conexion/conexion.php");

	$mail = new PHPMailer\PHPMailer\PHPMailer();

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

	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && ($_SESSION["id_tipo"] == 2 || $_SESSION["id_tipo"] == 3)){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: ../comunicacion/cerrar_sesion.php");
			die();
		}
		else{
			$dirigido = "";

			$nuevaConexion = new Conexion();
			$nuevaConexion->crearConexion();
			//Consulta
			$query = "SELECT persona.nombre_persona, persona.apellido_persona, persona.correo_persona, persona.cargo_persona, usuario_proyecto.nombre_proyecto, usuario_proyecto.numero_proyecto FROM persona INNER JOIN usuario_proyecto ON persona.id_usuario = usuario_proyecto.id_usuario WHERE persona.id_usuario = " . $_SESSION["id_usuario"];
			$resultado	= $nuevaConexion->ejecutarConsulta($query);
			$fila = $nuevaConexion->obtenerFilas($resultado);
			
			$destinatarios = $_POST["destinatarios"];
			//Validar SMTP
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = "smtp.office365.com";
			$mail->Username = "######";
			$mail->Password = "######";
			$mail->SMTPSecure = "tls";
			$mail->Port = 587;

			//Desde
			$mail->From = "manuel.levicoy@bogado.cl";
			$mail->FromName = "Manuel Levicoy";

			//Hacia
			for($i=0;$i<count($destinatarios);$i++){
				$mail->AddAddress($destinatarios[$i]);
				$dirigido = $dirigido . ", " . $destinatarios[$i];
			}
			//$mail->AddCc("manuel.levicoy@bogado.cl");
			//$mail->AddBcc("manuel.levicoy@bogado.cl");
			
			//Cuerpo
			$mail->IsHTML(true);
			$mail->Subject = "Reporte Diario N" . $_SESSION["numero_reporte"];

			$body = "Junto con saludar cordialmente, <br/><br/>";
			$body .= "Informo que se ha enviado el &quot;<strong>Reporte Diario de Inspecci&oacute;n de Obra N°".$_SESSION["numero_reporte"]."</strong>&quot; correspondiente al proyecto &quot;<strong>" . $fila[4] . "</strong>&quot;, n&uacute;mero &quot;<strong>" . $fila[5] . "</strong>&quot;.<br/><br/>Sin otro particular se despido cordialmente, <br/><br/>" . $fila[0] . " " . $fila[1] . "<br/>" . $fila[3] . "<br/>" . $fila[2] . "<br/><br/>";
			$mail->Body = utf8_decode($body);
			$mail->AltBody = "Junto con saludar, informo que se ha enviado el Reporte Diario de Inspección de Obra N°".$_SESSION["numero_reporte"] . " correspondiente al proyecto " . $fila[4] . " número " . $fila[5] . ". Sin otro particular se despido cordialmente, " . $fila[0] . " " . $fila[1] . ", " . $fila[3] . ", " . $fila[2] . ".";

			if($_SESSION["id_tipo"] == 2){			
				$mail->AddAttachment("../archivos/" . $_SESSION["id_usuario"] . "/reporteDiario_N".$_SESSION["numero_reporte"].".pdf");
			}
			else{
				$mail->AddAttachment("../archivos/" . $_SESSION["numero_usuario"] . "/reporteDiario_N".$_SESSION["numero_reporte"].".pdf");	
			}
			
			$exito = $mail->Send();

			if($exito){
				//Movimiento
				$fecha = date("Y-m-d");
				$titulo = "ENVIO DE REPORTE DIARIO";
				$descripcion = "Usuario ha enviado el reporte diario N&deg;" . $numeroReporte . "el " . $nuevaConexion->enviarFechaHora() . ", a los usuarios " . $dirigido;				
				$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
				$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);
				$resultado	= $nuevaConexion->ejecutarConsulta($query);
				//Fin Movimiento

				//Cerrar la conexión
				$nuevaConexion->cerrarConexion();

				if($_SESSION["id_tipo"] == 2){
					header("Location: ../interfaz/menu.php?val=100");	
					die();
				}
				else{
					header("Location: ../interfaz/jefe.php?val=100");	
					die();
				}				
			}
			else{
				header("Location: ../interfaz/jefe.php?val=101");	
				die();
			}
		}
	}
?>	
