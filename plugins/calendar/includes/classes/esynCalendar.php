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
 * esynCalendar 
 * 
 * @uses eSyndiCat
 * @package 
 * @version $id$
 */
class esynCalendar extends eSyndiCat
{	
	/**
	 * esynCalendar 
	 * 
	 * @access public
	 * @return void
	 */
	function esynCalendar()
	{
		parent::eSyndiCat();
	}

	/**
	 * getFromTo 
	 * 
	 * @param mixed $from 
	 * @param mixed $to 
	 * @access public
	 * @return void
	 */
	function getFromTo($from, $to)
	{
		$sql = "SELECT `t1`.*, `t2`.`path` AS `path` ";
		$sql .= "FROM `{$this->mPrefix}listings` `t1` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}categories` `t2` ";
		$sql .= "ON `t1`.`category_id` = `t2`.`id` ";
		$sql .= "WHERE `t1`.`status` = 'active' AND ";
		$sql .= "`t1`.`date` >= '{$from}' AND `t1`.`date` <= '{$to}'";

		return $this->getAll($sql);
	}

	/**
	 * getFrom 
	 * 
	 * @param mixed $from 
	 * @access public
	 * @return void
	 */
	function getFrom($from)
	{
		$sql = "SELECT `t1`.*, `t2`.`path` `path` ";
		$sql .= "FROM `{$this->mPrefix}listings` `t1`";
		$sql .= "LEFT JOIN `{$this->mPrefix}categories` `t2` ";
		$sql .= "ON `t1`.`category_id` = `t2`.`id` ";
		$sql .= "WHERE `t1`.`status` = 'active' AND ";
		$sql .= "`t1`.`date` >= '{$from}' AND `t1`.`date` <= '{$from} 23:59:59'";

		return $this->getAll($sql);
	}
	
	/**
	 * getListingByDate 
	 * 
	 * @param mixed $year 
	 * @param mixed $month 
	 * @param mixed $day 
	 * @access public
	 * @return void
	 */
	function getListingByDate($year, $month, $day, $start = 0, $limit = '')
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `t1`.*, `t2`.`path` `path` ";
		$sql .= "FROM `{$this->mPrefix}listings` `t1` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}categories` `t2` ";
		$sql .= "ON `t1`.`category_id` = `t2`.`id` ";
		$sql .= "WHERE `t1`.`status` = 'active' AND ";
		$sql .= "`t1`.`date` >= '{$year}/{$month}/{$day}' AND ";
		$sql .= "`t1`.`date` <= '{$year}/{$month}/{$day} 23:59:59' ";
		$sql .= $limit ? "LIMIT $start, $limit" : '';

		return $this->getAll($sql);
	}
	
	/**
	 * getActiveDays 
	 * 
	 * @param mixed $year 
	 * @param mixed $month 
	 * @access public
	 * @return void
	 */
	function getActiveDays($year, $month)
	{
		$sql = "SELECT DATE_FORMAT(`date`, '%d') `date` ";
		$sql .= "FROM `{$this->mPrefix}listings` ";
		$sql .= "WHERE `date` >= '{$year}/{$month}/01' ";
		$sql .= "AND `date` <= '{$year}/{$month}/31' ";
		$sql .= "AND `status` = 'active' ";
		
		return $this->getAll($sql);
	}
	
	/**
	 * getActiveDaysOnMonth 
	 * 
	 * @param mixed $year 
	 * @param mixed $month 
	 * @access public
	 * @return void
	 */
	function getActiveDaysOnMonth($year, $month, $start = 0, $limit = '')
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `t1`.*, `t2`.`path` `path` ";
		$sql .= "FROM `{$this->mPrefix}listings` `t1` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}categories` `t2` ";
		$sql .= "ON `t1`.`category_id` = `t2`.`id` ";
		$sql .= "WHERE `t1`.`status` = 'active' ";
		$sql .= "AND `t1`.`date` >= '{$year}/{$month}/01' ";
		$sql .= "AND `t1`.`date` <= '{$year}/{$month}/31' ";
		$sql .= $limit ? "LIMIT $start, $limit" : '';

		return $this->getAll($sql);
	}
	/**
	 * generateCalendar 
	 * 
	 * @param mixed $year 
	 * @param mixed $month 
	 * @param array $days 
	 * @param int $day_name_length 
	 * @param mixed $month_href 
	 * @param int $first_day 
	 * @param array $pn 
	 * @access public
	 * @return void
	 */
	function generateCalendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array())
	{
		$first_of_month = gmmktime(0,0,0,$month,1,$year);
		#remember that mktime will automatically correct if invalid dates are entered
		# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		# this provides a built in "rounding" feature to generate_calendar()

		$day_names = array(); #generate all the day names according to the current locale
		for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
			$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
		$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
		$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

		#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
		@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
		if ($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
		if ($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
		$calendar = '<table class="calendar" cellpadding="1" cellspacing="0" width="100%">'."\n".
			'<caption class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";

		if ($day_name_length){ #if the day names should be shown ($day_name_length > 0)
			#if day_name_length is >3, the full name of the day will be printed
			foreach($day_names as $d)
				$calendar .= '<th abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
			$calendar .= "</tr>\n<tr>";
		}

		if ($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			if ($weekday == 7){
				$weekday   = 0; #start a new week
				$calendar .= "</tr>\n<tr>";
			}
			if (isset($days[$day]) and is_array($days[$day])){
				@list($link, $classes, $content) = $days[$day];
				if (is_null($content))  $content  = $day;
				$calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
					($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
			}
			else $calendar .= "<td>$day</td>";
		}
		if ($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

		return $calendar."</tr>\n</table>\n";
	}
}