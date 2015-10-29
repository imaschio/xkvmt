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

class esynAdmins extends eSyndiCat
{
	/**
	 * Description of the Variable
	 * @var		mixed
	 * @access	public
	 */
	var $mTable = 'admins';

	function delete($ids = '', $arg1 = array(), $arg2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAdminDelete", $params);

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		$where = $this->convertIds('id', $ids);

		parent::delete($where);

		$where = $this->convertIds('admin_id', $ids);

		parent::setTable("admin_permissions");
		parent::delete($where);
		parent::resetTable();

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAdminDelete", $params);

		return true;
	}

	function insert($admin, $arg1 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAdminInsert", $params);

		if (isset($admin['permissions']))
		{
			$permissions = $admin['permissions'];

			unset($admin['permissions']);
		}

		$admin_id = parent::insert($admin, array('date_reg' => 'NOW()'));

		if (!empty($permissions))
		{
			$acos = $this->getAcos();

			foreach($permissions as $key => $permission)
			{
				if (array_key_exists($permission, $acos))
				{
					$admin_permissions[] = array(
						"admin_id"	=> $admin_id,
						"allow"		=> 1,
						"aco"		=> $permission
					);
				}
			}

			if (!empty($admin_permissions))
			{
				parent::setTable("admin_permissions");
				parent::insert($admin_permissions);
				parent::resetTable();
			}
		}

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAdminInsert", $params);

		return true;
	}

	function update($admin, $ids = '', $arg1 = array(), $arg2 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeAdminUpdate", $params);

		if (empty($admin))
		{
			$this->message = 'The Admin parameter is empty.';

			return false;
		}

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		if (isset($admin['permissions']))
		{
			$acos = $this->getAcos();

			if ($acos)
			{
				foreach($acos as $key => $aco)
				{
					if (in_array($key, $admin['permissions'], true))
					{
						if (is_array($ids))
						{
							foreach($ids as $id)
							{
								$temp = array(
									"admin_id"	=> $id,
									"allow"		=> 1,
									"aco"		=> $key
								);

								$permissions[] = $temp;
							}
						}
						else
						{
							$temp = array(
								"admin_id"	=> $ids,
								"allow"		=> 1,
								"aco"		=> $key
							);

							$permissions[] = $temp;
						}
					}
				}

				if (!empty($permissions))
				{
					parent::setTable("admin_permissions");
					parent::delete("`admin_id` = :id", array('id' => $ids));
					parent::insert($permissions);
					parent::resetTable();
				}
			}

			unset($admin['permissions']);
		}

		if (isset($admin['id']) && !empty($admin['id']))
		{
			$values = array('id' => $admin['id']);
			$where = "`id` = :id";
		}

		if (isset($ids) && !empty($ids))
		{
			$values = array();
			$where = $this->convertIds('id', $ids);
		}

		parent::update($admin, $where, $values);

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterAdminUpdate", $params);

		return true;
	}

	function getAcos()
	{
		$acos = array();

		$this->setTable("admin_pages");
		$acos = $this->keyvalue("`aco`", "1=1 GROUP BY `aco`");
		$this->resetTable();

		return $acos;
	}
}

