<?php

class UCMDocument {
    protected $self = array();

    public $dDocName;
    public $dDocTitle;
    public $dSecurityGroup = 'LE_EE';
    public $dDocAccount    = 'Elektrum_EE';
    public $dDocType;
    //'dInDate'=>date("d.m.y H:i"),
    //'doFileCopy'=>'1',
    //'dDocFormat'=>'image/png',
    public $CustomDocMetaData = array();
    public $primaryFile;
/*    
    public function __construct( $array ) {
        $property = array();
        foreach($array as $name => $value){
            $property[] = array(
                'name'  => $name,
                'value' => $value
            );
        }
        $this->CustomDocMetaData = $property;
    }*/
};

/*
try { 

    var_export($ucm->CheckInUniversal(array(
        'dDocName'      => '',
        'dDocTitle'     => ':)',
        'dSecurityGroup'=> 'LE_EE',
        'dDocAccount'   => 'Elektrum_EE',
        'dDocType'      => 'Document',
        //'dInDate'=>date("d.m.y H:i"),
        //'doFileCopy'=>'1',
        //'dDocFormat'=>'image/png',
        'primaryFile'=>array(
            'fileName'      => 'test.txt',
            'fileContent'   => 'cid:272374544482'
        )
    )));

} catch (SoapFault $ex) { 
    var_dump($ex->faultcode, $ex->faultstring, @$ex->faultactor, @$ex->detail, @$ex->faultname, @$ex->headerfault); 
}     
*/