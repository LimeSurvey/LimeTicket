<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );


class LimeticketsModelTicketgroups extends JModelLegacy
{
   
    var $_data;

	var $_total = null;

	var $lists = array(0);

	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        $mainframe = JFactory::getApplication(); global $option;

  		$context = "ticket_group_list_";

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($context.'.list.limitstart', 'limitstart', 0, 'int');

 		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		if (!$filter_order)
			$filter_order = "";

		if ($filter_order == "name") $filter_order = "groupname";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
 		$db	= JFactory::getDBO();

        $query = ' SELECT g.id, g.groupname, g.description, cnt, allsee, allemail, allprods FROM #__limeticket_ticket_group as g LEFT JOIN ';
		$query .= "(SELECT group_id, count(*) as cnt FROM #__limeticket_ticket_group_members GROUP BY group_id) as c ON g.id = c.group_id ";
		$where = array();

		if ($this->lists['search']) {
			$where[] = '(LOWER( g.description ) LIKE '.$db->Quote( '%'.LIMETICKETJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}
		
		$order = "";

		if ($this->lists['order'] != '') {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'];
		}

  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;
		//echo $query."<br>";
  		return $query;
    }

    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_data;
    }

    function getTotal()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    function getPagination()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }

    function getLists()
    {
		return $this->lists;
	}

}


