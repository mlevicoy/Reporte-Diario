<?php	
	date_default_timezone_set("America/Santiago");
	setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
	header("Content-Type: text/html; charset=UTF-8");

	class Conexion{
		//Atributos
		private $host;
		private $user;
		private $password;
		private $database;
		private $conn;

		public function __construct(){
			//Constructor
			require_once("variables.php");
			$this->host = SERVIDOR_DB;
			$this->user = USUARIO_DB;
			$this->password = CONTRASENA_DB;
			$this->database = NOMBRE_DB;
		}
		public function crearConexion(){
			//Método para crear y retornar la conexión a la DB
			$this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
			if($this->conn->connect_errno){
				die("Error al conectarse a MySQL: (" . $this->conn->connect_errno . ")" . $this->conn->connect_error);
			}
		}		
		public function cerrarConexion(){
			//Método que cierra la conexión a la DB
			$this->conn->close();			
		}
		public function liberarConsulta($result){
			//Método que libera los resultado
			$result->free_result();
		}
		public function ejecutarConsulta($sql){
			//Método que ejecuta una query retornando el resultado
			$resultado = $this->conn->query($sql);
			return $resultado;
		}
		public function obtenerFilasAfectadas(){
			//Método que retorna la cantidad de filas afectadas en el query
			return $this->conn->affected_rows;
		}
		public function obtenerFilas($result){
			//Retorna última fila en formato
			return $result->fetch_row();
		}
		public function generamosConsulta($nombre_db, $array_reporte){
			// Obtenemos los campos de la table
			$nombre_campo = [];
			$consulta = "SHOW COLUMNS FROM " . $nombre_db;
			$resultado = $this->ejecutarConsulta($consulta);			
			if($resultado){
				while($fila = $resultado->fetch_assoc()){
					array_push($nombre_campo, $fila["Field"]);					
				}
			}
			// Generamos la consulta
			$query_inicial = "INSERT INTO " . $nombre_db . " (";
			$query_centro = "";
				// Verificamos que no sea la tabla reporte y eliminamos el campo id_XXX
			//if(strcmp("reporte", $nombre_db) != 0){
			array_splice($nombre_campo, 0, 1);
			//}
			for($i=0;$i<count($nombre_campo);$i++){
				$query_centro = $query_centro . $nombre_campo[$i] . ", ";
			}
				//Eliminamos el último espacio y la última coma
			$consulta = $query_inicial . substr($query_centro, 0, -2) . ") VALUES (";				
				// Agregamos las variables
			for($i=0;$i<count($array_reporte);$i++){
				if(is_string($array_reporte[$i]) == 1){
					$consulta = $consulta . "'" . $array_reporte[$i] . "', ";
				}
				else{
					$consulta = $consulta . $array_reporte[$i] . ", ";
				}				
			}

			$consulta = substr($consulta, 0, -2) . ")";

			$this->liberarConsulta($resultado);
			return($consulta);						
		}

		//Nada que ver, solo para no hacer otra clase
		public function enviarFechaHora(){
			$dia = date("d");
			$mes = date("F");
			$anho = date("Y");
			$hora = date("h:i:s A");
			$mes_espanol = "";

			switch($mes){
				case "January":
					$mes_espanol = "enero";
					break;
				case "February":
					$mes_espanol = "febrero";
					break;
				case "March":
					$mes_espanol = "marzo";
					break;
				case "April":
					$mes_espanol = "abril";
					break;
				case "May":
					$mes_espanol = "mayo";
					break;
				case "June":
					$mes_espanol = "junio";
					break;
				case "July":
					$mes_espanol = "julio";
					break;
				case "August":
					$mes_espanol = "agosto";
					break;
				case "September":
					$mes_espanol = "septiembre";
					break;
				case "October":
					$mes_espanol = "octubre";
					break;
				case "November":
					$mes_espanol = "noviembre";
					break;
				case "December":
					$mes_espanol = "diciembre";
					break;
			}
			$fecha_hora = $dia . " de " . $mes_espanol . " del " . $anho . " a las " . $hora. ".";				
			return($fecha_hora);			
		}		
	}
?>