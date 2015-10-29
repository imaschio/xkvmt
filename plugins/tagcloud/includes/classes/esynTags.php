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

class esynTags extends eSyndiCat
{
	var $mTable = 'tags';

	function process_listing($aListing, $aListingId = 0, $aRecount = true)
	{
		$converted_tags = array();

		$aListing = empty($aListing) ? false : $aListing;
		$aListingId = $aListingId ? $aListingId : $aListing['id'];
		$tags = empty($aListing['tag']) ? array() : explode(',', str_replace(', ', ',', $aListing['tag']));

		if ($tags && !empty($aListingId))
		{
			$tags = array_map('trim', $tags);
			$sql = "INSERT IGNORE `{$this->mPrefix}tags` (`id_listing`, `tag`) VALUES ";
			$i = 0; // valid tags count
			foreach ($tags as $t)
			{
				if (empty($t)) continue;

				if ($this->mConfig['tags_convert_utf_ascii'])
				{
					require_once IA_CLASSES.'esynUtf8.php';

					esynUtf8::loadUTF8Core();
					esynUtf8::loadUTF8Util('utf8_to_ascii');

					$t = utf8_to_ascii($t);
				}
				$converted_tags[] = $t;

				$sql .= sprintf("(%d, '%s'),", $aListingId, $this->escape_sql($t));
				$i++;
			}

			$sql = substr($sql, 0, -1);
			$i and $this->query( $sql );
			
			$aRecount and $this->recount_sum();
		}
		else
		{
			$converted_tags = $tags;
		}

		return implode(', ', $converted_tags);
	}

	/*
	 * recount data in the "tags_sum" table
	 */
	function recount_sum()
	{
		$sql = "TRUNCATE TABLE `{$this->mPrefix}tags_sum`";
		$this->query($sql);
		
		$sql  = "INSERT INTO `{$this->mPrefix}tags_sum` (`tag`, `count`) ";
		$sql .= "SELECT `tag`, COUNT(`tag`) FROM `{$this->mPrefix}tags` GROUP BY `tag` ";
		$this->query($sql);
	}

	/*
	 * Return top tags
	 *
	 * @param int $aLimit top listings limit
	 * @return array
	 */
	function top_tags($aLimit = 0)
	{
		//$aLimit = $aLimit ? $aLimit : $this->mConfig['tags_count'];
		$this->setTable('tags_sum');
		$tags = $this->all('*', "1 ORDER BY `count` DESC", false, 0, $aLimit);
		$tags = $tags ? $tags : array();
		list($size_min, $size_max) = explode('-', $this->mConfig['tags_size']);
		if ($tags)
		{
			// count the tags weight
			$max = $tags[0]['count'];
			$min = $tags[count($tags)-1]['count'];
			$log_diff = $max - $min ? log($max) - log($min) : 1; // avoid division by zero
			$size_min = $max - $min ? $size_min : 100; // fix
			foreach ($tags as $i => $t)
			{
				$weight = ( log($t['count']) - log($min) ) / $log_diff;
				$size = $size_min + round( ($size_max - $size_min) * $weight);
				$tags[$i]['weight'] = $weight;
				$tags[$i]['size'] = $size;
			}
			// end
		}
		return $tags;
	}

