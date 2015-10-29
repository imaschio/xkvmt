<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.3.0
 *	 LISENSE: http://www.esyndicat.com/license.html
 *	 http://www.esyndicat.com/
 *
 *	 This program is a commercial software and any kind of using it must agree 
 *	 to eSyndiCat Directory Software license.
 *
 *	 Link to eSyndiCat.com may not be removed from the software pages without
 *	 permission of eSyndiCat respective owners. This copyright notice may not
 *	 be removed from source code in any case.
 *
 *	 Copyright 2007-2013 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$err = 0;
$oFile = $_FILES['upload'] ;

if (isset($_GET['Type']) && 'Image' == $_GET['Type'])
{
	$sErrorNumber = '0';

	$imgtypes = array(
		'image/gif'     => 'gif',
		'image/jpeg'    => 'jpg',
		'image/pjpeg'   => 'jpg',
		'image/png'     => 'png',
		'image/x-png'   => 'png',
	);

	$sFileUrl = 'uploads/';

	$ext = array_key_exists($oFile['type'], $imgtypes) ? $imgtypes[$oFile['type']] : false;

	if (!$ext)
	{
		$err = true;
		SendResults( '202' );
	}

	$tok = esynUtil::getNewToken();
	$fname = "{$tok}.{$ext}";

	if (!$err)
	{
		list($width, $height, $type, $attr) = getimagesize($oFile['tmp_name']);

		if ($width > 0 && $height > 0)
		{
			$eSyndiCat->loadClass("Image");

			$image = new esynImage();
			$image->processImage($oFile, IA_HOME . $sFileUrl, $fname, $width, $height, 1001);
		}
		else
		{
			move_uploaded_file($oFile['tmp_name'], IA_HOME . $sFileUrl . $fname);
		}
	}

	SendResults( $err, IA_URL . $sFileUrl . $fname, $fname ) ;
}

// This is the function that sends the results of the uploading process.
function SendResults( $errorNumber, $fileUrl = '', $fileName = '', $customMsg = '' )
{
	$callback = (int)$_GET['CKEditorFuncNum'];
	$output = '<html><body><script type="text/javascript">';
	$output .= "window.parent.CKEDITOR.tools.callFunction('$callback', '$fileUrl', '$customMsg');";
	$output .= '</script></body></html>';
	die($output);
}
