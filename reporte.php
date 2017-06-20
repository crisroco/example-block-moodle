<?php

include 'Classes/PHPExcel.php';
require_once("../../config.php");
require_once('locallib.php');
global $DB, $USER;
$phpexcel = new PHPExcel();

$sheet = $phpexcel->getActiveSheet()->setTitle('reporte completo'); //Setting index when creating
$phpexcel->setActiveSheetIndex(0);

//###################CABECERA######################
	//asignar sutosize a las columnas
	$letras = array("A","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',);
	foreach ($letras as $key => $value) {
		$sheet->getColumnDimension($value)->setAutoSize(true);			
	}


	$styleArray = array(    
	      'alignment' => array(
	          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	      ),
	      'borders' => array(
	          'allborders' => array(
	              'style' => PHPExcel_Style_Border::BORDER_THIN,
	          ),
	      ),
	      'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => 'A0A0A0')
	            ),
	            'font' => array(
					'bold'  => true,
					'color' => array('rgb' => '404040'),
					'size'  => 20,
	            )
	);
	$sheet->mergeCells('A2:AZ2');
	$sheet->getStyle('A2:AZ2')->applyFromArray($styleArray);
	$sheet->getRowDimension('2')->setRowHeight(32);
	$sheet->setCellValueByColumnAndRow(0,2, 'Reporte de participación y resultados');

	//Add logo de ucic
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$logo =  'assets/img/UCIC.png'; // Provide path to your logo file
	$objDrawing->setPath($logo);
	$objDrawing->setOffsetX(6);    // setOffsetX works properly
	//$objDrawing->setOffsetY(300);  //setOffsetY has no effect
	$objDrawing->setCoordinates('B4');
	$objDrawing->setHeight(75); // logo height
	$objDrawing->setWorksheet($sheet); 


	$styleArray = array(    
	      'alignment' => array(
	          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	      ),
	       'borders' => array(
	          'allborders' => array(
	              'style' => PHPExcel_Style_Border::BORDER_THIN,
	          ),
	      ),
	      'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => 'FFFFFF')
	            ),
	            'font' => array(
					'bold'  => true,
					'color' => array('rgb' => '838383'),
					'size'  => 20,
	            )
	      );
	$sheet->mergeCells('F5:H6');
	$sheet->getStyle('F5:H6')->applyFromArray($styleArray);
	$sheet->setCellValueByColumnAndRow(5,5, 'Ejercicio Virtual');


	$styleArray = array(    
	      'alignment' => array(
	          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	      ),
	       'borders' => array(
	          'allborders' => array(
	              'style' => PHPExcel_Style_Border::BORDER_THIN,
	          ),
	      ),
	      'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => 'FFFFFF')
	            ),
	            'font' => array(
					'bold'  => true,
					'color' => array('rgb' => '336600'),
					'size'  => 20,
	            )
	      );
	$sheet->mergeCells('J5:R6');
	$sheet->getStyle('J5:R6')->applyFromArray($styleArray);
	$sheet->setCellValueByColumnAndRow(9,5, 'Continuidad del negocio');
//###################FIN - CABECERA######################

////###################CABECERA DATOS DE LOS ALUMNOS######################
	$td_alumno = 0;
	

	$title_alum = array('N°','DNI','APELLIDOS', 'NOMBRES', 'EMPRESA ', 'CORREO', 'GRUPO','------','------');
  $td=0;
  $styleArray = array(    
	      'alignment' => array(
	          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	      ),
	       'borders' => array(
	          'allborders' => array(
	              'style' => PHPExcel_Style_Border::BORDER_THIN,
	          ),
	      ),
	      'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => '808080')
	            ),
	            'font' => array(
					'bold'  => true,
					'color' => array('rgb' => 'E0E0E0'),
					'size'  => 13,
	            )
	      );
  $sheet->getRowDimension('10')->setRowHeight(28);
  $sheet->getStyle('A10:I10')->applyFromArray($styleArray);
  foreach ($title_alum as $key => $value) {
      $sheet->setCellValueByColumnAndRow($td,10, $value);
      $td++;
  }
////###################FIN CABECERA DATOS DE LOS ALUMNOS######################

