<?php

require_once(dirname(__FILE__).IA_DS.'includes'.IA_DS.'kcaptcha'.IA_DS.'captcha.php');

$captcha = new KCAPTCHA();

$captcha->length = $esynConfig->getConfig('captcha_num_chars');

echo $captcha->getImage();

$_SESSION['pass'] = $captcha->getKeyString();

?>
