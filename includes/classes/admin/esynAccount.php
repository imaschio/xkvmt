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

/**
 * esynAccount
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynAccount extends eSyndiCat
{
	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = "accounts";

	/**
	 * insert
	 *
	 * Adds new account to database
	 *
	 * @param array $account account information
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return int the id of the newly created account
	 */
	function insert($account, $compat = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAccountInsert", $params);

		if (empty($account))
		{
			$this->message = 'Account parameter is empty';

			return false;
		}

		if (isset($account['old_name']))
		{
			unset($account['old_name']);
		}

		if (isset($account['password2']))
		{
			unset($account['password2']);
		}

		$account['password'] = md5($account['password']);

		$id = parent::insert($account, array('date_reg' => "NOW()"));

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAccountInsert", $params);

		return true;
	}

	/**
	 * update
	 *
	 * @param mixed $fields
	 * @param string $where
	 * @param array $addit
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return void
	 */
	function update($fields, $ids = '', $send_email = false, $compat = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAccountUpdate", $params);

		if (empty($fields))
		{
			$this->message = 'The Fields parameter is empty.';

			return false;
		}

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		$where = $this->convertIds('id', $ids);

		$accounts = $this->all("*", $where);

		if (empty($accounts))
		{
			return false;
		}

		if (isset($fields['status']))
		{
			switch($fields['status'])
			{
				case "active":
					foreach ($accounts as $account)
					{
						if ('unconfirmed' == $account['status'])
						{
							parent::update(array("sec_key" => ''), "`id` = '{$account['id']}'");
						}

						if (!empty($account['email']) && $send_email)
						{
							$this->mMailer->AddAddress($account['email']);
							$this->mMailer->Send('account_approved', $account);
						}
					}
					break;
				case "approval":
					foreach ($accounts as $account)
					{
						if (!empty($account['email']) && $send_email)
						{
							$this->mMailer->AddAddress($account['email']);
							$this->mMailer->Send('account_disapproved', $account);
						}
					}
					break;
				case "banned":
					foreach ($accounts as $account)
					{
						if (!empty($account['email']) && $send_email)
						{
							$this->mMailer->AddAddress($account['email']);
							$this->mMailer->Send('account_banned', $account);
						}
					}
					break;
			}
		}

		if (isset($fields['password']))
		{
			$fields['password'] = md5($fields['password']);
		}

		if (empty($where))
		{
			$where = $this->convertIds('id', $ids);
		}

		$id = parent::update($fields, $where, array('date_reg' => "NOW()"));

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAccountUpdate", $params);

		return true;
	}

	/**
	 * delete
	 *
	 * Remove account
	 *
	 * @param string $where
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @param bool $compat2 - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return void
	 */
	function delete($ids = '', $compat = false, $compat2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAccountDelete", $params);

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		$where = $this->convertIds('id', $ids);

		if (!empty($where))
		{
			$accounts = $this->all("*", $where);

			if ($accounts)
			{
				foreach($accounts as $account)
				{
					if ($account['email'])
					{
						$this->mMailer->AddAddress($account['email']);
						$this->mMailer->Send("account_deleted", $account);
					}
				}
			}
		}

		if (empty($where))
		{
			$where = $this->convertIds('id', $ids);
		}

		parent::delete($where);

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAccountDelete", $params);

		return true;
	}

	/**
	 * getInfo
	 *
	 * Returns account information by id
	 *
	 * @param int $aAccount account id
	 * @access public
	 * @return array
	 */
	function getInfo($aAccount)
	{
		$sql = "SELECT t1.*, COUNT(t2.`id`) listings ";
		$sql .= "FROM `{$this->mTable}` t1 ";
		$sql .= "LEFT JOIN `{$this->mPrefix}listings` t2 ";
		$sql .= "ON t1.`id` = t2.`account_id` ";
		$sql .= "WHERE t1.`id` = '{$aAccount}' ";
		$sql .= "AND t1.`status` = 'active' ";
		$sql .= "GROUP BY t1.`id` ";
		$sql .= "LIMIT 0,1";

		return $this->getRow($sql);
	}

	/**
	 * getAccountsByStatus
	 *
	 * Returns accounts by status
	 *
	 * @param string $aStatus account status
	 * @param int $aStart starting position
	 * @param int $aLimit number of accounts to be returned
	 * @access public
	 * @return void array
	 */
	function getAccountsByStatus($aStatus = '', $aStart = 0, $aLimit = 0)
	{
		$sql = "SELECT *";
		$sql .= "FROM `".$this->mTable."` ";
		$sql .= $aStatus ? "WHERE `status` = '{$aStatus}'" : '';
		$sql .= "ORDER BY `date_reg` DESC ";
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit} " : ' ';

		return $this->getAll($sql);
	}

	/**
	 * getNumSearch
	 *
	 * Returns number of accounts
	 *
	 * @param string $aUsername account username
	 * @param string $aEmail account email
	 * @access public
	 * @return int
	 */
	function getNumSearch($aUsername = '', $aEmail = '')
	{
		$sql = "SELECT COUNT(`id`) ";
		$sql .= "FROM `".$this->mTable."` ";
		$sql .= $aUsername ? "WHERE `username` = '{$aUsername}'" : '';
		if ($aEmail)
		{
			$sql .= $aUsername ? " AND  " : " WHERE ";
			$sql .= " `email` LIKE '%{$aEmail}%' ";
		}

		return $this->getOne($sql);
	}

	/**
	 * getSearch
	 *
	 * Returns accounts by username or email
	 *
	 * @param string $aUsername account username
	 * @param string $aEmail account email
	 * @param int $aStart starting position
	 * @param int $aLimit number of accounts to be returned
	 * @access public
	 * @return array
	 */
	function getSearch($aUsername = '', $aEmail = '', $aStart = 0, $aLimit = 0)
	{
		$sql = "SELECT * ";
		$sql .= "FROM `".$this->mTable."` ";
		$sql .= $aUsername ? "WHERE `username` = '{$aUsername}'" : '';
		if ($aEmail)
		{
			$sql .= $aUsername ? " AND " : " WHERE ";
			$sql .= " `email` LIKE '%{$aEmail}%' ";
		}
		$sql .= $aStart ? "LIMIT {$aStart}, {$aLimit}" : '';

		return $this->getAll($sql);
	}

	/**
	 * createPassword
	 *
	 * Generate random password
	 *
	 * @param int $length (optional) length of password
	 * @access public
	 * @return void
	 */
	function createPassword($length = 7)
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

	/**
	 * registerAccount
	 *
	 * The function creates new record in the database
	 * and sends confirmation email
	 *
	 * @param arr $aAccount user's data
	 * @access public
	 * @return int newly created account id
	 */
	function registerAccount($aAccount, $aListing)
	{
		$account = array();

		$password		= $this->createPassword();
		$sec_key		= md5($this->createPassword());
		$md5_password	= md5($password);

		$account['username']	= $aAccount['username'];
		$account['email']		= $aAccount['email'];
		$account['password']	= $md5_password;
		$account['sec_key']		= $sec_key;
		$account['status']		= isset($aAccount['status']) ? $aAccount['status'] : 'unconfirmed';

		parent::insert($account, array('date_reg' => 'NOW()'));

		$id = mysql_insert_id();

		$account['id'] = $id;
		$account['pwd'] = $password;

		$this->mMailer->AddAddress($aAccount['email']);
		$this->mMailer->Send('account_admin_register', $account, $aListing);

		return $id;
	}

	/**
	 * resendEmail
	 *
	 * Resend email to account
	 *
	 * @param mixed $aAccount account information
	 * @access public
	 * @return void
	 */
	function resendEmail($aAccount)
	{
		$aAccount['pwd'] = $aAccount['password'];

		$this->mMailer->AddAddress($aAccount['email']);
		$this->mMailer->Send('account_register', $aAccount);
	}
}
