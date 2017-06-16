<?php

include 'Classes/PHPExcel.php';
require_once("../../config.php");
require_once('locallib.php');
global $DB, $USER;
$phpexcel = new PHPExcel();

$sheet = $phpexcel->getActiveSheet()->setTitle('reporte completo'); //Setting index when creating
$phpexcel->setActiveSheetIndex(0);

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






$sheet->setCellValueByColumnAndRow(5,5, 'Ejercicio Virtual');
$sheet->setCellValueByColumnAndRow(8,5, 'Continuidad del negocio');



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