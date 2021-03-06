<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 32700488e7ef919827a91d968578ca8c
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'translate.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_tickets.php');

class LimeticketViewAdmin_Support extends LIMETICKETView
{
	function display($tpl = NULL)
	{
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		$autologin = LIMETICKET_Input::getCmd('login');
		if ($autologin != "") LIMETICKET_Helper::AutoLogin($autologin);

		$layout = LIMETICKET_Input::getCmd('layout', 'list');	
		$layout = preg_replace("/[^a-z0-9\_]/", '', $layout);
				
		$file = JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'layout.' . $layout . '.php';
		if (!file_exists($file))
		{
			$file = JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'layout.list.php';
			$layout = "list";
		}
		require_once($file);
		
		$class_name = "LimeticketViewAdmin_Support_" . $layout;
		
		$layout_handler = new $class_name();
		$layout_handler->setLayout($layout);
		$layout_handler->_models = $this->_models;
		$layout_handler->_defaultModel = $this->_defaultModel;
		if (!$layout_handler->init())
			return false;

		$layout_handler->display();
	}
	
	public function getName()
	{
		$this->_name = "admin_support";
		return $this->_name;
	}
	
	function _display($tpl = NULL)
	{
		parent::display($tpl);	
	}
	
	function init()
	{
		$user = JFactory::getUser();
		$this->userid = $user->get('id');

		$this->model = $this->getModel("admin_support");
		
		if (!LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin"))
			return LIMETICKET_Admin_Helper::NoPerm();

		$this->def_open = LIMETICKET_Ticket_Helper::GetStatusID('def_open');
		$this->ticket_view = LIMETICKET_Input::getCmd('tickets', $this->def_open);
		$this->count = SupportTickets::getTicketCount();

		LIMETICKET_Helper::StylesAndJS(array('calendar', 'base64'));

		if (Task_Helper::HandleTasks($this))
			return false;
		
		return true;
	}
	
	var $is_restricted;
	var $got_res;
	var $ticket_type = 'ticket_admin';
	
	function is_Restricted()
	{
		if (empty($this->got_res))
		{
			$this->got_res = true;

			$cur_user = JFactory::getUser()->id;

			$this->is_restricted = LIMETICKET_Permission::auth("limeticket.ticket_admin.restrict", "com_limeticket.support_admin", $cur_user);
			
			if (!empty($this->ticket))
			{
				if ($this->ticket->admin_id != $cur_user)
				{
					$is_cc = false;
					
					foreach ($this->ticket->admin_cc as $cc_user)
					{
						if ($cc_user->id ==	$cur_user)
							$is_cc = true;
					}
					
					
						
					if ($is_cc)
					{
						if (LIMETICKET_Permission::auth("limeticket.ticket_admin_cc.restrict", "com_limeticket.support_admin", $cur_user))
						{
							$this->is_restricted = 1;
							$this->ticket_type = "ticket_admin_cc";	
						}
					} elseif ($this->ticket->admin_id == 0) {
						if (LIMETICKET_Permission::auth("limeticket.ticket_admin_una.restrict", "com_limeticket.support_admin", $cur_user))
						{
							$this->is_restricted = 1;
							$this->ticket_type = "ticket_admin_una";	
						}
					} else {
						if (LIMETICKET_Permission::auth("limeticket.ticket_admin_other.restrict", "com_limeticket.support_admin", $cur_user))
						{
							$this->is_restricted = 1;
							$this->ticket_type = "ticket_admin_other";	
						}
					}
				}				
			}
		}
		
		return $this->is_restricted;
	}
	
	function can_Reply()
	{
		if (!$this->is_Restricted()) return true;
		return LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.reply", "com_limeticket.support_admin", JFactory::getUser()->id);
	}
	
	function can_Forward()
	{
		if (!$this->is_Restricted()) return true;
		return 	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.forward", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
	
	function can_ChangeUser()
	{
		if (!$this->is_Restricted()) return true;
		return 	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.changeuser", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
	
	function can_EditFields()
	{
		if (!$this->is_Restricted()) return true;
		return 	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.editfields", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
	
	function can_EditMisc()
	{
		if (!$this->is_Restricted()) return true;
		return 	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.editmisc", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
	
	function can_EditTicket()
	{
		if (!$this->is_Restricted()) return true;
		return	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.editticket", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
	
	function can_ChangeTicket()
	{
		if (isset($this->print) && $this->print)
			return false;
		
		if ($this->ticket->isLocked())
			return false;
		
		if ($this->ticket->merged > 0)
			return false;
		
		if (isset($this->email_preview) && $this->email_preview)
			return false;
		
		return true;
	}
	
	function can_Close()
	{
		if (!$this->is_Restricted()) return true;
		return 	LIMETICKET_Permission::auth("limeticket.{$this->ticket_type}.close", "com_limeticket.support_admin", JFactory::getUser()->id);
	}	
}
