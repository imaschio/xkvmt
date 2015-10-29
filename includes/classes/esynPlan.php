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
 * esynPlan
 *
 * @uses eSyndiCat
 * @package
 * @version $id$
 */
class esynPlan extends eSyndiCat
{

	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'plans';

	/**
	 * Prepare payment information
	 * @param array $item
	 * @param array $payment
	 * @param string $cost
	 * @param array $plan
	 * @return bool|string
	 */
	public function preparePayment($item, $payment, $cost, $plan)
	{
		if (empty($item))
		{
			return false;
		}

		if (empty($payment))
		{
			return false;
		}

		$sec_key = esynUtil::getNewToken(16);
		$cost = $cost ? $cost : $plan['cost'];

		$invoice = array();

		$invoice['item_id'] = $item['id'];
		$invoice['item'] = $item['item'];

		$invoice['plan_title'] = isset($plan['title']) ? $plan['title'] : '';
		$invoice['account_id'] = isset($item['account_id']) ? $item['account_id'] : 0;
		$invoice['plugin'] = isset($item['plugin']) ? $item['plugin'] : '';
		$invoice['method'] = isset($item['method']) ? $item['method'] : '';

		$invoice['plan_id'] = $plan['id'];
		$invoice['payment_gateway'] = $payment;

		$invoice['total'] = $cost;

		$invoice['sec_key'] = $sec_key;

		$this->setTable('transactions');
		$this->insert($invoice, array('date' => 'NOW()'));
		$this->resetTable();

		return $sec_key;
	}

	/**
	 * getAllPlansByCategory
	 *
	 * Returns all plan by category
	 *
	 * @param mixed $id
	 * @param string $where
	 * @return array
	 */
	public function getAllPlansByCategory($id, $where = '')
	{
		$this->setTable("flat_structure");

		$parents = parent::onefield("`parent_id`", "`category_id`='" . $id . "' AND `parent_id`<>  '" . $id . "'");

		$parents = (!$parents) ? '0' : join(',', $parents);

		$this->resetTable();

		$sql = "SELECT `p`.* FROM `{$this->mTable}` `p` "
			. "LEFT JOIN `{$this->mPrefix}plan_categories` `pc` "
			. "ON `pc`.`plan_id` = `p`.`id` "
			. "WHERE (`pc`.`category_id` = '{$id}' OR `p`.`recursive` = '1' ";

		if ($id != $parents)
		{
			$sql .= " AND `pc`.`category_id` IN ({$parents}))";
		}
		else
		{
			$sql .= "AND `pc`.`category_id` = '{$id}')";
		}

		$sql .= !empty($where) ? " AND {$where}" : '';
		$sql .= " AND `p`.`status` = 'active'";
		$sql .= " ORDER BY `p`.`order` ASC ";

		return $this->getAll($sql);
	}
}
