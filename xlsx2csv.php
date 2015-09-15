<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

/** PHPExcel_IOFactory */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'IOFactory.php';

$filename = '';
foreach ($argv as $key=>$value){
	preg_match('/xlsx/',$value,$matches, PREG_OFFSET_CAPTURE);
	if(count($matches))
		$filename = $value;
};

// Check prerequisites

if (!file_exists($filename)) {
    exit("File not found\n");
}

$objPHPExcel = PHPExcel_IOFactory::load($filename);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
/*
$objWriter->setInputEncoding('UTF8');
$objWriter->setUseBOM(false);
$objWriter->setDelimiter(',');
$objWriter->setEnclosure('"');
$objWriter->setLineEnding("\r\n");
*/
$objWriter->save(str_replace('.xlsx', '.csv',$filename));
?>