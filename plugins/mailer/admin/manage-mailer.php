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

define('IA_REALM', "mass_mailer");

define("IA_ITEMS_PER_PAGE", 20);

$error = false;

// send first bunch of emails after this store remaining emails to the mail queue to be sent through other php script invoked via cron
define("IA_FIRST_BUNCH", 100);
defined("ITEMS_PER_PAGE") or define("ITEMS_PER_PAGE", 15);

// groups of users to be selected to send to
$usergroups = array(
	"all"			=> $esynI18N['_all_'],
	"broken"		=> $esynI18N['broken'],
	"norecip"		=> $esynI18N['nonrecip'],
	"recip"			=> $esynI18N['reciprocal'],	
	"sponsored"     => $esynI18N['sponsored'],
	"featured"		=> $esynI18N['featured'],
	"partner"		=> $esynI18N['partner'],
	"newsletters"	=> $esynI18N['newsletters']
);
$esynAdmin->setTable('accounts');
$accounts=$esynAdmin->all("*");
$esynAdmin->resetTable();
// statuses of the users
$statuses = explode(",","approval,banned,suspended,active");
$mimetype = $esynConfig->getConfig("mimetype");

$gTitle = $esynI18N['mass_mailer'];

/**
* Returns array with all necessary values by specified group (e.g sponsored, featured... or may be all)
*
* @param str $group
*
* @return arr
*/
function getUsersByGroup($group, $status)
{
	global $usergroups, $esynAdmin;

	if (!is_string($group) || empty($group) || !is_array($status) || empty($status))
	{
		return false;
	}

	// common string in the query
	$common = "DISTINCT email";
	$query = '';

	switch($group)
	{
		/*case "editors":
			$appr= in_array("approval", $status);
			$act	= in_array("active", $status);
			$s = '';
			if ($appr) {
				$s = " and status='approval'";
			}
			elseif ($act) {
				$s = " and status='active'";
			}
			else { // don't send anything and break. Because there is no status set
				break;
			}
			// editors 
			$query.="SELECT DISTINCT email, username as name FROM ".$esynAdmin->mPrefix."editors WHERE 1=1 $s";
			break;*/
		case "recip":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE `recip_valid`='1' AND `email` != ''";
			break;
		case "norecip":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE `recip_valid`='0' AND `email` != ''";
			break;			
		case "broken":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE `listing_header` NOT IN('200', '301','302') AND `email` != ''";
			break;			
		case "sponsored":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE sponsored='1' AND `email` != ''";
			break;
		case "featured":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE featured='1' AND `email` != ''";
			break;
		case "partner":
			$query="SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE partner='1' AND `email` != ''";
			break;
		case "newsletters":
			$query="SELECT * FROM ".$esynAdmin->mPrefix."newsletter WHERE `status` = 'active' AND `email` != ''";
			break;
		default: // all emails
			//$query.="(SELECT DISTINCT email FROM ".$esynAdmin->mPrefix."listings) UNION DISTINCT (SELECT DISTINCT email FROM ".$esynAdmin->mPrefix."editors)";
			$query="(SELECT $common FROM ".$esynAdmin->mPrefix."listings WHERE `email` != '') UNION DISTINCT (SELECT $common FROM ".$esynAdmin->mPrefix."newsletter WHERE `status` = 'active')";
	}

	$s = '';
	// limit by user statuses
	if ($group != 'editors' && $group != 'newsletters' && $group != 'all')
	{
		foreach($status as $st)
		{
			$s.="'".$st."',";
		}
		$s = rtrim($s,",");
		$s = " AND `status` IN($s)";
	}
	$query	.= $s;

	return $esynAdmin->getAll($query);
}

