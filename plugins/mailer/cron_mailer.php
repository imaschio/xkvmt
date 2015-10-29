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

/** require necessary files **/
$eSyndiCat->setTable('mail_queue');	
$rs = $eSyndiCat->all('*','1 ORDER BY `date` LIMIT 100');
$eSyndiCat->resetTable();

if (!empty($rs))
{
	$mail = $eSyndiCat->mMailer;

	$message = $ids = array();
	$lastMessage = false;
	$bad = $sent = 0;
	foreach($rs as $r)
	{
		$ids[] = $r['id'];
		$u = unserialize($r['variables']);

		// i.e if this message_id already used do not get it second time
		if (empty($message) && $lastMessage!=$r['message_id'])
		{
			$eSyndiCat->setTable('messages');	
			$message = $eSyndiCat->row('*',"`id`='{$r['message_id']}'");
			$eSyndiCat->resetTable();
			$lastMessage = $r['message_id'];			
		}

		$from = $message['from'];
		$reply = $message['reply'];

        $mail->Subject = $message['subject'];
        $mail->From = $reply;
        $mail->FromName = $from;
        $mail->Body = $message;

        $mail->set_tags('%','%');
        $replace = array(
            "account_name" => ''
        );
        $mail->add_replace($replace);

		if (!$mail->Send())
		{
			$bad++;
		}
		else
		{
			$sent++;
		}
	}
	// Garbage collector
	// Delete all queued mails
	$ids = implode(",",$ids);
	if (!empty($ids)) {
		$eSyndiCat->setTable('mail_queue');	
		$eSyndiCat->delete("`id` IN({$ids})");
		$eSyndiCat->resetTable();
	}
	// Delete all messages that are already been sent (i.e that are NOT IN existing `message_id`s)
	$eSyndiCat->setTable('mail_queue');	
	$rs = $eSyndiCat->onefield("`message_id`");
	$eSyndiCat->resetTable();
	$rs = array_unique($rs);

	$ids = array();
	$ids = implode(",",$rs);
	if (!empty($ids))
	{
		$eSyndiCat->setTable('messages');	
		$eSyndiCat->delete("`id` NOT IN({$ids})");
		$eSyndiCat->resetTable();		
	}	
	// end garbage collector
}
?>
