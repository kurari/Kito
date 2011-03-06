<?php
/**
 * きっとメールクラス
 * ------------------------------------------------------
 * 直接TCP/IPを話してメールを送信します
 *
 * ------------------------------------------------------
 * @author Hajime MATSUMOTO <mail@hazime.org>
 * @date 20110306
 * ------------------------------------------------------
 * <code>
 * mb_language('ja');
 * mb_internal_encoding('utf8');
 * $mailto="ueda@yupu.jp";
 * $subject="本文あり";
 * $content="こんにちは!";
 * 
 * $host = "ATSMAIL01";
 * $port = 25;
 * $ttl = 60;
 * 
 * $M = new KtMail( );
 * $M->setServer( $host, $port, $ttl );
 * $M->send( $mailto, array("hajime@avap.co.jp","創"), $subject, $content);
 * </code>
 */
class KtMail {
	private $host,$port,$eno,$estr,$ttl;

	function setServer( $host, $port, $ttl = 60){
		$this->host = $host;
		$this->port = $port;
		$this->ttl = $ttl;
	}

	function send( $to, $from, $title, $content ){
		$s = fsockopen( 
			$this->host, $this->port, $this->eno, $this->estr, $this->ttl
		);
		if( !$s ){
			echo "$this->estr\n";
			die();
		}

		$mailfrom="From:".mb_encode_mimeheader($from[1])."<{$from[0]}>";
		$mailsubj="Subject:".mb_encode_mimeheader($title);

		$log = array();
		fwrite($s, "HELO ATSMAIL01\n");
		$log[] = fread($s, 1000);
		fwrite($s, "MAIL FROM: {$from[0]}\n");
		$log[] = fread($s, 1000);
		fwrite($s, "RCPT TO: $to\n");
		$log[] = fread($s, 1000);
		fwrite($s, "DATA\n");
		$log[] = fread($s, 1000);
		fwrite($s, "MIME-version: 1.0\n");
		fwrite($s, "$mailfrom\n");
		fwrite($s, "$mailsubj\n");
		fwrite($s, "\n");
		fwrite($s, "$content\n");
		fwrite($s, ".\n");
		$log[] = fread($s, 1000);
		fwrite($s, "QUIT");
	}
}
?>