if (isset($_POST['add']))
{
	$msg = array();
	$esynAdmin->loadPluginClass("esynAdminMassMail", "mailer", '', true);
	$Mailer = new AdminMassMail;
	
	$fields['subject'] = esynSanitize::sql($_POST['subject']);
	$fields['message'] = esynSanitize::sql($_POST['message']);
	
	if (empty($fields['subject']))
	{
		$error = true;
		$msg[] = $esynI18N['subject_incorrect'];
	}
	
	if (esynValidator::isEmail($_POST['from']))
	{
		$fields['from'] = esynSanitize::sql($_POST['from']);
	}
	else
	{
		$error = true;
		$msg[] = $esynI18N['from_incorrect'];
	}

	// get specified statuses
	$status = array();
	if (is_array($_POST['statuses']))
	{
		foreach($_POST['statuses'] as $s)
		{
			if (in_array($s,$statuses))
			{
				$status[] = $s;
			}
		}
	}
	
	if (empty($status))
	{
		$error = true;
		$msg[] = $esynI18N['users_status_not_spec'];
	}

	// if reply field not explicitly specified just use `form` field value
	if (esynValidator::isEmail($_POST['reply']))
	{
		$fields['reply'] = esynSanitize::sql($_POST['reply']);
	}
	else
	{
		$fields['reply'] = $fields['from'];
	}

	// all emails
	$emails = array();
	
	// individual recipients (will be joined to the $emails)
	$individuals = array();
	
	$groups = array();

	// get users groups (sponsored, featured ... or may be all of them)
	if (!empty($_POST['usergroup']) && is_array($_POST['usergroup']))
	{
		foreach($_POST['usergroup'] as $v)
		{
			if (array_key_exists($v, $usergroups))
			{
				$groups[] = $v;
			}
		}
	}

	if (!empty($_POST['individual']) && is_string($_POST['individual']))
	{
		$x = preg_split("#\s+#", $_POST['individual']);
		$x = array_unique($x);

		foreach($x as $email)
		{
			$email = trim($email);
			if (esynValidator::isEmail($email))
			{
				$emails[] = $email;
			}
		}
	}

	// at this time $emails vairiable contains individual validated(only syntax) recipients

	// users array - will be filled with the users by group
	$users = array();
	if (isset($_POST['accunt']))
		{
			$user_accaunts = array();
			$esynAdmin->setTable('accounts');
			for($i=0;$i<count($_POST['accunt']);$i++)
			{
				if ($_POST['accunt'][$i]==0)
				{
					$user_accaunts = $esynAdmin->all("*");
					break;
				}
				else
				{
					$user_accaunts[] = $esynAdmin->row("*","`id`='{$_POST['accunt'][$i]}'");
				}
				
			}
			$esynAdmin->resetTable();
		}
	// if no actually recipients are set
	if (empty($groups) && empty($emails) && empty($user_accaunts))
	{
		$error	= true;
		$msg[]	= $esynI18N['no_receps'];
	}
	else
	{
		// if user selected `all` then there is no need to others since they included to the all set.....doh
		if (in_array("all",$groups))
		{
			$users = getUsersByGroup("all", $status);
		}
		else
		{
			foreach($groups as $g)
			{
				$users = array_merge($users, getUsersByGroup($g, $status));
			}
		}
	}

	// validating done - start processing
	if (!$error)
	{
		if ($esynConfig->getConfig('newsletter_unsubscribe_link'))
		{
			$unsub_link = IA_URL . 'controller.php?plugin=mailer';
			$unsubscribe_link = str_replace("{unsub_link}", $unsub_link ,$esynConfig->getConfig("newsletter_unsubscribe_link"));
		}
		// store the message
		$esynAdmin->setTable('messages');
		$msgId = $esynAdmin->insert(array("subject"=>$fields['subject'], "message"=>$fields['message']), array("date" => "NOW()"));
		$esynAdmin->resetTable();

		if ($msgId)
		{
			$Mailer->mId = $msgId;
            $mail = $esynAdmin->mMailer;

			// emails was escaped
			$reply = stripslashes($fields['reply']);
			$from = stripslashes($fields['from']);
			$subject = $_POST['subject'];
			$message = nl2br(($_POST['message']));

            $mail->set_tags('%','%');

			// all the individual must receive emails without storing them to the mail queue
			if (!empty($emails))
			{
                if (is_array($emails))
                {
	                $mail->Subject = $subject;
                    $mail->From = $reply;
                    $mail->FromName = $from;
                    $mail->Body = $message.$unsubscribe_link;

	                $replace = array(
		                "account_name" => ''
	                );
					$mail->add_replace($replace);

                    foreach ($emails as $value)
                    {
                        $mail->AddAddress($value);

                        if (!$mail->Send())
                        {
                            $error = true;
	                        $msg[] = str_replace("{email}", $value, $esynI18N['could_not_deliver']);
                            //$msg[] = $mail->ErrorInfo;
                        }

	                    $mail->ClearAddresses();
                    }
                }
			}
			// ...this variable will be used only for not sending duplicate email
			//$emails = array();
			if (!empty($user_accaunts))
			{
				$x=1;
				foreach ($user_accaunts as $i=>$u)
				{
					if ($x>IA_FIRST_BUNCH)
					{
						$u['email']=esynSanitize::sql($u['email']);
						$Mailer->addToQueue($u);
					}
					else
					{
                        if (empty($u['email']))
                        {
                            continue;
                        }

                        $mail->AddAddress($u['email']);
                        $mail->Subject = $subject;
                        $mail->From = $reply;
                        $mail->FromName = $from;
                        $mail->Body = $message.$unsubscribe_link;

                        $replace = array(
                            "account_name" => (isset($u['username']) ? $u['username'] : '')
                        );
                        $mail->add_replace($replace);

						if (!$mail->Send())
						{
							$error	= true;
							$msg[]	= str_replace("{email}", $u['email'], $esynI18N['could_not_deliver']);
							break;
						}

						$mail->ClearAddresses();

						$x++;		
					}
				}
				
			}
			if (!empty($users))
			{
				// indicator of FIRST_BUNCH whether to send or to store to the queue
				$x = 1;

				// send to the stored users
				foreach($users as $i=>$u)
				{
					if ($x > IA_FIRST_BUNCH)
					{
						// escaping is necessary as email such: email'example@domain.com considered as valid
						$u['email'] = esynSanitize::sql($u['email']);
						$Mailer->addToQueue($u);
					}
					else
					{
                        if (empty($u['email']))
                        {
                            continue;
                        }

                        $mail->AddAddress($u['email']);
                        $mail->Subject = $subject;
                        $mail->From = $reply;
                        $mail->FromName = $from;
                        $mail->Body = $message.$unsubscribe_link;

                        $replace = array(
                            "account_name" => (isset($u['username']) ? $u['username'] : '')
                        );
                        $mail->add_replace($replace);

						if (!$mail->Send())
						{
							$error	= true;
							$msg[]	= str_replace("{email}", $u['email'], $esynI18N['could_not_deliver']);
							break;
						}

						$mail->ClearAddresses();

						$x++;
					}
				}

				if ($x >  IA_FIRST_BUNCH)
				{
					// we've stored queued emails to the internal PHP
					// array and we must store those emails to the DB table
					// by invoking apply() method
					$Mailer->apply();
				}
			}

			if (empty($msg))
			{
				$msg[] = $esynI18N['email_message_sent'];
			}
		}
	}
	esynMessages::setMessage($msg, $error);	
}

