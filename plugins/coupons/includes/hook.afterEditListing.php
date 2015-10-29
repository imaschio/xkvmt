<?php

global $coupon, $eSyndiCat, $plan, $params;

if (isset($coupon) && !empty($coupon))
{
	$coupon_used = array("coupon_id" => $coupon['id'], "item_id" => $params['id'], "item" => $params['item_type']);

	$eSyndiCat->setTable('coupons_used');
	$eSyndiCat->delete("`paid` = '0'");
	$eSyndiCat->insert($coupon_used, array("date" => "NOW()"));
	$eSyndiCat->resetTable();

	$plan['cost'] = $plan['cost'] * ((100 - $coupon['discount'])/100);

	if($plan['cost'] <= 0)
	{
		$eSyndiCat->setTable('coupons_used');
		$coupon_used = $eSyndiCat->row("`coupon_id`, `id`", "`item_id` = :item_id AND `item` = :item AND `paid` = '0'", array('item_id' => $params['item_id'], 'item' => $params['item']));
		$eSyndiCat->update(array("paid" => "1"), "`id` = :id", array('id' => $coupon_used['id']));
		$eSyndiCat->resetTable();

		$eSyndiCat->setTable("coupons");
		$coupon = $eSyndiCat->row("`discount`, `id`, `time_used`", "`id` = '".$coupon_used['coupon_id']."'");
		$time_used = $coupon['time_used'] - 1;
		$status = $time_used ? 'active' : 'inactive';
		$eSyndiCat->update(array("status" => $status, "time_used" => $time_used), "`id` = :coupon_id", array("coupon_id" => $coupon_used['coupon_id']));
		$eSyndiCat->resetTable();
	}
}