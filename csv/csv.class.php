<?php
/**
 * きっとCSV操作クラス
 * -----------------------------------------------------------------
 * CSV操作面倒なのでライブラリ化してみた。
 * 主な目的はエクセルの吐き出す変なCSVを対応する為にした。
 * プロパティを変える事で様々なフォーマットに対応できるようにした。
 * -----------------------------------------------------------------
 * @author	Hajime MATSUMOTO<mail@hazime.org>
 * @date		20110307
 * -----------------------------------------------------------------
 * <code>
 * <?php
 * require_once dirname(__FILE__) ."/../csv.class.php";
 * $csv = new KtCsv( );
 * $csv->setLineTerminatedBy("\n");
 * $csv->setFieldTerminatedBy(",");
 * $csv->setFieldEnclosedBy("\"");
 * $csv->setFieldEscapedBy("\\");
 * function Output( $recode ){
 *  if(count($recode) > 9 && !empty($recode[0])){
 * 	 vprintf("%1\$s\t%9\$s\n", $recode);
 *  }
 * }
 * $csv->setOutPutHandler( 'Output' );
 * $csv->parse($file);
 * ?>
 * </code>
 */
class KtCSV {

	/**
	 * 書式に関係する
	 */
	private $LS = "\n"; // ラインセパレータ
	private $EC = "\""; // エンクローズド
	private $EB = "\\"; // エスケープドバイ
	private $FS = ","; // フィールドセパレータ
	private $OutPutFormat = false;
	private $OutPutHandler = false;


	public function __construct( ){

	}
	public function setLineTerminatedBy( $char ){
		$this->LS = $char;
	}
	public function setFieldTerminatedBy( $char ){
		$this->FS = $char;
	}
	public function setFieldEnclosedBy( $char ){
		$this->EC = $char;
	}
	public function setFieldEscapedBy( $char ){
		$this->EB = $char;
	}
	public function setOutPutFormat( $string ){
		$this->OutPutFormat = $string;
	}

	public function setOutPutHandler( $func ){
		$this->OutPutHandler = $func;
	}
	public function parse( $file ){
		$fp = fopen($file, "r");
		return $this->parseFilePointer( $file );
	}

	public function parseFilePointer( $fp ){
		while($recode = $this->getLine($fp)){
			call_user_func( $this->OutPutHandler, $recode );
		}

		fclose($fp);
	}


	private function getLine( $fp ){
		// 一文字っつ解析する
		$buff = "";
		$recode = array();
		$i = 0; // フィールドインデックス
		$enc = false;
		$esc = false;

		while( false !== ($c = fgetc($fp))){

			if(	$c === $this->EB ){
				$esc = true;
				continue;
			}
			if( $esc === false && $c === $this->EC){
				$enc = !$enc;
				continue;
			}

			if( $esc === false && $enc === false ){
				if( $c === $this->LS ){ // 行末
					$recode[$i++] = $buff;
					$buff = "";
					return $recode;
				}

				if( $c === $this->FS ){ // 区切り文字
					$recode[$i++] = $buff;
					$buff = "";
					continue;
				}
			}

			// バッファする
			$buff .= $c;
			if( $esc === true ) $esc = false;
		}

		return $c;
	}
}
?>
