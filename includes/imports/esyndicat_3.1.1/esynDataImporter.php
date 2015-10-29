<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.1.1
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

require_once IA_ADMIN_CLASSES. 'esynImporter.php';
require_once dirname(__FILE__) . IA_DS . 'config.php';

class esynDataImporter extends esynImporter
{
	public function truncateBlocks()
	{
		$sql = "DELETE FROM `" . IA_DBPREFIX . "blocks` ";
		$sql .= "WHERE `name` NOT IN ('sponsored', 'featured', 'partner', 'statistics', 'actions', 'inventory', 'main', 'account', ";
		$sql .= "'bottom', 'slider', 'block_12', 'block_13', 'block_14', 'block_15') AND `plugin` = '' ";

		$this->destDb->query($sql);
	}

	public function getRecordsBlocks()
	{
		$this->setTable($this->mSourceTable);

		$where = "`name` != 'sponsored' ";
		$where .= "AND `name` != 'featured' ";
		$where .= "AND `name` != 'partner' ";
		$where .= "AND `name` != 'statistics' ";
		$where .= "AND `name` != 'accounts_area' ";
		$where .= "AND `name` != 'actions' ";
		$where .= "AND `type` != 'menu' ";
		$where .= "AND `plugin` = '' ";

		$records = $this->all("*", $where, array());
		$this->resetTable();

		return $records;
	}

	public function processRecordBlocks($record, $fields = array())
	{
		$result = parent::processMapping($record, $fields);

		$result['status'] = 'inactive';

		return $result;
	}
}
