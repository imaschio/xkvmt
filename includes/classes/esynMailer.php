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

require_once IA_INCLUDES . 'phpmailer' . IA_DS . 'class.phpmailer.php';

class esynMailer extends PHPMailer
{
	var $replace = array(); // fill replace rules in the constructor
	var $mConfig = false;
	var $db = false;
	var $notif = array(); // notif action for admins
	var $start_tag = '';
	var $end_tag = '';

	function esynMailer()
	{
		$this->mConfig = esynConfig::instance();
		$config =& $this->mConfig->config;

		$this->FromName = $config['site'];
		$this->Sender = $config['site_email'];

		$this->Mailer = ('sendmail' == $config['mail_function'] || 'smtp' == $config['mail_function']) ? $config['mail_function'] : 'mail';
		$this->CharSet = $config['charset'];
		$this->isHTML(false !== strpos($config['mimetype'], 'html'));
		$this->From = $config['site_email'];
		$this->Sendmail = $config['sendmail_path'];
		$this->SingleTo = true;

		// PROPERTIES FOR SMTP
		$this->Host = $config['smtp_secure_connection']
			? strtolower($config['smtp_secure_connection']) . '://'
			: '';
		$this->Host .= $config['smtp_server'];
		$this->Host .= $config['smtp_port'] ? ':' . $config['smtp_port'] : '';
		$this->SMTPAuth = true;
		$this->Username = $config['smtp_user'];
		$this->Password = $config['smtp_password'];

		$this->reset_tags();

		$this->notif = array(
			'listing_submit',
			'account_register',
		);

		$this->dataBase = &$this->mConfig;
	}

	function set_tags($start_tag, $end_tag)
	{
		$this->start_tag = $start_tag;
		$this->end_tag = $end_tag;
	}

	function reset_tags()
	{
		$this->start_tag = isset($config['email_replace_start_tag']) ? $config['email_replace_start_tag'] : '{';
		$this->end_tag = isset($config['email_replace_end_tag']) ? $config['email_replace_end_tag'] : '}';
	}

	function add_replace($arr)
	{
		if (!is_array($arr))
		{
			return false;
		}

		foreach ($arr as $key => $value)
		{
			$this->replace[$this->start_tag . $key . $this->end_tag] = $value;
		}
	}

	function add_notif ($tpl)
	{
		if (!empty($tpl))
		{
			$this->notif[] = $tpl;
		}
	}
	/*
	 * Load email template
	 * Mail subject loads also
	 *
	 * @param string $aName template name
	 */
	function load_template($aName)
	{
		global $esynI18N;
		$this->Subject = $esynI18N['tpl_' . $aName . '_subject'];
		$this->Body = $esynI18N['tpl_' . $aName . '_body'];
	}

