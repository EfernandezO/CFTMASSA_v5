<!--

function escrit_inicial()
{
	if (document.all) document.all.clock.innerHTML=texte_colors;
	else 
	{
		if (document.getElementById)
		{
			document.getElementById("clock").innerHTML=texte_colors;
		}
	}
}


function encripta(Str_Message)
{
	Len_Str_Message=Str_Message.length;
	Str_Encrypted_Message="";
	for (Position = 0 ; Position<Len_Str_Message ; Position++)
	{
		Byte_To_Be_Encrypted = Str_Message.substring(Position, Position+1); 
		Ascii_Num_Byte = 999-Str_Message.charCodeAt(Position);
		Str_Encrypted_Message=Str_Encrypted_Message+Ascii_Num_Byte;

	} 
	return(Str_Encrypted_Message);
}

function calcula_checksum(Str_Message)
{

	longitud=Str_Message.length;
	suma=0;
	for (Position = 0 ; Position<longitud ; Position++)
	{
		codi = Str_Message.substring(Position, Position+1); 
		suma = suma+Str_Message.charCodeAt(Position);
	} 
	cadena=""+suma;
	return(cadena);


}


function analitza_resultat(errors)
{
	var perrors=round(errors*100/longitud);
		if((hora_fi=="")||(hora_fi==undefined))
		{ hora_fi=CalculaHora();}
	var segons=hora_fi-hora_inici;
	var canvi_de_dia=0;

	if (errors<0) errors=0;

	if (segons<1)
	{
		segons=segons+86400;
		canvi_de_dia=1;
	}

	if (paste_utilitzat==1)
	{
		//segons=10000;
		document.getElementById('indicador_tiempo').value="error";//cambio estado del indicador para saber que ocurrio error pero en la seccion resultador y mantener el tiempo...
	}

	if (canvi_de_dia==0)
	{
		if (hora_fi<hores[8]) segons=segons+300;
		if (hora_fi<hores[7]) segons=segons+300;
		if (hora_fi<hores[6]) segons=segons+300;
		if (hora_fi<hores[5]) segons=segons+300;
		if (hora_fi<hores[4]) segons=segons+300;
		if (hora_fi<hores[3]) segons=segons+300;
		if (hora_fi<hores[2]) segons=segons+300;
		if (hora_fi<hores[1]) segons=segons+300;
		if (hora_fi<hores[0]) segons=segons+300;
		if (hores[8]<hores[7]) segons=segons+300;
		if (hores[7]<hores[6]) segons=segons+300;
		if (hores[6]<hores[5]) segons=segons+300;
		if (hores[5]<hores[4]) segons=segons+300;
		if (hores[4]<hores[3]) segons=segons+300;
		if (hores[3]<hores[2]) segons=segons+300;
		if (hores[2]<hores[1]) segons=segons+300;
		if (hores[1]<hores[0]) segons=segons+300;
		if (hores[0]<hora_inici) segons=segons+300;
	}

	variables_a_encriptar="x="+errors+"&l="+longitud+"&s="+segons+"&h="+hora_servidor+"&";

	variables_encriptades=encripta(variables_a_encriptar);

        checksum=calcula_checksum(variables_encriptades);
	id_leccion=document.getElementById('id_leccion').value;
	total_pulsaciones=document.getElementById('contador').value;
	indicador_tiempo=document.getElementById('indicador_tiempo').value;
	location="puente_codif.php?v=1&"+variables_a_encriptar+"ID="+id_leccion+"&p="+total_pulsaciones+"&it="+indicador_tiempo+"&w="+checksum+"&";
}

function CheckKey(event)
{
	if (navigator.appName != 'Netscape')
	{
		tecla_marcada=window.event.keyCode;
		if (window.event.shiftKey) 
		{
  			 vigila=1;
	 	}
		if ( (navigator.platform == 'Win32') || (navigator.platform == 'Win64') || (navigator.platform == 'Windows') )
		{
			if ( (tecla_marcada==186) || (tecla_marcada==222) )
			{
				if (jaheaixecat==1)
				{
					if ( (tecla_anterior!=186) && (tecla_anterior!=222) )
					{
						errors=errors-1;
						jaheaixecat=0;
					}
				}
			}
		}
	}
	else
	{
		tecla_marcada=event.which
		if (tecla_marcada==16)
		{
			vigila=1;
		}
		if ( (navigator.platform == 'Win32') || (navigator.platform == 'Win64') || (navigator.platform == 'Windows') )
		{
			if ( (tecla_marcada==222) || (tecla_marcada==59) )
			{
				if (jaheaixecat==1)
				{
					if( (tecla_anterior!=222) && (tecla_anterior!=59)  )
					{
						errors=errors-1;
						jaheaixecat=0;
					}
				}
			}
		}
	}
	tecla_anterior=tecla_marcada;
}

