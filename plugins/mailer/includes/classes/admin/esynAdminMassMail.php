<?php

class AdminMassMail extends esynAdmin
{
	var $mailQueue	= array();
	var $mId		= 0;

	function addToQueue($user)
	{
		$this->mailQueue[] = $user;
	}

	function apply()
	{
		global $esynAdmin;
		if (empty($this->mailQueue)) return true;
		
		foreach($this->mailQueue as $u)
		{
			$q = $query = '';
			$query = "INSERT INTO ".$esynAdmin->mPrefix."mail_queue (email, message_id, date, variables) values %s";
			$ser = serialize($u);
			$q = "('".$u['email']."','".$this->mId."', NOW(),'".$esynAdmin->escape_sql($ser)."')";
			$query = sprintf($query,$q);
			$esynAdmin->query($query);
		}
        return false;
	}
}
