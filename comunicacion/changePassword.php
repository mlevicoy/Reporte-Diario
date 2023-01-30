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

	if(isset($_SESSION["identificador"]) && $_SESSION["identificador"] == session_id() && $_SESSION["id_tipo"] == 1){
		if((time() - $_SESSION["tiempo_inicio"]) > 7200){
			header("Location: cerrar_sesion.php");
			die();
		}
		else{
			//Creamos una instancia y una nueva conexión
			$nuevaConexion = new Conexion();			
			$nuevaConexion->crearConexion();

			$identificador_usuario = $_POST["identificador_usuario"];

			$query = "SELECT nombre_usuario FROM usuario WHERE id_usuario = ". $identificador_usuario;
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			$filas = $nuevaConexion->obtenerFilas($resultado);
			
			//Datos
			if(isset($_POST["contrasena_automatica"])){
				// Generamos la contraseña aleatoria
				$arreglo = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ".", ",", "/", "(", ")", "!");
				$password_array = array_rand($arreglo, 8);
				$password_array_key = array();
				$contrasena = "";
				for($i=0;$i<count($password_array);$i++){
					$password_array_key[] = $arreglo[$password_array[$i]];
				} 
				$contrasena = implode("", $password_array_key);

				//Encriptamos la contraseña
				$contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT);
			}
			else{				
				$nueva_contrasena = htmlentities(strval(trim($_POST["nueva_contrasena"])), ENT_QUOTES);
				$validar_nueva_contrasena = htmlentities(strval(trim($_POST["validar_nueva_contrasena"])), ENT_QUOTES); 	

				// Validamos la contraseña nueva
				if(strcmp($nueva_contrasena, $validar_nueva_contrasena) == 0){
					$contrasena = $nueva_contrasena;
					//Encriptamos la contraseña
					$contrasena_encriptada = password_hash($nueva_contrasena, PASSWORD_BCRYPT);					
				}
				else{
					//Redirigimos
					header("Location: ../interfaz/passwordChange.php?val=102");
					die();																
				}
			}
			//Actualizamos					
			$query = "UPDATE usuario SET contrasena_usuario = '$contrasena_encriptada' WHERE id_usuario = " . $identificador_usuario;
			$resultado	= $nuevaConexion->ejecutarConsulta($query);

			//Buscamos el nombre, apellido y correo del usuario modificado
			$query = "SELECT nombre_persona, apellido_persona, correo_persona FROM persona WHERE id_usuario = " . $identificador_usuario;
			$resultado = $nuevaConexion->ejecutarConsulta($query);
			$fila = $nuevaConexion->obtenerFilas($resultado);

			require("../librerias/PHPMailer660/src/PHPMailer.php");
			require("../librerias/PHPMailer660/src/SMTP.php");
			require("../librerias/PHPMailer660/src/Exception.php");
			
			$mail = new PHPMailer\PHPMailer\PHPMailer();

			$destinatarios = $_SESSION["usuario"];
			//Validar SMTP
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = "smtp.office365.com";
			$mail->Username = "manuel.levicoy@bogado.cl";
			$mail->Password = "B0g1d0Ing2n32r0s.2022.#*";
			$mail->SMTPSecure = "tls";
			$mail->Port = 587;
			//Desde
			$mail->From = "manuel.levicoy@bogado.cl";
			$mail->FromName = "Manuel Levicoy";
			$mail->AddAddress($destinatarios);	
			//Cuerpo
			$mail->IsHTML(true);
			$mail->Subject = "Cambio de contraseña - Sistema Reporte de Inspección de Obra";
			$body = "Junto con saludar cordialmente, <br/><br/>";
			$body .= "Informo que se ha cambiado la contraseña de la siguiente cuenta: <br><br><strong>Usuario</strong>: " . $filas[0] . "<br><strong>Contrase&ntilde;a</strong>: " . $contrasena . "<br><br><br>";
			$mail->Body = utf8_decode($body);
			$mail->AltBody = "Informo que se ha creado la siguiente cuenta: Usuario: " . $filas[0] . " - Contrasena: " . $contrasena;
			$exito = $mail->Send();

			if($exito){
				//Movimiento
				$fecha = date("Y-m-d");
				$titulo = "CAMBIO DE CONTRASE&Ntilde;A";
				$descripcion = "Usuario ha cambiado la contrase&ntilde;a de " . $fila[0] . " " . $fila[1] . "(" . $fila[2] . ") el " . $nuevaConexion->enviarFechaHora();
				$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
				$query = $nuevaConexion->generamosConsulta("movimientos", $array_descripcion);	
				$resultado	= $nuevaConexion->ejecutarConsulta($query);
				$nuevaConexion->cerrarConexion();					
				//Fin Movimiento

				header("Location: ../interfaz/administracion.php?val=101");
				die();
			}
			else{
				echo "Hubo un inconveniente. Contacta a un administrador.";
				exit;
			}		
		}		
	}
	else{
		header("Location: cerrar_sesion.php");
		die();
	}
?>