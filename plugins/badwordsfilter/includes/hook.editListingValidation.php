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

global $fields, $esynConfig, $esynI18N, $msg, $error;

if ($fields && $esynConfig->getConfig('bad_words_checking') && trim($esynConfig->getConfig('bad_words')) != '')
{
	$bad_words = explode(",", $esynConfig->getConfig('bad_words'));
	$words = array();

	foreach($fields as $key=>$value)
	{
		if (($value['type'] == 'text') || ($value['type'] == 'textarea'))
		{
			$field_name = $value['name'];

			foreach ($bad_words as $word)
			{
				$word = trim($word);
				if (preg_match("/\b{$word}\b/i", $_POST[$field_name]))
				{
					$words[] = $word;
				}
			}
		}
	}
	$bad_msg = $esynI18N['bad_words'];

	if ($words)
	{
		$error = true;
		$msg[] = $bad_msg . ': ' . implode(', ', array_unique($words));
	}
}