////###################DATOS DE LOS ALUMNOS######################

  $datos_alumno = get_report_data($_GET['group'], $_GET['scorm'], $_GET['courseid']);
  $tr_user_data = 11;
  $cont = 1;
  //Recorrer array con todos los datos
  foreach ($datos_alumno as $key => $value) {
  	$td_user_data = 1;  	
  	$title_answer_statet = true;
  	//numeracion de cada fila
  	$sheet->setCellValueByColumnAndRow(0,$tr_user_data, $cont);

  	//recorrer cada array de datos (por alumno)
  	foreach ($value as $ke => $valu) { 

  		//si la llave tiene estos valores salta al siguiente
  		if ($ke == 'userid' || $ke == 'curosmod_id' || $ke == 'sct_id') {
  		continue;
  		
  		//imprime los datos de los alumnos 			
  		}elseif ($ke !== 'value') {
  			
	      $sheet->setCellValueByColumnAndRow($td_user_data,$tr_user_data, $valu);
	      $td_user_data++;
	   
	   //imprime las respuestas de los alumnos   
  		}elseif ($ke == 'value' && $valu !== '') {
  			
  			$answertemp = array();
  			$td_user_answer = 9;

  			//decodificar el json con las respuestas
  			$valu = json_decode($valu);

  			//recorre el array que contiene las respuestas 
  			foreach ($valu as $llave => $valor) {
  				//si la llave tiene lo siguientes valores los almacenará en un array temporal()
  				//if ($valor->name == 'P1' || $valor->name == 'P2' || $valor->name == 'P11') {
  					//$answertemp[$valor->name] = $valor->answer;

  				//imprime en el excel las respuestas	
  				//}else{
  					//imprime los titulos de cada respuesta dependiendo del estado del flag($title_answer_statet)
  					if ($title_answer_statet) {
  						$sheet->setCellValueByColumnAndRow($td_user_answer,10, $valor->name);
  						$styleArray = array(    
				      'alignment' => array(
				          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				      ),
				       'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN,
				          ),
				      ),
				      'fill' => array(
				                'type' => PHPExcel_Style_Fill::FILL_SOLID,
				                'color' => array('rgb' => '52923e')
				            ),
				      'font' => array(
								'bold'  => true,
								'color' => array('rgb' => 'E0E0E0'),
								'size'  => 13,
				            )
				      );
				   	$sheet->getStyle('J10:T10')->applyFromArray($styleArray);
  					}

	      		$sheet->setCellValueByColumnAndRow($td_user_answer,$tr_user_data, $valor->answer);
	      		$td_user_answer++;
  				//}				
  			}

  			//estilos apra todos los datos de los alumnos
  			$styleArray = array(    
				      'alignment' => array(
				          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				      )
			);
			$sheet->getStyle('A11:T'.$tr_user_data)->applyFromArray($styleArray);


  			/*$title_answer_statet2 = true;
  			//recorre el array teporal generado anteriormentePHP_EOL
			foreach ($answertemp as $llave => $valor) {
				if ($llave == 'P1') {

					if ($title_answer_statet2) {
  						$sheet->setCellValueByColumnAndRow($td_user_answer,10, 'Reconoce al miembro de equipo');
  					}

					//convierte en  array el string para poder agregarle el salto de linea
					$valortemp =  explode(",", $valor);
					$newValor = ''; 
					//inicializando el alto de la celda
					$alto_celda = 0;	

					//recorre el array y agrega el salto de linea a cada elemento				
					foreach ($valortemp as $k => $v) {
						$newValor .= '- '.$v.PHP_EOL;
						//agrega un valor de 20 por cada dato en la celda
						$alto_celda += 20;
					}

					$sheet->setCellValueByColumnAndRow($td_user_answer,$tr_user_data, $newValor);
		      	$td_user_answer++;
				}else{
					if ($title_answer_statet2) {
  						$sheet->setCellValueByColumnAndRow($td_user_answer,10, 'Algún miembro no aparece en la lista');
  						$sheet->setCellValueByColumnAndRow($td_user_answer+1,10, 'Comentarios');
  						$title_answer_statet2 = false;
  					}

					$sheet->setCellValueByColumnAndRow($td_user_answer,$tr_user_data, $valor);
					$td_user_answer++;
				}
			}*/



  			$title_answer_statet = false;
  			
	      $td_user_answer++;
 		}

  	}

  	//asigna el alto a la fila dependiendo de la cantidad de datos que tenga
  	//$sheet->getRowDimension($tr_user_data)->setRowHeight($alto_celda);
  	$tr_user_data++;
  	$cont++;
  }
  /*echo "<pre>";
  	print_r($datos_alumno);
  echo "</pre>";*/
////###################FIN  DATOS DE LOS ALUMNOS######################

$writer = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$nombre = 'UCIC_Scorm';
$hoy = date("j_F_Y");
$filename = 'Reporte_'. $nombre ."_".$hoy.'.xlsx';
$writer->save($filename);
//header("Content-type:xlsx");
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header("Content-disposition: attachment; filename=$filename");
//header('Content-Disposition: attachment; filename="'.basename($filename).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
readfile("$filename");
unlink($filename);