	function auto_build_tags()
	{
		$this->query("TRUNCATE TABLE `{$this->mPrefix}tags`");
		//$this->query("UPDATE `{$this->mPrefix}listings` SET `tag` = ''"); //for tests

		if (!defined('IA_NOUTF'))
		{
			require_once(IA_CLASSES.'esynUtf8.php');
			$esynUtf8 = new esynUtf8();

			$esynUtf8->loadUTF8Core();
			$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
		}
		$this->setTable('listings');
		$listings = $this->all('`id`, `title`, description, `tag`', "`status` = 'active'");

		$sql = "INSERT IGNORE `{$this->mPrefix}tags` (`id_listing`, `tag`) VALUES ";
		$values = '';
		$i = 0;

		foreach ($listings as $l)
		{
			// use title if tag field is empty
			if ($this->mConfig['use_description_for_tags'])
			{
				if (empty($l['tag']))
				{
					$tags = explode(' ', $l['description']);
					foreach ($tags as $tag)
					{
						$tag = preg_replace("/[^\p{L}\p{N}\s]/u","",$tag);
						$len = utf8_is_ascii($tag) ? strlen($tag) : utf8_strlen($tag);
						if (empty($tag) || ($len < 3)) continue;
						$l['tag'] .= $tag . ", ";

						if ($this->getConfig('tags_convert_utf_ascii'))
						{
							$l['tag'] = utf8_to_ascii($l['tag']);
						}
					}
					$l['tag'] = trim($l['tag'], " ,");
					$this->update(array("tag" => $l['tag']), "`id` = '".$l['id']."'");
				}
			}

			// use title if tag field is empty
			if ($this->mConfig['use_title_for_tags'])
			{
				if (empty($l['tag']))
				{
					$tags = explode(' ', $l['title']);
					foreach ($tags as $tag)
					{
						$tag = preg_replace("/[^\p{L}\p{N}\s]/u","",$tag);
						$len = utf8_is_ascii($tag) ? strlen($tag) : utf8_strlen($tag);
						if (empty($tag) || ($len < 3)) continue;
						$l['tag'] .= $tag . ", ";

						if ($this->getConfig('tags_convert_utf_ascii'))
						{
							$l['tag'] = utf8_to_ascii($l['tag']);
						}
					}
					$l['tag'] = trim($l['tag'], " ,");
					$this->update(array("tag" => $l['tag']), "`id` = '".$l['id']."'");
				}
			}

			$tags = explode(',', $l['tag']);

			if ($tags)
			{
				$tags = array_map('trim', $tags);

				foreach ($tags as $t)
				{

					if (empty($t)) continue;

					$i++;
					$values .= sprintf("(%d, '%s'),", $l['id'], $this->escape_sql($t));
					if ($i%1000 == 0)
					{
						$r = $this->query($sql . trim($values, ' ,'));
						$values = '';
					}
				}
			}
		}
		if (!empty($values))
		{
			$r = $this->query($sql . trim($values, ' ,'));
		}

		$this->recount_sum();
	}

	function getByCriteria($aStart = 0, $aLimit = 0, $aCause = '', $calcFoundRows = false, $aAccount = false, $aOrder = '')
	{
		$sql = "SELECT " . ($calcFoundRows ? 'SQL_CALC_FOUND_ROWS' : '') . " `listings`.*, ";
		$sql .= "`categories`.`path`, `categories`.`title` `category_title`, `categories`.`path` `category_path`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `categories`.`account_id` = `accounts`.`id`) `account_username`, ";
		$sql .= "IF (`listings`.`sponsored` = '1', '3', IF (`listings`.`featured` = '1', '2', IF (`listings`.`partner` = '1', '1', '0'))) `listing_type`, ";
		if ($this->mConfig['listing_marked_as_new'])
		{
			$sql .= "IF ((`listings`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval`, ";
		}
		if ($aAccount)
		{
			$sql .= "IF (`fav_accounts_set` LIKE '%{$aAccount},%', '1', '0') `favorite`, ";
			$sql .= "IF ((`listings`.`account_id` = 0) OR (`listings`.`account_id` = '0'), '0', '1') `account_id_edit` ";

			$account_sql = "AND (`listings`.`status` = 'active' OR `listings`.`account_id` = {$aAccount}) ";
		}
		else
		{
			$sql .= "'0' `favorite`, '0' `account_id_edit` ";

			$account_sql = "AND `listings`.`status` = 'active' ";
		}
		$sql .= "FROM `" . $this->mPrefix . "listings` `listings` ";

		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` `categories` ";
		$sql .= "ON `categories`.`id` = `listings`.`category_id` ";

		$sql .= $aCause;

		$sql .= "AND `categories`.`status` = 'active' " . $account_sql;
		$sql .= "AND `categories`.`hidden` = '0' ";
		
		$sql .= " GROUP BY `listings`.`id` ";
		$sql .= "ORDER BY ";
		$sql .= $aOrder ? $aOrder : "`listing_type` DESC, `listings`.`date` DESC ";
		$sql .= $aLimit ? " LIMIT {$aStart}, {$aLimit}" : '';

		return $this->getAll($sql);
	}
}