<?php
mb_internal_encoding("UTF8");
session_start();
if(strtoupper($_POST['chars']) === strtoupper($_SESSION['captcha_code'])){
	echo "あたりー";
}
?>
<img src="sample.php" />
<form method="post" action="<?php $PHP_SELF; ?>">
  <input type="text" id="first" name="chars" value="" /> 
  <input type="submit" name="submit_send" value="送信する" /> 
</form> 
<script>
window.onload = function(){
	document.getElementById('first').focus();
};
</script>
