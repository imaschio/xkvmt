<?php

function smarty_outputfilter_compress($source, &$smarty)
{
	require_once(IA_INCLUDES.'php_speedy'.IA_DS.'php_speedy.php');
	
	return $compressor->finish($source);
}

?>
