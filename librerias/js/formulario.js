function soloNumeros(evt){
	var code = (evt.which) ? evt.which : evt.keyCode;

	if(code==8){
		return true;
	}
	else if(code >= 48 && code <= 57){
		return true;
	}
	else{
		return false;
	}
}

function soloNumerosPuntos(evt){
	var code = (evt.which) ? evt.which : evt.keyCode;

	if(code==8){
		return true;
	}
	else if((code >= 48 && code <= 57) || code == 46){
		return true;
	}
	else{
		return false;
	}
}

function formatearMonto(){
	var monto = document.formulario.monto.value;
	var puntos = 0;
	var contador = 0;
	var finalArray = [];	
	if (monto.length <= 6){
		puntos = 1;
	} 
	else if(monto.length > 6 && monto.length <= 9){
		puntos = 2;
	} 
	else{
		puntos = 3;
	}
	var arrayNumber = String(monto).split("");
	for(var i=monto.length-1;i>=0 ; i--){
		if(contador != 0 && contador%3 == 0 && puntos != 0){    				
			finalArray.push(".");
			finalArray.push(arrayNumber[i]);
			puntos--;
		}
		else{
			finalArray.push(arrayNumber[i]);    					
		}    			
		contador++;
	}
	finalArray.push("$");
	document.formulario.monto.value = ((finalArray.toString()).replace(/,/g,"")).split("").reverse().join("");
}

function diasRestantes(){    
	// aaaa-mm-dd		
	// Fecha_termino - Fecha_reporte
	var fechaReporte = document.formulario.fecha_reporte.value;
	var fechaTermino = document.formulario.fecha_termino.value;
	//var fechaInicio = document.formulario.fecha_inicio.value;
	//var diasTotales = document.formulario.dias_totales.value;
	
	if(fechaTermino.length != 0 && fechaReporte.length != 0){
		var auxTermino = String(fechaTermino).split("-");
			var fechaTermino = new Date((auxTermino[1].concat("/", auxTermino[2], "/", auxTermino[0]).toString()));

		var auxReporte =String(fechaReporte).split("-");
			var fechaReporte = new Date((auxReporte[1].concat("/", auxReporte[2], "/", auxReporte[0]).toString()));

			var diferencia = Math.abs(fechaReporte - fechaTermino);
			var dias = diferencia/(1000 * 3600 * 24); 	

			document.formulario.dias_restantes.value = parseInt(dias); //parseInt(diasTotales) - parseInt(dias);
	}
}

function personalContratista(){
	var administrador = parseInt(document.formulario.administrador_cantidad.value);
	var jefeTerreno = parseInt(document.formulario.jterreno_cantidad.value);
	var encargadoCalidad = parseInt(document.formulario.calidad_cantidad.value);
	var supervisorOOCC = parseInt(document.formulario.supervisor_cantidad.value);
	var prevencionista = parseInt(document.formulario.prevencionista_cantidad.value);
	var operadorCamionPluma = parseInt(document.formulario.operadorcp_cantidad.value);
	var operadorCamionTolva = parseInt(document.formulario.operadorct_cantidad.value);
	var operadorMultifuncional = parseInt(document.formulario.operadorm_cantidad.value);
	var maestroPrimera = parseInt(document.formulario.maestrop_cantidad.value);
	var jornal = parseInt(document.formulario.jornal_cantidad.value);
	var cantidadSubcontratista = parseInt(document.formulario.cantidad_subcontratista.value);
	var total = 0;

		total = (administrador + jefeTerreno) + encargadoCalidad + supervisorOOCC + prevencionista + operadorCamionPluma + operadorCamionTolva + operadorMultifuncional + maestroPrimera + jornal;
		// Total contratista
	document.formulario.cantidad_contratista.value = total;
	// Total
	if(isNaN(cantidadSubcontratista)){
		cantidadSubcontratista = 0;
	}
	document.formulario.total_trabajadores.value = total + cantidadSubcontratista;
}