function Comprova_ok(a,event)
{
	var tecla;
	var valor_tecla_event;

	jaheaixecat=1;

	if (navigator.appName != 'Netscape') 
	{
		if (window.event.keyCode==20) {a=a.substr(0,contador_real); return(a);}
		if (window.event.keyCode==8) { a=valor_anterior.substr(0,contador_real+1); return(a);}
		tecla=a.substr(contador_real,1);
		valor_tecla_event=window.event.keyCode;
	}
	else
	{
		if (event.which==20) {a=a.substr(0,contador_real); return(a);}
		if (event.which==8) { a=valor_anterior.substr(0,contador_real+1); return(a);}

		if (event.which==13) tecla=String.fromCharCode(event.which);
		else tecla=a.substr(contador_real,1);
		valor_tecla_event=event.which;
	}

	if (punter==0) hora_inici=CalculaHora(hora_inici)

	if (texte.substr(punter,4)=="<br>")
	{
		if (valor_tecla_event != 13)
		{
			if (vigila!=1)
			{
				errors=errors+1
			}
			a=a.substr(0,contador_real)
			vigila=0
		}
		else
		{
			hores[linia]=CalculaHora(hores[linia]);
			linia=linia+1;

			punter=punter+4

			if (navigator.appName=='Netscape') contador_real=contador_real+1;
			else
			{
				var Browser = {
				  Version: function() {
				    var version = 999; // we assume a sane browser
				    if (navigator.appVersion.indexOf("MSIE") != -1) version = parseFloat(navigator.appVersion.split("MSIE")[1]);
				    return version;
				  }
				}
				if (Browser.Version()==9) contador_real=contador_real+1;
				else contador_real=contador_real+2;
			}

			if(texte.substring(cursor_pos_color+num_salt_ini,cursor_pos_color+num_salt_ini+4)=='<br>')
			{	
				num_salt=num_salt_ini+3
			}
			else
			{
				if(num_salt>num_salt_ini) num_salt--;
				final_linea=0
				num_salt=num_salt_ini
			}

			cursor_pos_color=punter;
			inici_text_no_marcat=texte.substring(0,cursor_pos_color)
			text_marcat=texte.substring(cursor_pos_color, cursor_pos_color+num_salt)
			fi_text_no_marcat=texte.substring(cursor_pos_color+num_salt,longitud)
			texte_colors='<FONT FACE="courier new,courier"><FONT SIZE='+tamano+'>'+inici_text_no_marcat+'<FONT COLOR="red"><U>'+text_marcat+'</FONT COLOR></U>'+fi_text_no_marcat+'</FONT SIZE></FONT FACE>';
			if (document.all) document.all.clock.innerHTML=texte_colors;
			else 
			{
				if (document.getElementById)
				{
					document.getElementById("clock").innerHTML=texte_colors;
				}
			}
		}
	}
	else
	{
		if (tecla!=texte.substr(punter,1))
    		{

			if (plataforma=="mac")
			{
				if(nav=="Safari")
				{
					if (vigila!=1)
					{
						errors=errors+1;
					}				
					a=a.substr(0,contador_real)
					vigila=0

				}
				else
				{
					if(nav=="Firefox")
					{
						if ((event.keyCode==0) || (event.keyCode==192) || (event.keyCode==16))
						{
						}
						else
						{
							if (vigila!=1)
							{
								errors=errors+1;
							}				
							a=a.substr(0,contador_real)
							vigila=0
						}
					}
					else
					{
						if(nav=="Netscape")
						{
							if ((event.keyCode==0) || (event.keyCode==192))
							{
							}
							else
							{
								if (vigila!=1)
								{
									errors=errors+1;
								}				
								a=a.substr(0,contador_real)
								vigila=0
							}
						}
						else // qualsevol altre navegador (ie, opera, ...) - no provat
						{
							if ((event.keyCode==0) || (event.keyCode==192))
							{
							}
							else
							{
								if (vigila!=1)
								{
									errors=errors+1;
								}				
								a=a.substr(0,contador_real)
								vigila=0
							}
						}
					}
				}
			}
			else // no mac
			{
				if (vigila!=1)
				{
					if (navigator.appName != 'Netscape') errors=errors+1;
					else
					{ 
						if ( (navigator.platform == 'Win32') || (navigator.platform == 'Win64') || (navigator.platform == 'Windows') )
						{
							errors=errors+1;
						}
						else if (tecla!=0) errors=errors+1;
					}				
				}
				a=a.substr(0,contador_real)
				vigila=0
			}
    		}
    		else
    		{
			punter=punter+1
			contador_real=contador_real+1

			if(texte.substring(cursor_pos_color+num_salt_ini,cursor_pos_color+num_salt_ini+4)=='<br>')
			{	
				num_salt=num_salt_ini+3
				final_linea=1
			}
			else
			{
				if (final_linea==1)
				{
					if(num_salt>4) num_salt--;
				}
				else
				{
					if(num_salt>num_salt_ini) num_salt--;
				}
			}

			cursor_pos_color=punter;
			inici_text_no_marcat=texte.substring(0,cursor_pos_color)
			text_marcat=texte.substring(cursor_pos_color, cursor_pos_color+num_salt)
			fi_text_no_marcat=texte.substring(cursor_pos_color+num_salt,longitud)
			texte_colors='<FONT FACE="courier new,courier"><FONT SIZE='+tamano+'>'+inici_text_no_marcat+'<FONT COLOR="red"><U>'+text_marcat+'</FONT COLOR></U>'+fi_text_no_marcat+'</FONT SIZE></FONT FACE>';
			if (document.all) 
			{
				document.all.clock.innerHTML=texte_colors;
			}
			else 
			{
				if (document.getElementById)
				{
					document.getElementById("clock").innerHTML=texte_colors;
				}

			}
    		}
    	}

	if (longitud==punter) 
	{
		hora_fi=CalculaHora(hora_fi)
		analitza_resultat(errors)	
	}
	valor_anterior=a;
	return(a)
}

