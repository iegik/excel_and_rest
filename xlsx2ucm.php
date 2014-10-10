<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

// Ph9glINo
// Include PHPExcel_IOFactory
require_once dirname(__FILE__) . './Classes/PHPExcel/IOFactory.php';
// Include SOAP
require_once dirname(__FILE__) . './Classes/soa_ucm.php';
require_once dirname(__FILE__) . './config.php';

$filename = '';
$env = '';
foreach ($argv as $key=>$value){
	if(in_array($value,array('dev','test','prod'))!= NULL)
		$env = $value;
		
	preg_match('/xlsx/',$value,$matches, PREG_OFFSET_CAPTURE);
	if(count($matches))
		$filename = $value;
};

if($env == '' || $filename == '')exit("err \n");
$ucm_config = $config[$env];

if (!file_exists($filename)) {
	exit("Canot open the file '".$filename."'." . EOL);
}

// Get rows
// XLSX
$objPHPExcel = PHPExcel_IOFactory::load($filename);
// CSV
//$objReader = new PHPExcel_Reader_CSV();
//$objReader->setActiveSheetIndex(0);
//$objReader->setInputEncoding('CP1257');
//$objReader->setDelimiter(';');
//$objReader->setEnclosure('');
//$objReader->setLineEnding("\r\n");
//$objReader->setSheetIndex(0);

//$objPHPExcel = $objReader->load($filename);
$sheet = $objPHPExcel->getActiveSheet();
$c = 0;
$r = 1;
$header = Array();
$duplicates = Array();
// Rows
while($sheet->getCellByColumnAndRow(0, $r)->getValue()){
  if($r==1){
		while($caption = $sheet->getCellByColumnAndRow($c, $r)->getValue()){$header[] = $caption;$c++;}
		$columns = $c-1;
	}else{
		// Prepeare request
		$ucm = new SoapClient(
			// FIXME: Authorization problem
	  	//'http'.($ucm_config['ssl']?'s':'').'://'.$ucm_config['login'].':'.$ucm_config['password'].'@'.$ucm_config['host'].':'.$ucm_config['port'].$ucm_config['wsdl']['CheckIn'],
			//'wsdl/test/CheckIn.wsdl',
			$ucm_config['wsdlfile']['CheckIn'],
			array(
				'classmap' => array(
					'CheckInUniversal' => "UCMDocument"
				),
				'login' => $ucm_config['login'],
				'password' => $ucm_config['password'],
			)
		);
		// Map column values
		for($c=0;$c<=$columns;$c++){
			$data[$header[$c]] = $sheet->getCellByColumnAndRow($c, $r)->getValue();
		}
		if(!@$data[$header[1]])break;

		// Checkin
		if(!@$duplicates[$data[$header[$columns]]]){
			$data['path'] = dirname($filename);
			// Send to ucm
			echo "[".date(DATE_ATOM)."] CheckIn a document...";
        try { 
            $response = $ucm->CheckInUniversal(mapping($data));
            if(@$response->CheckInUniversalResult->StatusInfo->statusCode >= 0){
                echo "done:".$response->CheckInUniversalResult->StatusInfo->statusMessage."\n";
							// Change Rows
							$duplicates[$data[$header[$columns]]]='http'.($ucm_config['ssl']?'s':'').'://'.$ucm_config['host'].':'.$ucm_config['port'].$ucm_config['url']['GetFileById'].$response->CheckInUniversalResult->dID;
							//$sheet->setCellValueByColumnAndRow($c, $r,$ucm_config['url']."?IdcService=DOC_INFO&coreContentOnly=1&dID=".$response->CheckInUniversalResult->dID);
            }else{
                throw new SoapFault("Server",$response->CheckInUniversalResult->StatusInfo->statusMessage);
            }
        } catch (SoapFault $ex) { 
            echo "FAIL:".@$ex->faultstring."\n";
            //var_dump($ex->faultcode, $ex->faultstring, @$ex->faultactor, @$ex->detail, @$ex->faultname, @$ex->headerfault);
        }
		}
		$sheet->setCellValueByColumnAndRow($c-1, $r,@$duplicates[$data[$header[$columns]]]);
	//break;
	}
	$r++;
};

// CSV
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
//$objWriter->setInputEncoding('CP1257');
$objWriter->setUseBOM(true);
$objWriter->setDelimiter(';');
$objWriter->setEnclosure('');
$objWriter->setLineEnding("\r\n");
$objWriter->save(preg_replace('/\.xlsx/','.csv',$filename));

// XLSX
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save($filename.microtime(1).'.xlsx'); //FIXME: Because, I`m not shure, that sending all files tu ucm is success - better create another xlsx file, then replacing origenal one.

echo "[".date(DATE_ATOM)."] File has been created ", $filename , EOL;
