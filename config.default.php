<?php
$config = array(
	'prod' => array(
		'login' => '',
		'password' => '',
		'url' => array(
			'GetFileById' => '/_dav/cs/idcplg?IdcService=GET_FILE&dID=',
		),
		'host' => 'ucm.energo.lv',
		'port' => '443',
		'ssl' => true,
		'wsdl' => array(
			'CheckIn' => '/_dav/cs/idcplg?IdcService=GET_SOAP_WSDL_FILE&wsdlName=CheckIn'
		),
		'wsdlfile' => array(
			'CheckIn' => 'wsdl/prod/CheckIn.wsdl'
		),
	),
	'test' => array(
		'login' => '',
		'password' => '',
		'url' => array(
			'GetFileById' => '/_dav/cs/idcplg?IdcService=GET_FILE&dID=',
		),
		'host' => 'testucm.energo.lv',
		'port' => '16201',
		'ssl' => true,
		'wsdl' => array(
			'CheckIn' => '/_dav/cs/idcplg?IdcService=GET_SOAP_WSDL_FILE&wsdlName=CheckIn'
		),
		'wsdlfile' => array(
			'CheckIn' => 'wsdl/test/CheckIn.wsdl'
		),
	),
);

function mapping($data,$request = array('CustomDocMetaData'=>array('property' => array()),'primaryFile'   => array('fileContent'   => ''))){
		switch ($data['Country']) {
				case 'Lithuania': 
					$request['dDocAccount'] = 'Elektrum_LT';
					$request['dSecurityGroup'] = 'LE_LT';
					break;
				case 'Estonia':
					$request['dDocAccount'] = 'Elektrum_EE';
					$request['dSecurityGroup'] = 'LE_EE';
					break;
				default :
					$request['dDocAccount'] = 'Elektrum_LV';
					$request['dSecurityGroup'] = 'LE_LV';
					break;
		}
		$data['Contact date/time'] = DateTime::createFromFormat('Y.m.d H:i:s',$data['Contact date/time']);
		$data['Offer exp. date'] = DateTime::createFromFormat('Y.m.d',$data['Offer exp. date']);
		return array_merge($request,array(
				'dDocTitle'     => html_entity_decode($data['First name'].' '.$data['Last name'].' / '.$data['Account number'].' / '.date_format($data['Contact date/time'],'d.m.Y')), // CustomerName / Account No / Contact Date
				'dDocType'      => "MASSPROLONGATION", // Required
				'CustomDocMetaData' =>       array (
        'property' => array_merge($request['CustomDocMetaData']['property'],array(
					array (
            'name' => 'xIdcProfile',
            'value' => 'sendigo',
          ),
          array (
            'name' => 'xCountry',
            'value' => $data['Country'],
          ),
          array (
            'name' => 'xChannelOut',
            'value' => 'email',
          ),
          array (
            'name' => 'xCustomerCode',
            'value' => $data['Customer Code'],
          ),
          array (
            'name' => 'xCustomerName',
            'value' => html_entity_decode($data['First name'].' '.$data['Last name']),
          ),
          array (
            'name' => 'xCustomerType',
            'value' => $data['Customer type'],
          ),
					array (
            'name' => 'xContactDate',
            'value' => date_format($data['Contact date/time'],'d.m.Y H:i:s'),
          ),
					array (
            'name' => 'xOfferExpirationDate',
            'value' => date_format($data['Offer exp. date'],'d.m.Y'),
          ),
					array (
            'name' => 'xCampaignId',
            'value' => $data['Campaign ID'],
          ),
					array (
            'name' => 'xAccountManagerName',
            'value' => html_entity_decode($data['Account manager name']),
          ),
				)),
      ),
			'primaryFile'   => array(
				'fileName'      => @$data['PDF document name'],
				'fileContent'   => file_get_contents($data['path'].'/'.@$data['PDF document name'])
			),
		));
}