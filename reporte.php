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
	$letras = array("A","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","U","V","W","X","Y","Z",'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',);
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
	$sheet->mergeCells('J5:N6');
	$sheet->getStyle('J5:N6')->applyFromArray($styleArray);
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
  $datos_alumno = get_report_data('65', '23');
  foreach ($datos_alumno as $key => $value) {
  	$td = 1;
  	$tr = 11;
  	$cont = 1;
  	foreach ($value as $ke => $valu) { 
  		if ($ke == 'id' || $ke == 'curosmod_id' || $ke == 'userid' || $ke == 'value') {
  		 			continue;
  		} 		
      $sheet->setCellValueByColumnAndRow($td,$tr, $valu);
      $td++;
  	}
  	$tr++;
  }
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