<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');

class LimeticketViewTicket_User extends LimeticketViewTicket
{
	function display($tpl = NULL)
	{
		$db	= JFactory::getDBO();

		$this->ticket = new SupportTicket();
		if (!$this->ticket->load(LIMETICKET_Input::getInt('ticketid'), true)) return;
		$this->ticket->loadCC();
		$this->ticket->setupUserPerimssions();		
			
		$qry = "SELECT g.id, g.ccexclude FROM #__limeticket_ticket_group_members AS gm LEFT JOIN #__limeticket_ticket_group AS g ON gm.group_id = g.id WHERE user_id = ".$db->escape($this->ticket->user_id);

		$db->setQuery($qry);
		$gids = array();
		$rows = $db->loadObjectList();
		foreach($rows as $row) if ($row->ccexclude == 0) $gids[$row->id] = $row->id;
				
		if (count($gids) == 0) return;
		
		$qry = "SELECT user_id FROM #__limeticket_ticket_group_members WHERE group_id IN (" . implode(", ",$gids) . ")";
		$db->setquery($qry);
		$user_ids = $db->loadObjectList('user_id');
				
		$uids = array();
		foreach($user_ids as $uid => &$group) $uids[$uid] = $uid;
		
		if ($this->ticket->user_id > 0) unset($uids[$this->ticket->user_id]);
		
		if (array_key_exists("cc",$this->ticket))
		{
			foreach ($this->ticket->user_cc as $ccuser) unset($uids[$ccuser->id]);		
		}
		
		$qry = "SELECT * FROM #__users ";
		$where = array();
		
		$limitstart = LIMETICKET_Input::getInt('limitstart');
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = LIMETICKET_Input::getString('search');
		
		if ($search != "")
		{
			$where[] = "(username LIKE '%".LIMETICKETJ3Helper::getEscaped($db, $search)."%' OR name LIKE '%".LIMETICKETJ3Helper::getEscaped($db, $search)."%' OR email LIKE '%".LIMETICKETJ3Helper::getEscaped($db, $search)."%')";
		}
		
		if (count($uids) > 0)
		{
			$where[] = "id IN (" . implode(", ", $uids) . ")";
		} else {
			$where[] = "id = 0";		
		}
		
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ", $where);	
		}

		// Sort ordering
		$qry .= " ORDER BY name ";
		
		// get max items
		$db->setQuery( $qry );
		$db->query();
		$maxitems = $db->getNumRows();
		
		//echo $qry . "<br>";
		
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->users = $db->loadObjectList();

		
		// build pagination
		$this->pagination = new JPaginationEx($maxitems, $limitstart, $limit );
		$this->search = $search;

		$this->_display();
	}	
}