function restarhoras(indicador){	
	var inicioNormal = document.formulario.hh_inicio_normal.value;    		
	var inicioExtras = document.formulario.hh_inicio_extras.value;    		
	var terminoNormal = document.formulario.hh_termino_normal.value;
	var terminoExtras = document.formulario.hh_termino_extras.value;
	var informeNormal = document.formulario.hh_informe_normal.value;
	var informeExtras = document.formulario.hh_informe_extras.value;
	var trayectoNormal = document.formulario.hh_trayecto_normal.value;
	var trayectoExtras = document.formulario.hh_trayecto_extras.value;
	var aux;
	var totalNormal;
	var totalExtras;
	if(inicioNormal != "" && terminoNormal != "" && informeNormal != "" && trayectoNormal != "" && indicador == 1){
		//Limpiamos 
		//document.formulario.hh_inicio_extras.value = "";
		//document.formulario.hh_termino_extras.value = "";
		//document.formulario.hh_informe_extras.value = "";
		//document.formulario.hh_total_extras.value = "";

		inicioMinutos = parseInt(inicioNormal.substr(3,2));
		inicioHoras = parseInt(inicioNormal.substr(0,2));

		finMinutos = parseInt(terminoNormal.substr(3,2));
		finHoras = parseInt(terminoNormal.substr(0,2));

		transcurridoMinutos = finMinutos - inicioMinutos;
		transcurridoHoras = finHoras - inicioHoras;

		if (transcurridoMinutos < 0) {
			transcurridoHoras--;
			transcurridoMinutos = 60 + transcurridoMinutos;
		}

		horas = transcurridoHoras.toString();
		minutos = transcurridoMinutos.toString();

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		aux = horas.concat(":", minutos);  				
		//totalNormal = sumarHoras(1, aux, informeNormal, 0);
		sumarHoras(1, aux, informeNormal, 0);
		//document.formulario.hh_total_normal.value = totalNormal;
	}
	else if(inicioExtras != "" && terminoExtras != "" && informeExtras != "" && trayectoExtras != "" && indicador == 2){ 
		//Limpiamos
		//document.formulario.hh_inicio_normal.value = "";
		//document.formulario.hh_termino_normal.value = "";
		//document.formulario.hh_informe_normal.value = "";
		//document.formulario.hh_informe_normal.value = "";
		//document.formulario.hh_total_normal.value = "";
		
		inicioMinutos = parseInt(inicioExtras.substr(3,2));
		inicioHoras = parseInt(inicioExtras.substr(0,2));

		finMinutos = parseInt(terminoExtras.substr(3,2));
		finHoras = parseInt(terminoExtras.substr(0,2));

		transcurridoMinutos = finMinutos - inicioMinutos;
		transcurridoHoras = finHoras - inicioHoras;

		if (transcurridoMinutos < 0) {
			transcurridoHoras--;
			transcurridoMinutos = 60 + transcurridoMinutos;
		}

		horas = transcurridoHoras.toString();
		minutos = transcurridoMinutos.toString();

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		aux = horas.concat(":", minutos);
		//totalExtras = sumarHoras(2, aux, informeExtras, 0); 
		sumarHoras(2, aux, informeExtras, 0); 
		//document.formulario.hh_total_extras.value = totalExtras;
	}
}

function sumarHoras(tipo, hora_uno, hora_dos, fin){
	var array_horaUno = hora_uno.split(":");
	var array_horaDos = hora_dos.split(":");
	var hora_horaUno = array_horaUno[0];
	var hora_horaDos = array_horaDos[0];
	var minutos_horaUno = array_horaUno[1];
	var minutos_horaDos = array_horaDos[1];
	var sumaMinutos = 0;
	var sumaHoras = 0;
	sumaHoras = parseInt(hora_horaUno) + parseInt(hora_horaDos);    		
	sumaMinutos = parseInt(minutos_horaUno) + parseInt(minutos_horaDos);
	if(sumaMinutos >= 60 && sumaMinutos < 120){
		sumaHoras = sumaHoras + 1;
		sumaMinutos = sumaMinutos - 60;    			
	}
	else if(sumaMinutos >= 120){
		sumaHoras = sumaHoras + 2;
		sumaMinutos = sumaMinutos - 120;
	}

	if(sumaHoras < 10){
		str_sumaHoras = sumaHoras.toString();
		str_sumaHoras_dos = "0" + str_sumaHoras;
	}
	else{
		str_sumaHoras_dos = sumaHoras.toString();
	}
	if(sumaMinutos < 10){
		str_sumaMinutos = sumaMinutos.toString();
		str_sumaMinutos_dos = "0" + str_sumaMinutos;	
	}
	else{
		str_sumaMinutos_dos = sumaMinutos.toString();
	}
	aux = str_sumaHoras_dos.concat(":", str_sumaMinutos_dos);
	//return(aux);

	if(fin == 0){
		if(tipo == 1){
			var trayecto = document.formulario.hh_trayecto_normal.value;
		}
		else if(tipo == 2){
			var trayecto = document.formulario.hh_trayecto_extras.value;			
		}	
		sumarHoras(tipo, aux, trayecto, 1);
	}
	else{
		if(tipo == 1){
			document.formulario.hh_total_normal.value = aux;
		}
		else if(tipo == 2){
			document.formulario.hh_total_extras.value = aux;			
		}		
	}
}

