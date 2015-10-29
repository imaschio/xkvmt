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

/*
 * Checking-UP CRON
 *
 * Including:
 * 1. Checking for broken listings
 * 2. Checking for reciprocal listings
 * 3. Update pagerank of listings
 *
 */

define('IA_DEBUG_CRON', 0);

// uncomment if you want to run file from browser
//require_once('../header-lite.php');

global $esynConfig;

if (IA_DEBUG_CRON)
{
	echo '<b>Starting Cron Job</b><br />';
	echo '<p>';
	echo '<b>PHP Settings:</b><br />';
	echo '<i>allow_url_fopen</i> is: ' . ini_get('allow_url_fopen') . '<br />';
	echo '<i>curl</i> is : ' . extension_loaded('curl') . '<br />';
	echo '</p>';
}

if (ini_get('allow_url_fopen') || extension_loaded('curl'))
{
	// short name
	$eC = $esynConfig;

	if (IA_DEBUG_CRON)
	{
		echo '<p>';
		echo '<b>Script Settings:</b><br />';
		echo '<i>Checking Broken</i> is: ' . $eC->gC('cron_broken') . '<br />';
		echo '<i>Checking Reciprocal</i> is: ' . $eC->gC('cron_recip') . '<br />';
		echo '<i>Update Pagerank</i> is: ' . $eC->gC('cron_pagerank') . '<br />';
		echo '<i>Duplicate Checking</i> is: ' . $eC->gC('cron_duplicate') . '<br />';
		echo '</p>';
	}

	if ($eC->gC('cron_broken') || $eC->gC('cron_recip') || $eC->gC('cron_pagerank') || $eC->gC('cron_duplicate'))
	{
		$num_listings = ((int)$eC->gC('cron_num_listings')) ? (int)$eC->gC('cron_num_listings') : 10;

		$headers = $esynConfig->gC('http_headers');

		$correct_headers = explode(',', $headers);

		$duplicate_type = $eC->gC('duplicate_type');
		$duplicate_field = $duplicate_type == 'domain' ? 'domain' : 'url';

		$duplicate_where = $duplicate_type == 'domain' ? "`domain` = '{listing}'" : "`url` = '{listing}'";
		$duplicate_where = $duplicate_type == 'contain' ? "`url` LIKE '%{listing}%'" : $duplicate_where;

		$where = "`status` = 'active' ORDER BY `last_check_date` ASC LIMIT " . $num_listings;

		// Get listings to check
		$eSyndiCat->setTable('listings');
		$listings = $eSyndiCat->all('`id`, `url`, `domain`, `reciprocal`', $where);
		$eSyndiCat->resetTable();

		if (IA_DEBUG_CRON)
		{
			echo '<p>';
			echo '<b>List Listings:</b><br />';
			echo '<pre>';
			//print_r($listings);
			echo '</pre>';
			echo '</p>';
		}

		if (!empty($listings))
		{
			$count_all = 0;

			$count_broken = $count_reciprocal = $count_pagerank = $count_duplicate = 0;
			$wrng_broken = $wrng_reciprocal = $wrng_pagerank = $wrng_duplicate = array();

			foreach ($listings as $listing)
			{
				$updates = $addit = array();

				if (empty($listing['url']) || 'http://' == $listing['url'])
				{
					continue;
				}

				$listing['url'] = trim($listing['url']);
				$listing['reciprocal'] = trim($listing['reciprocal']);

				$count_all++;

				if (IA_DEBUG_CRON)
				{
					echo '<p style="padding-left: 10px;">';
					echo '<b>Listing URL: </b><a href="' . $listing['url'] . '" target="_blank">' . $listing['url'] . '</a><br />';
				}

				// PERFORM DEAD LINK CHECK
				if ($esynConfig->getConfig('cron_broken'))
				{
					if (IA_DEBUG_CRON)
					{
						echo '<b>Checking Broken:</b> ';
						$color = 'green';
					}

					$listing_header = esynUtil::getListingHeader($listing['url']);

					if (!in_array($listing_header, $correct_headers))
					{
						if (IA_DEBUG_CRON)
						{
							$color = 'red';
						}

						if ($esynConfig->getConfig('cron_report_job'))
						{
							$count_broken++;

							if ($esynConfig->getConfig('cron_report_job_extra'))
							{
								$wrng_broken[] = $listing['url'];
							}
						}
					}

					if (IA_DEBUG_CRON)
					{
						echo '<span style="color: ' . $color . ';">header = ' . $listing_header . '</span><br />';
					}

					$updates['listing_header'] = $listing_header;
				}

				// PERFORM RECIPROCAL LINK CHECK
				if ($esynConfig->getConfig('cron_recip'))
				{
					if (IA_DEBUG_CRON)
					{
						echo '<b>Reciprocal Valid:</b> ';
						$color = 'green';
					}

					$recip_valid = 0;

					$pageContent = esynUtil::getPageContent($listing['reciprocal']);

					$rcodes = explode("\r\n", $eC->gC('reciprocal_link'));
					foreach ($rcodes as $r)
					{
						$recip_valid = esynValidator::hasUrl($pageContent, $r);
						if ($recip_valid)
						{
							break;
						}
					}

					if ($esynConfig->getConfig('cron_report_job') && 0 == $recip_valid)
					{
						if (IA_DEBUG_CRON)
						{
							$color = 'red';
						}

						$count_reciprocal++;

						if ($esynConfig->getConfig('cron_report_job_extra'))
						{
							$wrng_reciprocal[] = $listing['url'];
						}
					}

					if (IA_DEBUG_CRON)
					{
						echo '<span style="color: ' . $color . ';">reciprocal = ' . $recip_valid . '</span><br />';
					}

					$updates['recip_valid'] = $recip_valid;

					if ($updates['recip_valid'] && $esynConfig->getConfig('recip_featured') && !$listing['featured'])
					{
						$updates['featured'] = '1';
						$addit['featured_start'] = 'NOW()';
					}
				}

				// PERFORM PAGERANK UPDATE
				if ($esynConfig->getConfig('cron_pagerank'))
				{
					if (IA_DEBUG_CRON)
					{
						echo '<b>Update Pagerank:</b> ';
						$color = 'green';
					}

					$pagerank = PageRank::getPageRank($listing['domain']);
					$pagerank = $pagerank ? $pagerank : -1;

					if ($esynConfig->getConfig('cron_report_job') && '-1' == $pagerank)
					{
						if (IA_DEBUG_CRON)
						{
							$color = 'red';
						}

						$count_pagerank++;

						if ($esynConfig->getConfig('cron_report_job_extra'))
						{
							$wrng_pagerank[] = $listing['url'];
						}
					}

					if (IA_DEBUG_CRON)
					{
						echo '<span style="color: ' . $color . ';">pagerank = ' . $pagerank . '</span><br />';
					}

					$updates['pagerank'] = $pagerank;
				}

				// PERFORM DUPLICATE CHECKING
				if ($eC->gC('cron_duplicate'))
				{
					if (IA_DEBUG_CRON)
					{
						echo '<b>Duplicate Checking:</b> ';
					}

					$d_where = str_replace('{listing}', $listing[$duplicate_field], $duplicate_where);
					$d_where .= " AND `id` != '{$listing['id']}'";

					$eSyndiCat->setTable('listings');
					$duplicates = $eSyndiCat->all('`id`', $d_where);
					$eSyndiCat->resetTable();

					if (!empty($duplicates))
					{
						$count_duplicate++;

						$wrng_duplicate[] = $listing['url'];

						$duplicate_ids = array();

						foreach ($duplicates as $duplicate)
						{
							$duplicate_ids[] = $duplicate['id'];
						}

						$updates['duplicate'] = '1';

						if (IA_DEBUG_CRON)
						{
							echo 'Duplicates IDs: ' . implode(',', $duplicate_ids);
						}

						$eSyndiCat->setTable('listings');
						$eSyndiCat->update(array('duplicate' => 1), "`id` IN ('" . implode("','", $duplicate_ids) . "')");
						$eSyndiCat->resetTable();
					}
				}

				$addit['last_check_date'] = 'NOW()';
				$updates['cron_cycle'] = 1;

				$eSyndiCat->setTable('listings');
				$eSyndiCat->update($updates, "`id` = '{$listing['id']}'", false, $addit);
				$eSyndiCat->resetTable();

				if (IA_DEBUG_CRON)
				{
					echo '</p>';
				}
			}

			if ($esynConfig->getConfig('cron_report_job'))
			{
				$date_check = date("F j, Y, g:i a");
				$subject="Cron report [{$date_check}]";
				$body = $count_all." listings were checked \n=========================\n";

				if ($esynConfig->getConfig('cron_broken'))
				{
					$body .= "{$count_broken} broken listings \n";

					if ($esynConfig->getConfig('cron_report_job_extra') && $count_broken > 0)
					{
						$body .= "-->Warning these listings are Broken\n".implode("\n", $wrng_broken)."\n";
					}
				}

				if ($esynConfig->getConfig('cron_recip'))
				{
					$body .= "{$count_reciprocal} non-reciprocal listings \n";

					if ($esynConfig->getConfig('cron_report_job_extra') && $count_reciprocal > 0)
					{
						$body .= "-->Warning these listings fail Reciprocal Test\n".implode("\n", $wrng_reciprocal)."\n";
					}
				}

				if ($esynConfig->getConfig('cron_pagerank'))
				{
					$body .= "{$count_pagerank} listings PageRank not updated\n";

					if ($esynConfig->getConfig('cron_report_job_extra') && $count_pagerank > 0)
					{
						$body .= "-->Warning these listings fail for Pagerank update\n".implode("\n", $wrng_pagerank)."\n";
					}
				}

				if ($eC->gC('cron_duplicate'))
				{
					$body .= "{$count_duplicate} duplicated listings\n";

					if ($eC->gC('cron_report_job_extra') && $count_duplicate > 0)
					{
						$body .= "-->Warning these listings were marked as duplicated \n".implode("\n", $wrng_duplicate)."\n";
					}
				}

				mail($esynConfig->getConfig('site_email'), $subject, $body, 'From: '.$esynConfig->getConfig('site_email')."\r\n");
			}
		}

		$eSyndiCat->setTable('listings');
		$check_all = $eSyndiCat->one('COuNT(`id`)',"`cron_cycle`='0'");
		$eSyndiCat->resetTable();

		if ($esynConfig->getConfig('cron_report_cycle'))
		{
			if (empty($check_all))
			{
				$eSyndiCat->setTable('listings');
				$listings = $eSyndiCat->all('`url`,`listing_header`,`recip_valid`,`pagerank`,`duplicate`',"`cron_cycle`='1'");
				$eSyndiCat->resetTable();

				if (!empty($listings))
				{
					$count_all = 0;

					$count_broken = 0;
					$count_reciprocal = 0;
					$count_pagerank = 0;
					$count_duplicate = 0;

					$wrng_broken = array();
					$wrng_reciprocal = array();
					$wrng_pagerank = array();
					$wrng_duplicate = array();

					foreach ($listings as $listing)
					{
						if( empty($listing['url']) || 'http://' == $listing['url'] )
						{
							continue;
						}

						$count_all++;

						if (!in_array($listing['listing_header'], $correct_headers))
						{
							$count_broken++;

							if ($esynConfig->getConfig('cron_report_cycle_extra'))
							{
								$wrng_broken[] = $listing['url'];
							}
						}

						if (0 == $listing['recip_valid'])
						{
							$count_reciprocal++;

							if ($esynConfig->getConfig('cron_report_cycle_extra'))
							{
								$wrng_reciprocal[] = $listing['url'];
							}
						}

						if (-1 == $listing['pagerank'])
						{
							$count_pagerank++;

							if ($esynConfig->getConfig('cron_report_cycle_extra'))
							{
								$wrng_pagerank[] = $listing['url'];
							}
						}

						if ('1' == $listing['duplicate'])
						{
							$count_duplicate++;

							if ($esynConfig->getConfig('cron_report_cycle_extra'))
							{
								$wrng_duplicate[] = $listing['url'];
							}
						}
					}

					$date_check = $date_check ? $date_check : date("F j, Y, g:i a");

					$subject = "Cron cycle finished  [{$date_check}]";
					$body = $count_all." listings checked \n=========================\n";

					if ($esynConfig->getConfig('cron_broken'))
					{
						$body .= "{$count_broken} broken listings \n";

						if ($esynConfig->getConfig('cron_report_cycle_extra') && $count_broken > 0)
						{
							$body .= "-->Warning these listings are Broken\n".implode("\n",$wrng_broken)."\n";
						}
					}

					if ($esynConfig->getConfig('cron_recip'))
					{
						$body .= "{$count_reciprocal} non-reciprocal listings \n";

						if ($esynConfig->getConfig('cron_report_cycle_extra') && $count_reciprocal > 0)
						{
							$body .= "-->Warning these listings fail Reciprocal Test\n".implode("\n",$wrng_reciprocal)."\n";
						}
					}

					if ($esynConfig->getConfig('cron_pagerank'))
					{
						$body .= "{$count_pagerank} listings PageRank not updated\n";

						if ($esynConfig->getConfig('cron_report_cycle_extra') && $count_pagerank > 0)
						{
							$body .= "-->Warning these listings fail for Pagerank update\n".implode("\n",$wrng_pagerank)."\n";
						}
					}

					if ($eC->gC('cron_duplicate'))
					{
						$body .= "{$count_duplicate} duplicated listings\n";

						if ($eC->gC('cron_report_job_extra') && $count_duplicate > 0)
						{
							$body .= "-->Warning these listings were marked as duplicated \n".implode("\n", $wrng_duplicate)."\n";
						}
					}

					mail($esynConfig->getConfig('site_email'), $subject, $body, 'From: '.$esynConfig->getConfig('site_email')."\r\n");
				}
			}
		}

		if (empty($check_all))
		{
			$eSyndiCat->setTable('listings');
			$eSyndiCat->update(array('cron_cycle'=>'0'));
			$eSyndiCat->resetTable();
		}
	}
}
