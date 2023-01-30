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
			$nueva_conexion = new Conexion();
			$nueva_conexion->crearConexion();

			// Información
			$nombre = htmlentities(strval(trim($_POST["nombre_destinatario"])), ENT_QUOTES);			
			$apellido = htmlentities(strval(trim($_POST["apellido_destinatario"])), ENT_QUOTES);
			$correo = htmlentities(strval(trim($_POST["correo_destinatario"])), ENT_QUOTES);
			$cargo = htmlentities(strval(trim($_POST["cargo_destinatario"])), ENT_QUOTES);
			$tipo_cuenta = $_POST["tipo_cuenta"];
			$nombre_proyecto = htmlentities(strval(trim($_POST["nombre_proyecto"])), ENT_QUOTES);
			$numero_proyecto = htmlentities(strval(trim($_POST["numero_proyecto"])), ENT_QUOTES);
			
			//Validamos si existe el usuario
			$query = "SELECT * FROM usuario WHERE nombre_usuario = '" . $correo . "'";
			$resultado = $nueva_conexion->ejecutarConsulta($query);
			$fila = $nueva_conexion->obtenerFilas($resultado);
			if(!empty($fila)){
				header("Location: ../interfaz/crear_usuario.php?val=101");
				die();
			}
			else{				
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
				
				//Guardamos usuario y obtenemos su id
				$datos_usuario = [$tipo_cuenta, $correo, $contrasena_encriptada];
				$query_usuario = $nueva_conexion->generamosConsulta("usuario", $datos_usuario);
				$resultado_usuario	= $nueva_conexion->ejecutarConsulta($query_usuario);
				if($resultado_usuario){
					//Obtenemos el id_usuario
					$query_id_usuario = "SELECT id_usuario FROM usuario WHERE nombre_usuario = '$correo'";
					$resultado_id_usuario = $nueva_conexion->ejecutarConsulta($query_id_usuario);
					$fila_id_usuario = $nueva_conexion->obtenerFilas($resultado_id_usuario);
					
					//Preparamos el resto de las tablas y generamos las consultas
					$datos_usuario_proyecto = [$fila_id_usuario[0], $nombre_proyecto, $numero_proyecto];
					$datos_persona = [$fila_id_usuario[0], $nombre, $apellido, $correo, $cargo];
					$query_datos_usuario_proyecto = $nueva_conexion->generamosConsulta("usuario_proyecto", $datos_usuario_proyecto);
					$query_datos_persona = $nueva_conexion->generamosConsulta("persona", $datos_persona);
					$resultado_datos_usuario_proyecto = $nueva_conexion->ejecutarConsulta($query_datos_usuario_proyecto);
					$resultado_datos_persona = $nueva_conexion->ejecutarConsulta($query_datos_persona);
					if($resultado_datos_usuario_proyecto && $resultado_datos_persona){					

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
						$mail->Subject = "Nuevo Usuario - Sistema Reporte de Inspección de Obra";

						$body = "Junto con saludar cordialmente, <br/><br/>";
						$body .= "Informo que se ha creado la siguiente cuenta: <br><br><strong>Usuario</strong>: " . $correo . "<br><strong>Contrase&ntilde;a</strong>: " . $contrasena . "<br><br><br>";
						$mail->Body = utf8_decode($body);
						$mail->AltBody = "Informo que se ha creado la siguiente cuenta: Usuario: " . $correo . " - Contrasena: " . $contrasena;
						$exito = $mail->Send();

						if($exito){
							//Movimiento
							$fecha = date("Y-m-d");
							$titulo = "AGREGAR USUARIO";
							$descripcion = "Usuario ha agregado destinatario " . $nombre . " " . $apellido . "(" . $correo ."), el " . $nueva_conexion->enviarFechaHora();
							$array_descripcion = array($_SESSION["id_usuario"], $titulo, $descripcion, "", $fecha);
							$query = $nueva_conexion->generamosConsulta("movimientos", $array_descripcion);
							$resultado	= $nueva_conexion->ejecutarConsulta($query);
							$nueva_conexion->cerrarConexion();
							//Fin Movimiento	

							header("Location: ../interfaz/crear_usuario.php?val=100");
							die();
						}
						else{
							echo "Hubo un inconveniente. Contacta a un administrador.";
							exit;
						}					
					}	
					else{
						echo "Hubo un inconveniente. Contacta a un administrador.";
						exit;
					}
				}
			}
		}
	}
?>