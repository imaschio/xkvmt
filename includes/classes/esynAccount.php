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

class esynAccount extends eSyndiCat
{

	var $mTable = 'accounts';

	public function getInfo($aAccount)
	{
		$sql = "SELECT `accounts`.*, `plans`.`num_allowed_listings`, `plans`.`mark_as`, `plans`.`period`, ";
		$sql .= "(SELECT COUNT(*) FROM `" . $this->mPrefix . "listings` `listings` ";
		$sql .= "WHERE `listings`.`account_id` = `accounts`.`id`) `num_listings` ";
		$sql .= "FROM `" . $this->mTable . "` `accounts` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}plans` `plans` ON `plans`.`id` = `accounts`.`plan_id` ";
		$sql .= "WHERE `accounts`.`id` = '" . $aAccount . "' ";
		$sql .= "AND `accounts`.`status` = 'active' ";
		$sql .= "GROUP BY `accounts`.`id` ";
		$sql .= "LIMIT 0, 1";

		return $this->getRow($sql);
	}

	public function changePassword($aId, $aPassword)
	{
		$x = $this->update(array("password" => md5($aPassword)), "`id` = '" . $aId . "'");
		$aAccount = $this->row("*", "`id`='" . $aId . "'");

		$aAccount['pwd'] = $aPassword;
		$this->mMailer->AddAddress($aAccount['email']);
		$this->mMailer->Send('account_change_password', $aAccount);

		return $x;
	}

	public function setNewPassword($account)
	{
		$pass = $this->createPassword();

		$update = array(
			'password' => md5($pass),
			'sec_key' => '',
		);

		$r = $this->update($update, '`id` = :id', array('id' => $account['id']));

		/** sends notification email to account **/
		$account['pwd'] = $pass;
		$this->mMailer->AddAddress($account['email']);
		$this->mMailer->Send('account_change_password', $account);

		return $r;
	}

	public function registerAccount($aAccount)
	{
		$account = array();
		$addit = array();

		if ($aAccount['auto_generate'])
		{
			$password = $this->createPassword();
		}
		else
		{
			$password = $aAccount['password'];
		}

		$sec_key = md5($this->createPassword());
		$md5_password = md5($password);

		$account['username']	= $aAccount['username'];
		$account['email']		= $aAccount['email'];
		$account['password']	= $md5_password;
		$account['sec_key']		= $sec_key;
		$account['status']		= isset($aAccount['status']) ? $aAccount['status'] : 'unconfirmed';

		if (isset($aAccount['plan_id']))
		{
			$account['plan_id'] = (int)$aAccount['plan_id'];
			$addit['sponsored_expire_date'] = "CURRENT_DATE + INTERVAL {$aAccount['plan_period']} DAY";
			$account['sponsored'] = '1';
			$addit['sponsored_start'] = "NOW()";
		}

		$addit['date_reg'] = "NOW()";

		parent::insert($account, $addit);

		$id = mysql_insert_id();

		$account['pwd'] = $password;

		$this->mMailer->AddAddress($aAccount['email']);
		$this->mMailer->Send('account_register', $account);

		// get listings with the same email and assign them for account
		$this->setTable('listings');
		$listings = $this->keyvalue('`id`, `account_id`', "`account_id` = 0 AND `email` = '{$account['email']}'");
		if ($listings)
		{
			$this->update(array('account_id' => $id), $this->convertIds('id', array_keys($listings)));
		}
		$this->resetTable();

		return $id;
	}

	public function createPassword( $length = 7)
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		$pass = '';
		srand((double)microtime()*1000000);

		for ($i = 0; $i < $length; $i++)
		{
			$num = rand() % 33;
			$pass .= $chars[$num];
		}
		return $pass;
	}

	public function resendEmail($aAccount)
	{
		$aAccount['pwd'] = $aAccount['password'];

		$this->mMailer->AddAddress($aAccount['email']);
		$this->mMailer->Send('account_register', $aAccount);
	}

	public function updateAccount($account)
	{
		if (isset($account['plan_id']))
		{
			$account['plan_id'] = (int)$account['plan_id'];
			$addit['sponsored_expire_date'] = "CURRENT_DATE + INTERVAL {$account['plan_period']} DAY";
			$account['sponsored'] = '1';
			$addit['sponsored_start'] = "NOW()";
		}
		unset($account['plan_period'], $account['plan_cost']);

		$this->update($account, "`id` = :id", array('id' => (int)$account['id']), $addit);
	}

	public function confirmEmail($account, $action)
	{
		$account['sec_key'] = md5($this->createPassword());
		$account['account_id'] = $account['id'];

		// update current account to set salt
		$update['sec_key'] = $account['sec_key'];
		if ('account_confirm_change_email' == $action)
		{
			$update['nemail'] = $account['nemail'];
		}
		$update['id'] = $account['id'];
		$this->updateAccount($update);

		$this->mMailer->AddAddress($account['email']);
		$this->mMailer->Send($action, $account);
	}

	public function setNewAccountEmail($account)
	{
		$account['email'] = $account['nemail'];

		$update = array(
			'sec_key' => '',
			'nemail' => '',
			'email' => $account['email'],
		);

		$this->update($update, '`id` = :id', array('id' => (int)$account['id']));

		// sends notification email to the previous account email
		$this->mMailer->AddAddress($account['email']);

		$account['account_new_email'] = $account['email'];

		return $this->mMailer->Send('account_change_email', $account);
	}

	public function deleteAccount($id)
	{
		$id = (int)$id;

		$this->setTable('accounts');
		$account = $this->row('*', "`id` = '{$id}'");
		$this->resetTable();

		if (!$account)
		{
			return false;
		}

		// reset account id for categories
		$this->setTable('categories');
		$this->update(array('account_id' => 0), "`account_id` = '{$account['id']}'");
		$this->resetTable();

		// reset account id for listings
		$this->setTable('listings');
		$this->update(array('account_id' => 0), "`account_id` = '{$account['id']}'");
		$this->resetTable();

		// remove account
		$this->setTable('accounts');
		$this->delete("`id` = '{$account['id']}'");
		$this->resetTable();

		// remove cookie
		setcookie('account_id',	'', time() - 3600);
		setcookie('account_pwd', '', time() - 3600);

		return true;
	}

	public function postPayment($item, $plan, $invoice, $transaction)
	{
		$account = array();

		$account['transaction_id'] = $transaction['id'];
		$account['status'] = ('passed' == $transaction['status']) ? 'active' : 'approval';

		parent::update($account, "`id` = '{$invoice['item_id']}'");

		$this->mMailer->add_replace(array('account_plan' => $plan['title']));

		$this->mMailer->notifyAdmins('account_payment', $invoice['item_id']);
	}
}
