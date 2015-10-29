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

define('IA_REALM', "transactions");

esynUtil::checkAccess();

if (isset($_GET['action']))
{

	$out = array('data' => '', 'total' => 0);

	if (in_array($_GET['action'], array('listing', 'account')))
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$query = esynSanitize::sql(trim($_GET['query']));

		if ('listing' == $_GET['action'])
		{
			$esynAdmin->factory("Listing");

			$out['data'] = $esynListing->getSearch($query, 1, $start, $limit);
		}

		if ('account' == $_GET['action'])
		{
			$sql = "SELECT `a`.`id`, `a`.`username` `title`, `a`.`email` `description` "
				 . "FROM `{$esynAdmin->mPrefix}accounts` `a` "
				 . "WHERE `a`.`username` LIKE '%{$query}%' "
				 . "OR `a`.`email` LIKE '%{$query}%'";

			$out['data'] = $esynAdmin->getAll($sql);
		}
	}

	if ('getplans' == $_GET['action'])
	{
		$where = '';

		if (isset($_POST['type']))
		{
			if (in_array($_POST['type'], array('listing', 'account')))
			{
				$where = "`item` = '{$_POST['type']}'";
			}
		}

		$esynAdmin->setTable('plans');
		$plans = $esynAdmin->all('*', $where);
		$esynAdmin->resetTable();

		if ($plans)
		{
			foreach($plans as $key => $plan)
			{
				$out['data'][] = array('display' => $plan['title'], 'value' => $plan['id']);
			}
		}
	}

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		$where = $order = '';

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir} ";
		}

		$values = array();

		if (isset($_GET['email']) && !empty($_GET['email']))
		{
			$conditions[] = "`transactions`.`email` = :email";
			$values['email'] = $_GET['email'];
		}

		if (isset($_GET['order']) && !empty($_GET['order']))
		{
			$conditions[] = "`transactions`.`order_number` = :order";
			$values['order'] = $_GET['order'];
		}

		$condition = isset($_GET['condition']) && in_array($_GET['condition'], array('OR', 'AND')) ? $_GET['condition'] : 'OR';

		if (!empty($condition) && !empty($conditions))
		{
			$where = implode(' '. $condition .' ', $conditions);
		}

		$esynAdmin->setTable('transactions');
		$items = $esynAdmin->all("DISTINCT `item`");
		$esynAdmin->resetTable();

		if ($items)
		{
			$sqls = array();

			foreach ($items as $item)
			{
				$fields = $fields_q = array();
				$join = array();

				if ("listing" == $item['item'] || "account" == $item['item'])
				{
					$item_table = $item['item'] . 's';
					$join[$item_table] = 'ON `t`.`item_id` = `' . $item_table . '`.`id`';
					$join['plans'] = 'ON `t`.`plan_id` = `plans`.`id`';

					if ("listing" == $item['item'])
					{
						$join['accounts'] = 'ON `t`.`account_id` = `accounts`.`id`';
					}

					$fields['username'] = '`accounts`.`username`';
					$fields['itm_id'] = '`' . $item_table . '`.`id`';
					$fields['pln_title'] = '`plans`.`title`';
				}
				else
				{
					$join['accounts'] = 'ON `t`.`account_id` = `accounts`.`id`';
					$fields['username'] = '`accounts`.`username`';
				}

				$esynAdmin->startHook('phpAdminTransactionsQuery');

				$fields_q['username'] = isset($fields['username']) ? $fields['username'] : '0';
				$fields_q['account_id'] = isset($fields['account_id']) ? $fields['account_id'] : '`t`.`account_id`';
				$fields_q['item'] = isset($fields['item']) ? $fields['item'] : '`t`.`item`';
				$fields_q['email'] = isset($fields['email']) ? $fields['email'] : '`t`.`email`';
				$fields_q['itm_id'] = isset($fields['itm_id']) ? $fields['itm_id'] : 'null';
				$fields_q['item_id'] = isset($fields['item_id']) ? $fields['item_id'] : '`t`.`item_id`';
				$fields_q['plan_id'] = isset($fields['plan_id']) ? $fields['plan_id'] : '`t`.`plan_id`';
				$fields_q['pln_title'] = isset($fields['pln_title']) ? $fields['pln_title'] : 'null';
				$fields_q['plan_title'] = isset($fields['plan_title']) ? $fields['plan_title'] : '`t`.`plan_title`';
				$fields_q['status'] = isset($fields['status']) ? $fields['status'] : '`t`.`status`';
				$fields_q['total'] = isset($fields['total']) ? $fields['total'] : '`t`.`total`';
				$fields_q['order'] = isset($fields['order']) ? $fields['order'] : '`t`.`order_number`';
				$fields_q['id'] = isset($fields['id']) ? $fields['id'] : '`t`.`id`';

				$sql = "SELECT ";
				foreach ($fields_q as $key => $value)
				{
					$sql .= "$value `$key`, ";
				}
				$sql .= "`t`.`date` ";
				$sql .= "FROM `{$esynAdmin->mPrefix}transactions` `t` ";
				foreach ($join as $table => $on)
				{
					$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}{$table}` {$table} ";
					$sql .= $on . " ";
				}
				$sql .= "WHERE `t`.`item` = '{$item['item']}' ";
				$sql .= !empty($where) ? 'AND ' . $where : '';

				$sqls[] = $sql;

				$sql = '';

			}

			$sql = '(' . implode(')UNION ALL(', $sqls) . ')';
			$sql .= "{$order} LIMIT {$start}, {$limit}";

			$esynAdmin->setTable('transactions');
			$transactions = $esynAdmin->getAll($sql, $values);
			$out['total'] = $esynAdmin->one('COUNT(*)', $where);
			$esynAdmin->resetTable();

			if (is_array($transactions))
			{
				foreach ($transactions as $key => $value)
				{
					$transactions[$key]['username'] = $value['account_id'] ?
						'<b><a title="' . _t('edit_account') . '" href="controller.php?file=accounts&do=edit&id='.$value['account_id'].'">'.$value['username'].'</a></b>' : '';
					$transactions[$key]['email'] = $value['email'] ?
						'<b><a title="' . _t('contact') . '" href="mailto:' . $value['email'] . '">' . $value['email'] . '</a></b>' : '';

					if ("listing" == $value['item'] || "account" == $value['item'])
					{
						$transactions[$key]['plan'] = $value['pln_title'] ?
							'<b><a title="' . _t('edit_plan') . '" href="controller.php?file=plans&do=edit&id=' . $value['plan_id'] . '">' . $value['pln_title'] . '</a></b>' :
							'<b>' . $value['plan_title'] . '</b>';

						if ("listing" == $value['item'])
						{
							$transactions[$key]['item'] = $value['itm_id'] ?
								'<b><a title="' . _t('edit_listing') . '" href="controller.php?file=suggest-listing&do=edit&id=' . $value['item_id'] . '">' . $value['item'] . '</a></b>' :
								'<b>' . $value['item'] . '</b>';
						}

						if ("account" == $value['item'])
						{
							$transactions[$key]['item'] = '<b><a title="' . _t('edit_account') . '" href="controller.php?file=accounts&do=edit&id=' . $value['item_id'] . '">' . $value['item'] . '</a></b>';
						}
					}
					else
					{
						$transactions[$key]['item'] = '<b>' . $value['item'] . '</b>';
						$transactions[$key]['plan'] = '<b>' . $value['plan_title'] . '</b>';
					}

					$esynAdmin->startHook('phpAdminTransactionsParse');
				}
			}
			$out['data'] = $transactions;
		}
	}

	if ('add' == $_GET['action'])
	{
		$out['error'] = false;
		$new_transaction = array();

		$new_transaction['item_id'] = (int)$_POST['listing'];

		$esynAdmin->setTable("listings");
		$listing_exist = $esynAdmin->exists("`id` = :id", array('id' => $new_transaction['item_id']));
		$esynAdmin->resetTable();

		$esynAdmin->setTable('accounts');
		$account_exist = $esynAdmin->exists("`id` = :id", array('id' => $new_transaction['item_id']));
		$esynAdmin->resetTable();

		if (!$listing_exist && !$account_exist)
		{
			$out['error'] = true;
			$out['data']['msg'][] = _t('item_not_exist');
		}

		$esynAdmin->resetTable();

		$new_transaction['plan_id'] = (int)$_POST['plan'];

		$esynAdmin->setTable("plans");
		if (!$esynAdmin->exists("`id` = :id", array('id' => $new_transaction['plan_id'])))
		{
			$out['error'] = true;
			$out['data']['msg'][] = _t('plan_not_exist');
		}
		$esynAdmin->resetTable();

		$new_transaction['email'] = $_POST['email'];

		if (!esynValidator::isEmail($new_transaction['email']))
		{
			$out['error'] = true;
			$out['data']['msg'][] = _t('incorrect_email');
		}

		$new_transaction['account_id'] = (int)$_POST['account'];
		$new_transaction['order_number'] = $_POST['order'];
		$new_transaction['total'] = (float)$_POST['total'];
		$new_transaction['date'] = $_POST['date'].' '.$_POST['time'];
		$new_transaction['item'] = isset($_POST['item']) && in_array($_POST['item'], array('account', 'listing')) ? $_POST['item'] : '';

		if (!$out['error'])
		{
			$esynAdmin->setTable("transactions");
			$esynAdmin->insert($new_transaction);
			$esynAdmin->resetTable();

			$out['data']['msg'][] = _t('transaction_added');

			$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

			$out['success'] = true;
		}
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	$out = array('msg' => array(), 'error' => false);

	if (!isset($_POST['ids']) || empty($_POST['ids']))
	{
		$out['error'] = true;
		$out['msg'][] = 'Transactions IDS are wrong.';
	}

	if ('remove' == $_POST['action'])
	{
		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable('transactions');
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'][] = 'Transaction has been removed.';
		}
	}

	if ('update' == $_POST['action'])
	{
		if (empty($_POST['field']) || empty($_POST['value']))
		{
			$out['error'] = true;
			$out['msg'][] = $esynI18N['transaction_wrong_params'];
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			if ($_POST['field'] == 'status' && $_POST['value'] == 'passed')
			{
				$esynAdmin->setTable('listings');
				$data = $esynAdmin->onefield("`changes_temp`", "`transaction_id` = '{$_POST['ids'][0]}'");
				$esynAdmin->resetTable();

				if ($data)
				{
					$data = unserialize($data[0]);
					$data['changes_temp'] = '';

					$esynAdmin->factory("Listing");
					$esynListing->update($data, "`transaction_id` = '{$_POST['ids'][0]}'");
				}
			}

			$esynAdmin->setTable('transactions');
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['transaction_updated'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title'] = $esynI18N['manage_transactions'];
$gBc[0]['url'] = 'controller.php?file=transactions';

$gTitle = $esynI18N['manage_transactions'];

$actions = array(
	array("url" => "#", "attributes" => 'id="add" onclick="return false;"', "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=transactions", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display('transactions.tpl');
