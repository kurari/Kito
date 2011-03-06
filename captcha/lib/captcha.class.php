<?php
/**
 * きっとCAPTCHAクラス
 * -----------------------------------------------------------------
 * ランダム文字列をロボットが読み取りにくい形で吐き出します。
 * 画像吐き出しを実行すると戻り値で吐き出した文字列が返るので、
 * セッションに格納するなどして後続の処理につなぎます。
 * -----------------------------------------------------------------
 * 依存関係
 * php >= 5.0
 * GDライブラリ
 * -----------------------------------------------------------------
 * @author	Hajime MATSUMOTO<mail@hazime.org>
 * @date		20110306
 * -----------------------------------------------------------------
 * <code>
 * <?php
 * require_once 'captcha.class.php';
 * session_start();
 * 
 * $captcha = new KtCaptcha(array(
 * 	'fonts/mplus-1c-black.ttf',
 * 	'fonts/mplus-1p-black.ttf',
 * 	'fonts/VeraBd.ttf',
 * 	'fonts/VeraIt.ttf',
 * 	'fonts/Vera.ttf'
 * ), 200, 60);
 * 
 * // PR文なし
 * $captcha->setPrText(false);
 * 
 * // 文字列を追加
 * $captcha->setChars("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
 * 
 * // 描画した文字列を取得
 * $code = $captcha->draw();
 * 
 * // 描画した文字列をセッションに格納
 * $_SESSION['captcha_code'] = $code;
 * ?>
 * </code>
 * <code>
 * <?php
 * mb_internal_encoding("UTF8");
 * session_start();
 * if(strtoupper($_POST['chars']) === strtoupper($_SESSION['captcha_code'])){
 * 	echo "あたりー";
 * }
 * ?>
 * <img src="sample2.php" />
 * <form method="post" action="<?php $PHP_SELF; ?>">
 *   <input type="text" id="first" name="chars" value="" /> 
 *   <input type="submit" name="submit_send" value="送信する" /> 
 * </form> 
 * <script>
 * window.onload = function(){
 * 	document.getElementById('first').focus();
 * };
 * </script>
 * </code>
 */
class KtCaptcha {

	private $fonts;
	private $charsNum = 5;
	private $prText = false;
	private $width = 200;
	private $height = 90;
	private $linesNum = 70; // 線引きすぎるとメモリが取れない
  private $minFontSize = 15;
	private $maxFontSize = 25;
	private $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // 文字列
	//private $chars = "あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをん";

	function __construct( $fonts, $width, $height ){
		foreach($fonts as $k=>$f ){
			if(substr($f,0,1) != "/"){
				$f = realpath(dirname(__FILE__) . '/../share/') .'/'. $f;
			}
			$this->fonts[$k] = $f;
		}
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * 広告文をセットすると、
	 * 文字列画像下部に表示します
	 *
	 * @param string 文字列
	 */
	function setPrText( $text ){
		$this->prText = $text;
	}

	/**
	 * 文字列ソース
	 *
	 * @param string 文字列
	 */
	function setChars( $text ) {
		$this->chars = $text;
	}

	/**
	 * 描画
	 *
	 * @return string
	 */
	function draw( ){
		$code = ""; 

		header("Content-type: image/jpeg");
		$img = imagecreate( $this->width, $this->height);
		imagecolorallocate($img, 255, 255, 255);

		if( $this->prText !== false ){
			$color = imagecolorallocate($img, 0, 0, 0);
			$lh = $this->height - imagefontheight(2) - 4;
			// 線を引く
			imageline($img, 0, $lh, $this->width, $lh, $color); 
			// PRを書き込む
			imageTTFText($img, 8, 0, 5, $this->height - imagefontheight(2) - 3 + 12, $color,  $this->fonts[0],  $this->prText);
			// 使用した文領域を小さくする
			$this->height = $this->height - imagefontheight(2) - 5;
		}

		// ランダム線を引く
		for ($i = 0; $i < $this->linesNum; $i++) {
			$res = rand(100, 250);
			$alot = imagecolorallocate($img, $res,$res,$res);
			imageline($img, rand(0, $this->width), rand(0, $this->height), rand(0, $this->width), rand(0, $this->height), $alot);
		}

		// コードを作成する
		$str = preg_split('/(?<!^)(?!$)/u',$this->chars);
		for($code="",$i=0;$i<$this->charsNum;$i++){
			$code.= $str[array_rand($str)];
		}

		$space = (int)($this->width / $this->charsNum);
		$i = 0;
		foreach(preg_split('/(?<!^)(?!$)/u',$code) as $c ){
			$font = $this->fonts[array_rand($this->fonts)];
			$color = rand(0, 100);
			$textAlot = imagecolorallocate($img, $color, $color, $color);
			$size = rand($this->minFontSize, $this->maxFontSize);
			$angle = rand(-30, 30);
			// フォントを取得
			$detail = imageftbbox($size, $angle, $font, $c, array());
            
			// 文字開始位置
			$x = $space / 4 + $i++ * $space;
			$cHeight = $detail[2] - $detail[5];
			$y = $this->height / 2 + $cHeight / 4; 
            
			// 書き出し
			imagefttext($img, $size, $angle, $x, $y, $textAlot, $font, $c, array());
		}
            
		imagejpeg($img);

		// メモリクリーン
		imagedestroy($img);
		return $code;
	}
}
?>
