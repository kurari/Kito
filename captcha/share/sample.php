<?php
require_once '../lib/captcha.class.php';
session_start();

$captcha = new KtCaptcha(array(
	'fonts/mplus-1c-black.ttf',
	'fonts/mplus-1p-black.ttf',
	'fonts/VeraBd.ttf',
	'fonts/VeraIt.ttf',
	'fonts/Vera.ttf'
), 200, 60);

// PR文なし
$captcha->setPrText("by hazime.org");

// 文字列を追加
//$captcha->setChars("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
$captcha->setChars("ABCDEFGHIJKLMNOPQRSTUVWXYZ");

// 描画した文字列を取得
$code = $captcha->draw();

// 描画した文字列をセッションに格納
$_SESSION['captcha_code'] = $code;
?>