	function replace_tags($tplName = '', $account = array(), $listing = array())
	{
		global $esynLayout,$esynI18N;;

		if (!$esynLayout)
		{
			require_once(IA_CLASSES . 'esynLayout.php');

			$esynLayout = new esynLayout();
		}

		// replace templates
		$replace = array(
			'dir_url' => IA_URL,
			'dir_title' => $this->mConfig->config['site'],
			'dir_email' => $this->mConfig->config['site_email'],
		);
		$this->add_replace($replace);

		if ($listing)
		{
			if (!is_array($listing))
			{
				$this->dataBase->setTable('listings');
				$listing = $this->dataBase->row("*", '`id` = ' . (int)$listing);
				$this->dataBase->resetTable();
			}

			if (!isset($listing['path']))
			{
				$this->dataBase->setTable('categories');
				$category = $this->dataBase->row('*', "`id` = '{$listing['category_id']}'");
				$this->dataBase->resetTable();

				$replace = array(
					'category_title' => $category['title'],
					'category_path' => $esynLayout->printCategoryUrl(array('cat' => $category)),
				);
				$this->add_replace($replace);

				$listing['path'] = $category['path'];
			}

			//get crossed categories
			$this->dataBase->setTable('listing_categories');
			$cids = $this->dataBase->onefield('`category_id`', "`listing_id` = '". $listing['id'] ."'");
			$this->dataBase->resetTable();

			$crossed_categories = '';

			if ($cids && is_array($cids))
			{

				foreach ($cids as $key => $cid)
				{
					$this->dataBase->setTable('categories');
					$category = $this->dataBase->row('*', "`id` = '{$cid}'");
					$this->dataBase->resetTable();

					$crossed_categories .= '<a href="'. $esynLayout->printCategoryUrl(array('cat' => $category)) . '">' . $category['title'] . '</a>';

					if ($key + 1 < count($cids))
					{
						$crossed_categories .= '&nbsp;,&nbsp;';
					}

				}
			}
			else
			{
				$crossed_categories = '-//-';
			}

			$replace = array('crossed_categories' => $crossed_categories);
			$this->add_replace($replace);
		}

		if ($account)
		{
			if (!is_array($account))
			{
				$this->dataBase->setTable('accounts');
				$account = $this->dataBase->row('*', '`id` = ' . (int)$account);
				$this->dataBase->resetTable();
			}
		}

		if (is_array($listing) && !empty($listing))
		{
			$replace = array(
				'listing_id' => isset($listing['id']) ? $listing['id'] : '',
				'listing_title' => isset($listing['title']) ? $listing['title'] : '',
				'listing_url' => isset($listing['url']) ? $listing['url'] : '',
				'listing_email' => isset($listing['email']) ? $listing['email'] : '',
				'listing_status' => isset($listing['status']) ? $listing['status'] : '',
				'listing_rank' => isset($listing['rank']) ? $listing['rank'] : '',
				'listing_description' => isset($listing['description']) ? $listing['description'] : '',
				'listing_path' => $esynLayout->printListingUrl(array('listing' => $listing, 'details' => true)),
			);
		}
		else
		{
			$replace = array('listing_id' => 0);
		}

		$this->add_replace($replace);

		if (is_array($account) && !empty($account))
		{
			$replace = array(
				'account_id' => isset($account['id']) ? $account['id'] : '',
				'account_name' => isset($account['username']) ? $account['username'] : '',
				'account_name_url' => urlencode($account['username']),
				'account_key' => isset($account['sec_key']) ? $account['sec_key'] : '',
				'account_email' => isset($account['email']) ? $account['email'] : '',
				'account_new_email' => isset($account['account_new_email']) ? $account['account_new_email'] : '',
				'account_status' => isset($account['status']) ? $account['status'] : '',
			);

			if (isset($account['pwd']))
			{
				$replace['account_pwd'] = $account['pwd'];
			}
		}
		else
		{
			$replace = array('account_name' => $esynI18N['sir_madam']);
		}

		$this->add_replace($replace);

		$this->Body = str_replace(array_keys($this->replace), array_values($this->replace), $this->Body);
		$this->Subject = str_replace(array_keys($this->replace), array_values($this->replace), $this->Subject);
	}

	/*
	 * Send email immediately.
	 *
	 * @param string $tplName load specified email template, you can omit the parameter if you call load_template() before
	 * @param int $accId additional replacements will be available for template
	 * @param int $listId additional replacements will be available for template
	 */
	function Send($tplName = '', $account = array(), $listing = array())
	{
		if ($this->mConfig->gC($tplName))
		{
			if ($tplName)
			{
				$this->load_template($tplName);
			}

			$this->replace_tags($tplName, $account, $listing);

			$r = parent::Send();

			$this->ClearAddresses();
		}

		// notify admins
		if (in_array($tplName, $this->notif))
		{
			$this->notifyAdmins($tplName, $account, $listing);
		}

		return $r;
	}

	/*
	 * Send custom email immediately.
	 *
	 * @param string $content email content (subject & body)
	 * @param int $account additional replacements will be available for template
	 * @param int $listing additional replacements will be available for template
	 */
	function SendCustom($content, $account = array(), $listing = array())
	{
		$this->Subject = $content['subject'];
		$this->Body = $content['body'];
		$this->replace_tags('', $account, $listing);

		$r = parent::Send();

		$this->ClearAddresses();

		return $r;
	}

	/*
	 * Send email for all site admins
	 *
	 * @param string $tplName email template name
	 */
	function notifyAdmins($tplName, $account = 0, $listing = 0)
	{
		if ($this->mConfig->gC('notif_' . $tplName))
		{
			$where = '';

			if ('listing_submit' == $tplName)
			{
				$where = "AND `submit_notif` = '1'";
			}

			if ('account_register' == $tplName)
			{
				$where = "AND `account_registr_notif` = '1'";
			}

			if ('item_payment' == $tplName)
			{
				$where = "AND `payment_notif` = '1'";
			}

			$this->ClearAddresses();

			$this->dataBase->setTable('admins');
			$admins = $this->dataBase->all('`email`, `fullname`, `username`', "`status` = 'active' AND `email` != '' {$where}");
			$this->dataBase->resetTable();

			foreach ($admins as $admin)
			{
				$this->AddAddress($admin['email']);

				$this->load_template('notif_' . $tplName);

				$replace = array('admin_name' => empty($admin['fullname']) ? $admin['username'] : $admin['fullname']);
				$this->add_replace($replace);

				$this->replace_tags($tplName, $account, $listing);

				parent::Send();

				$this->ClearAddresses();
			}
		}
	}
}