function calculoHora(hora_inicio, hora_fin, operacion){	
	if(operacion.localeCompare("resta") == 0){
		//Calculamos contratista
		var inicioMinutos = parseInt(hora_inicio.substr(3,2));
		var inicioHoras = parseInt(hora_inicio.substr(0,2));
	
		var finMinutos = parseInt(hora_fin.substr(3,2));
		var finHoras = parseInt(hora_fin.substr(0,2));

		var transcurridoMinutos = finMinutos - inicioMinutos;
		var transcurridoHoras = finHoras - inicioHoras;	

		if (transcurridoMinutos < 0) {
			transcurridoHoras--;
			transcurridoMinutos = 60 + transcurridoMinutos;
		}

		horas = transcurridoHoras.toString();
		minutos = transcurridoMinutos.toString();

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		aux = horas.concat(":", minutos);
		return(aux);
	}
	else if(operacion.localeCompare("suma") == 0){
		var array_horaUno = hora_inicio.split(":");
		var array_horaDos = hora_fin.split(":");
		var hora_horaUno = array_horaUno[0];
		var hora_horaDos = array_horaDos[0];
		var minutos_horaUno = array_horaUno[1];
		var minutos_horaDos = array_horaDos[1];
		var sumaMinutos = 0;
		var sumaHoras = 0;
		
		sumaHoras = parseInt(hora_horaUno) + parseInt(hora_horaDos);    		
		sumaMinutos = parseInt(minutos_horaUno) + parseInt(minutos_horaDos);
		if(sumaMinutos >= 60 && sumaMinutos < 120){
			sumaHoras = sumaHoras + 1;
			sumaMinutos = sumaMinutos - 60;    			
		}
		else if(sumaMinutos >= 120){
			sumaHoras = sumaHoras + 2;
			sumaMinutos = sumaMinutos - 120;
		}
		if(sumaHoras < 10){
			str_sumaHoras = sumaHoras.toString();
			str_sumaHoras_dos = "0" + str_sumaHoras;
		}
		else{
			str_sumaHoras_dos = sumaHoras.toString();
		}
		if(sumaMinutos < 10){
			str_sumaMinutos = sumaMinutos.toString();
			str_sumaMinutos_dos = "0" + str_sumaMinutos;	
		}
		else{
			str_sumaMinutos_dos = sumaMinutos.toString();
		}
		aux = str_sumaHoras_dos.concat(":", str_sumaMinutos_dos);
		return(aux);
	}
}