if (isset($_GET['action']))
{


	$out = array('data' => '', 'total' => 0);
	
	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];
		
		$sql = "SELECT `mq`.*, `ms`.`subject` FROM `".$esynAdmin->mPrefix."mail_queue` `mq` 
				LEFT JOIN `".$esynAdmin->mPrefix."messages` `ms` ON `mq`.`message_id` = `ms`.`id` 
				ORDER BY `mq`.`date` LIMIT ".$start.",".$limit;

		$out['data'] = $esynAdmin->getAll($sql);
		$esynAdmin->setTable('mail_queue');
		$out['total'] = $esynAdmin->one("COUNT(*)", "1");
		$esynAdmin->resetTable();
	}
	
	if ('get_recip' == $_GET['action'])
	{
		$esynAdmin->factory("Category");

		$catId = (int)$_GET['id'];
	
		$recipients = array();
		if (isset($_GET['recipients']))
		{
			$recipients = explode(PHP_EOL,$_GET['recipients']);
			$recipients = array_unique($recipients);			
		}
	
		if (isset($_GET['textarea_action']))
		{
			$sql = "SELECT `id`, `email` ";
			$sql .= "FROM `".IA_DBPREFIX."listings` ";
			$sql .= "WHERE `category_id` = '{$catId}' ";
			$sql .= "AND `email` <> '' ";			
			$sql .= "GROUP BY `email` ";
	
			$out_array = array();
			$new_recipients = $esynCategory->getKeyValue($sql);
	
			if ('add_to_textarea' == $_GET['textarea_action'])
			{
				if (!empty($new_recipients))
				{				
					$out_array = $new_recipients + $recipients;				
				}
				else
				{
					$out_array = $recipients;
				}
			}
			elseif ('rem_fr_textarea' == $_GET['textarea_action'])
			{
				if (!empty($new_recipients))
				{
					$out_array = array_diff($recipients, $new_recipients);
				}
			}
			$out_array = array_unique($out_array);			
			echo join(PHP_EOL,$out_array);
			die();
		}
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}
$gNoBc		= false;

$gBc[1]['title'] = $esynI18N['mass_mailer'];
$gBc[1]['url'] = 'controller.php?plugin=mailer&amp;file=manage-mailer';

if (isset($_GET['do']) && $_GET['do'] == 'send')
{
	$gBc[2]['title'] = $esynI18N['send'];
	$gBc[2]['url'] = 'controller.php?plugin=mailer&amp;file=manage-mailer';
}

$actions[] = array("url" => "controller.php?plugin=mailer&amp;file=manage-mailer&amp;do=send", "icon" => "add.png", "label" => $esynI18N['send']);
$actions[] = array("url" => "controller.php?plugin=mailer", "icon" => "contract.png", "label" => $esynI18N['manage_newsletters']);
$actions[] = array("url" => "controller.php?plugin=mailer&amp;file=manage-mailer", "icon" => "view.png", "label" => $esynI18N['view_all']);

require_once( IA_ADMIN_HOME.'view.php');

if (isset($_GET['do']) && ($_GET['do'] == 'send'))
{

	$state = array();
	
	if (isset($_POST['subject']) && isset($_POST['from']) && isset($_POST['reply']) && isset($_POST['message']) && isset($_POST['individual']))
	{
		$state['subject']	= ($_POST['subject']);
		$state['from'] 		= ($_POST['from']);
		$state['reply']		= ($_POST['reply']);
		$state['message']	= ($_POST['message']);
		$state['individual']= ($_POST['individual']);
	}
	else
	{
		$state['individual'] = $state['message'] = $state['reply'] = $state['from'] = $state['subject']	= "";
	}
	$esynSmarty->assign('mimetype', $mimetype);
	$esynSmarty->assign('state', $state);
	$esynSmarty->assign('usergroups', $usergroups);
	$esynSmarty->assign('accounts', $accounts);
	$esynSmarty->assign('statuses', $statuses);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'manage_mailer.tpl');
