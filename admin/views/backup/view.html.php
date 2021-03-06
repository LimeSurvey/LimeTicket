<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'updatedb.php');

class LimeticketsViewBackup extends JViewLegacy
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("ADMINISTRATION"), 'limeticket_admin' );
        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();
		
		$this->log = "";
		
		$task = JRequest::getVar('task');
		$updater = new LIMETICKETUpdater();
			
		if ($task == "saveapi")
		{
			return $this->SaveAPI();
				
		}
		if ($task == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=limetickets',false);
			$mainframe->redirect($link);
			return;			
		}
		if ($task == "update")
		{
			$this->log = $updater->Process();
			parent::display();
			return;
		}
				
		if ($task == "backup")
		{
			$this->log = $updater->BackupData('limeticket');
		}
		
		if ($task == "restore")
		{
			// process any new file uploaded
			$file = JRequest::getVar('filedata', '', 'FILES', 'array');
			if (array_key_exists('error',$file) && $file['error'] == 0)
			{
				$data = file_get_contents($file['tmp_name']);
				$data = unserialize($data);
				
				global $log;
				$log = "";
				$log = $updater->RestoreData($data);
				$this->log = $log;
				parent::display();
				return;
			}
			
		}
		
        parent::display($tpl);
    }
	
	function SaveAPI()
	{
		$username = JRequest::getVar('username');
		$apikey = JRequest::getVar('apikey');

		$db = JFactory::getDBO();
		
		$qry = "REPLACE INTO #__limeticket_settings (setting, value) VALUES ('fsj_username','".LIMETICKETJ3Helper::getEscaped($db, $username)."')";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "REPLACE INTO #__limeticket_settings (setting, value) VALUES ('fsj_apikey','".LIMETICKETJ3Helper::getEscaped($db, $apikey)."')";
		$db->setQuery($qry);
		$db->Query();
		
		// update url links
		$updater = new LIMETICKETUpdater();
		$updater->SortAPIKey($username, $apikey);
		
		$mainframe = JFactory::getApplication();
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=backup',false);
		$mainframe->redirect($link);
	}
}