function horasTrabajadores(){
	var contratista_cantidad = document.formulario.contratista_cantidadbnup.value;
	var contratista_inicio = document.formulario.contratista_iniciobnup.value;		
	var contratista_termino = document.formulario.contratista_terminobnup.value;
	var subcontratista_cantidad = document.formulario.subcontratista_cantidadbnup.value;
	var subcontratista_inicio = document.formulario.subcontratista_iniciobnup.value;
	var subcontratista_termino = document.formulario.subcontratista_terminobnup.value;
	var aux1;
	var aux2;

	var totalNormal;
	var totalExtras;

	if(contratista_cantidad == ""){
		contratista_cantidad = 0;
	}
	if(contratista_inicio == ""){
		contratista_inicio = "00:00"
	}
	if(contratista_termino == ""){
		contratista_termino = "00:00"
	}
	if(subcontratista_cantidad == ""){
		subcontratista_cantidad = 0;
	}
	if(subcontratista_inicio == ""){
		subcontratista_inicio = "00:00"
	}
	if(subcontratista_termino == ""){
		subcontratista_termino = "00:00"
	}

	total_contratista = calculoHora(contratista_inicio, contratista_termino, "resta");	
	total_subcontratista = calculoHora(subcontratista_inicio, subcontratista_termino, "resta");

	if(total_contratista.length < 5){
		total_contratista = total_contratista + "0"	
	}
	if(total_subcontratista.length < 5){
		total_subcontratista = total_subcontratista + "0"	
	}

	total = calculoHora(total_contratista, total_subcontratista, "suma");

	document.formulario.total_cantidadbnup.value = parseInt(contratista_cantidad, 10) + parseInt(subcontratista_cantidad, 10);
	document.formulario.total_bnup.value = total;




/*	if(inicioNormal != "" && terminoNormal != "" && informeNormal != "" && trayectoNormal != ""){
		//Limpiamos 
		//document.formulario.hh_inicio_extras.value = "";
		//document.formulario.hh_termino_extras.value = "";
		//document.formulario.hh_informe_extras.value = "";
		//document.formulario.hh_total_extras.value = "";

		inicioMinutos = parseInt(inicioNormal.substr(3,2));
		inicioHoras = parseInt(inicioNormal.substr(0,2));

		finMinutos = parseInt(terminoNormal.substr(3,2));
		finHoras = parseInt(terminoNormal.substr(0,2));

		transcurridoMinutos = finMinutos - inicioMinutos;
		transcurridoHoras = finHoras - inicioHoras;

		if (transcurridoMinutos < 0) {
			transcurridoHoras--;
			transcurridoMinutos = 60 + transcurridoMinutos;
		}

		horas = transcurridoHoras.toString();
		minutos = transcurridoMinutos.toString();

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		if (horas.length < 2) {
			horas = "0"+horas;
		}

		aux = horas.concat(":", minutos);  				
		//totalNormal = sumarHoras(1, aux, informeNormal, 0);
		sumarHoras(1, aux, informeNormal, 0);
		//document.formulario.hh_total_normal.value = totalNormal;
	}
	else if(inicioExtras != "" && terminoExtras != "" && informeExtras != "" && trayectoExtras != ""){ 
		//Limpiamos
		//document.formulario.hh_inicio_normal.value = "";
		//document.formulario.hh_termino_normal.value = "";
		//document.formulario.hh_informe_normal.value = "";
		//document.formulario.hh_informe_normal.value = "";
		//document.formulario.hh_total_normal.value = "";
		
		inicioMinutos = parseInt(inicioExtras.substr(3,2));
		inicioHoras = parseInt(inicioExtras.substr(0,2));

		finMinutos = parseInt(terminoExtras.substr(3,2));
		finHoras = parseInt(terminoExtras.substr(0,2));

		transcurridoMinutos = finMinutos - inicioMinutos;
			transcurridoHoras = finHoras - inicioHoras;

			if (transcurridoMinutos < 0) {
			transcurridoHoras--;
			transcurridoMinutos = 60 + transcurridoMinutos;
			}

			horas = transcurridoHoras.toString();
			minutos = transcurridoMinutos.toString();

			if (horas.length < 2) {
			horas = "0"+horas;
			}

			if (horas.length < 2) {
			horas = "0"+horas;
			}

			aux = horas.concat(":", minutos);
			//totalExtras = sumarHoras(2, aux, informeExtras, 0); 
			sumarHoras(2, aux, informeExtras, 0); 
			//document.formulario.hh_total_extras.value = totalExtras;
	}
*/
}

function validarExtension(numeroImagen){
	if(numeroImagen == 1){ var nombre = document.formulario.foto_1; }
	if(numeroImagen == 2){ var nombre = document.formulario.foto_2; }
	if(numeroImagen == 3){ var nombre = document.formulario.foto_3; }
	if(numeroImagen == 4){ var nombre = document.formulario.foto_4; }
	if(numeroImagen == 5){ var nombre = document.formulario.foto_5; }
	if(numeroImagen == 6){ var nombre = document.formulario.foto_6; }
	if(numeroImagen == 7){ var nombre = document.formulario.libro_obra; }    		
	
	nombre_aux = nombre.value;
	var array_nombre = nombre_aux.split(".");
	var elementos = array_nombre.length;
	var extensiones = ['jpg','jpeg', 'png', 'bmp', 'JPG', 'JPEG', 'PNG', 'BMP'];
	if(!extensiones.includes(array_nombre[elementos-1])){
		window.alert("Solo se permiten imagenes con extensión jpg, jpeg, png y bmp.");				
		nombre.value = "";
		nombre.focus();
	}
	previewImage(numeroImagen);
}

function previewImage(nb) {
	if(nb == 1){
		document.formulario.uploadPreview1.style.display = "inline";  		
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_1.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview1.src = e.target.result;         
		};	

	}
	if(nb == 2){
		document.formulario.uploadPreview2.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_2.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview2.src = e.target.result;         
		};	
	}	     
	if(nb == 3){
		document.formulario.uploadPreview3.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_3.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview3.src = e.target.result;         
		};	
	}	
	if(nb == 4){
		document.formulario.uploadPreview4.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_4.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview4.src = e.target.result;         
		};	
	}	
	if(nb == 5){
		document.formulario.uploadPreview5.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_5.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview5.src = e.target.result;         
		};	
	}	
	if(nb == 6){
		document.formulario.uploadPreview6.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.foto_6.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview6.src = e.target.result;         
		};	
	}
	if(nb == 7){
		document.formulario.uploadPreview7.style.display = "inline";
		var reader = new FileReader();         
    	reader.readAsDataURL(document.formulario.libro_obra.files[0]);         
    	reader.onload = function (e) {             
        	document.formulario.uploadPreview7.src = e.target.result;         
		};	
	}	
}