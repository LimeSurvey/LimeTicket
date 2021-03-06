<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');



class LimeticketsModelTicketgroup extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__limeticket_ticket_group '.
					'  WHERE id = '.LIMETICKETJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->groupname = null;
			$this->_data->description = null;
			$this->_data->allsee = 0;
			$this->_data->allemail = 0;
			$this->_data->allprods = 1;
			$this->_data->ccexclude = 0;
		}
		return $this->_data;
	}

	function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// sort code for all products
		$db	= JFactory::getDBO();
		
		$query = "DELETE FROM #__limeticket_ticket_group_prod WHERE group_id = " . LIMETICKETJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new product ids
		if (!$row->allprods)
		{
			$query = "SELECT * FROM #__limeticket_prod ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "prod_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__limeticket_ticket_group_prod (group_id, prod_id) VALUES (" . LIMETICKETJ3Helper::getEscaped($db, $row->id) . "," . LIMETICKETJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		
		$this->_id = $row->id;
		$this->_data = $row;

		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				
				$qry = "DELETE FROM #__limeticket_ticket_group_members WHERE group_id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				$this->_db->query();

				$qry = "DELETE FROM #__limeticket_ticket_group_prod WHERE group_id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				$this->_db->query();

				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}

			}
		}
		return true;
	}
}


