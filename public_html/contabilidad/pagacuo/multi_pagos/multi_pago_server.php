<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("multi_pago_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"MULTI_CHEQUE");
////////////////////////////////////////////

function MULTI_CHEQUE($cantidad_cheques, $valor_cheque)
{
	$html="";
	$div="div_cheque";
	$div_script="script_calendar";
	/////////////
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
	///////////////
	$objResponse = new xajaxResponse();
	$aux=0;
	
	if($cantidad_cheques==1)
	{ $editaX='readonly="readonly"';}
	else
	{ $editaX='';}
	$aux_valor_cheque=($valor_cheque/$cantidad_cheques);
	
	for($i=1;$i<=$cantidad_cheques;$i++)
	{
		//color
		if($aux%2==0)
		{ $color="#f5f5f5";}
		else{ $color="#e5e5e5";}
		$aux++;
		//---------------//
		$id_aux=$i;
		
		
		$html.='<table width="100%" height="103" border="0">
            <tr>
              <td colspan="2" bgcolor="'.$color.'">&nbsp;</td>
            </tr>
            <tr>
              <td bgcolor="'.$color.'">('.$i.') N&deg; Cheque</td>
              <td bgcolor="'.$color.'"><input name="cheque_numero[]" type="text" id="cheque_numero_'.$i.'" /></td>
            </tr>
            <tr>
              <td width="11%" bgcolor="'.$color.'">('.$i.') Banco</td>
              <td width="21%" bgcolor="'.$color.'"><select name="cheque_banco[]" id="cheque_banco_'.$i.'">'; 
		 foreach($array_bancos as $n)
		 {
		 	
				$html.='<option value="'.$n.'">'.$n.'</option>';	
		 }
              $html.='</select></td>
            </tr>
            <tr>
              <td bgcolor="'.$color.'">('.$i.') Fecha Vence</td>
              <td bgcolor="'.$color.'">
                <select name="dia[]" id="dia_'.$i.'">';
				
						$dia_actual=date("d");
						for($d=1;$d<=30;$d++)
						{
							if($d==$dia_actual)
							{ $html.='<option value="'.$d.'" selected="selected">'.$d.'</option>';}
							else
							{ $html.='<option value="'.$d.'">'.$d.'</option>';}
						}
               
                $html.='</select>
                /
                <select name="mes[]" id="mes_'.$i.'">';
						$mes_actual=date("m");
						for($m=1;$m<=12;$m++)
						{
							if($m==$mes_actual)
							{ $html.='<option value="'.$m.'" selected="selected">'.$m.'</option>';}
							else
							{ $html.='<option value="'.$m.'">'.$m.'</option>';}
						}
                 
                $html.='</select>
                /
                <select name="year[]" id="year_'.$i.'">';
						$year_actual=date("Y");
						for($y=2010;$y<=2050;$y++)
						{
							if($y==$year_actual)
							{ $html.='<option value="'.$y.'" selected="selected">'.$y.'</option>';}
							else
							{ $html.='<option value="'.$y.'">'.$y.'</option>';}
						}
               
                $html.='</select>
                <em>
                *Utilice solo Dias Habiles*
                  </em></td>
            </tr>
			 <tr>
              <td bgcolor="'.$color.'">('.$i.') Valor</td>
              <td bgcolor="'.$color.'"><input type="text" name="cheque_valor[]" id="cheque_valor_'.$i.'"  value="'.$aux_valor_cheque.'" '.$editaX.'  size="10"/></td>
            </tr>
          </table>
                </div>            
             </td>
          </tr>
          </table>';
	}  			

	$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
///////////////
$xajax->processRequest();
?>