function round (n) {
    n = Math.round(n * 100) / 100;
    n = (n + 0.001) + '';
    return n.substring(0, n.indexOf('.') + 3);
  }

function CalculaHora(a)
{
	var d = new Date()
	a=d.getHours()*3600+d.getMinutes()*60+d.getSeconds()
	return(a)
}


function Calcula(Operacion)
{ 
    var Formu = Operacion.form 
    var Expresion = total + UltimaOperacion + Formu.display.value 
    UltimaOperacion = Operacion.value 
    total = eval(Expresion) 
    Formu.display.value = total 
    NuevoNumero = true
}


function d_tamany()
{
	if (tamano>1) 
	{
		tamano=tamano-1;
		texte_colors='<FONT FACE="courier new,courier"><FONT SIZE='+tamano+'>'+inici_text_no_marcat+'<FONT COLOR="red"><U>'+text_marcat+'</FONT COLOR></U>'+fi_text_no_marcat+'</FONT SIZE></FONT FACE>';
		if (document.all) document.all.clock.innerHTML=texte_colors;
		else 
		{
			if (document.getElementById)
			{
				document.getElementById("clock").innerHTML=texte_colors;
			}
		}
	}
}
function a_tamany()
{
	if (tamano<8) 
	{
		tamano=tamano+1;
		texte_colors='<FONT FACE="courier new,courier"><FONT SIZE='+tamano+'>'+inici_text_no_marcat+'<FONT COLOR="red"><U>'+text_marcat+'</FONT COLOR></U>'+fi_text_no_marcat+'</FONT SIZE></FONT FACE>';
		if (document.all) document.all.clock.innerHTML=texte_colors;
		else 
		{
			if (document.getElementById)
			{
				document.getElementById("clock").innerHTML=texte_colors;
			}
		}
	}
}


var valor_anterior=""
var total = 0
var UltimaOperacion = "+" 
var NuevoNumero = true
var punter=0
var contador_real=0
var num_salt_ini=1
var num_salt=num_salt_ini
var errors=0
var perrors=0
var longitud=0
var vigila=0
var hora_inici
var hora_fi
var posicio_cursor=0
var text_marcat
var cursor_pos_color=0
var texte_colors;
var tamano=3;
var jaheaixecat=1;
var tecla_anterior;
var final_linea=0;

var linia=0;
var hores=new Array(0,0,0,0,0,0,0,0,0);

var platform = window.navigator.platform.toLowerCase();
var plataforma="";
var navegador=window.navigator.userAgent.toLowerCase();
var nav="";

if (platform.indexOf('mac') != -1) plataforma='mac';

if (navegador.indexOf('safari') != -1) nav='Safari';
else if (navegador.indexOf('firefox') != -1) nav='Firefox';
else if (navegador.indexOf('netscape') != -1) nav='Netscape';
else if (navegador.indexOf('opera') != -1) nav='Opera';
else if (navegador.indexOf('explorer') != -1) nav='Explorer';

if (screen.width > 800) tamano=4;
else
{
	if (screen.width > 640) tamano=3;
	else tamano=2;
}


longitud=texte.length

inici_text_no_marcat=texte.substring(0,cursor_pos_color-1)
text_marcat=texte.substring(cursor_pos_color, cursor_pos_color+num_salt)
fi_text_no_marcat=texte.substring(cursor_pos_color+num_salt,longitud)
texte_colors='<FONT FACE="courier new,courier"><FONT SIZE='+tamano+'>'+inici_text_no_marcat+'<FONT COLOR="red"><U>'+text_marcat+'</FONT COLOR></U>'+fi_text_no_marcat+'</FONT SIZE></FONT FACE>';

-->
