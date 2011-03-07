<?php
require_once dirname(__FILE__) ."/../csv.class.php";

/*
$file = dirname(__FILE__) . "/data/normal.csv";
$csv = new KtCsv( );
$csv->parse($file);

$file = dirname(__FILE__) . "/data/out_setting05.csv";
$csv = new KtCsv( );
$csv->setLineTerminatedBy(";");
$csv->setFieldTerminatedBy("\n");
$csv->setFieldEnclosedBy("\"");
$csv->setFieldEscapedBy("\\");
$csv->parse($file);
*/
$file = dirname(__FILE__) . "/data/out_setting05.csv";
$csv = new KtCsv( );
$csv->setLineTerminatedBy("\n");
$csv->setFieldTerminatedBy(",");
$csv->setFieldEnclosedBy("\"");
$csv->setFieldEscapedBy("\\");
//$csv->setOutPutFormat("%1\$s\t%14\$s\n");
//$csv->setOutPutFormat("%1\$s\t%14\$s\t%15\$s\n");


function Output( $recode ){
	if(count($recode) > 9 && !empty($recode[0])){
		vprintf("%1\$s\t%9\$s\n", $recode);
	}
}
$csv->setOutPutHandler( 'Output' );
$csv->parse($file);
//$csv->parseByFp($file);
?>
