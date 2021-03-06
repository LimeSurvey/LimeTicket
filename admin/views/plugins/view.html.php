<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.utilities.date');
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_actions.php' );
require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'updatedb.php' );

class LimeticketsViewPlugins extends JViewLegacy
{
    function display($tpl = null)
    {
		// need to force BS tempalte here!
		LIMETICKET_Helper::BootstrapAdminForce();
		
		$layout = JRequest::getVar('layout');
		if ($layout == "configure")
			return $this->configure();

		JToolBarHelper::title( JText::_("Plugins"), 'limeticket_moderate' );
		JToolBarHelper::cancel();
		LIMETICKETAdminHelper::DoSubToolbar();

		$task = JRequest::getVar('task');
		
		if ($task == "enable")
			return $this->enable(1);
		
		if ($task == "disable")
			return $this->enable(0);

		$this->plugins = $this->LoadPlugins();

        parent::display($tpl);
    }
	
	function LoadPlugins()
	{
		$updater = new LIMETICKETUpdater();
		$updater->UpdatePlugins();

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__limeticket_plugins ORDER BY `type`, name";
		$db->setQuery($qry);
		return $db->loadObjectList();
	}
	
	function enable($enable = 1)
	{
		$type = JRequest::getVar('type');
		$name = JRequest::getVar('name');

		$db = JFactory::getDBO();
		$sql = "UPDATE #__limeticket_plugins SET enabled = " . $db->escape($enable) . " WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'";
		$db->setQuery($sql);
		$db->Query();

		if ($type == "cron")
		{
			$sql = "UPDATE #__limeticket_cron SET published = " . $db->escape($enable) . " WHERE class = 'plugin" .  $db->escape($name) . "'";
			$db->setQuery($sql);
			$db->Query();
		}

		$this->back_to_list();
	}

	function configure()
	{
		$db = JFactory::getDBO();

		$task = JRequest::getVar('task');
		$type = JRequest::getVar('type');
		$name = JRequest::getVar('name');

		if ($task == "cancel")
		{
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_limeticket&view=plugins', false));
		}

		if ($task == "apply" || $task == "save")
		{
			// save the data!
			$settings = LIMETICKET_Input::getArray("jform");
			$json = json_encode($settings);

			$sql = "UPDATE #__limeticket_plugins SET settings = '" . $db->escape($json) . "' WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'"; 
			$db->setQuery($sql);
			$db->Query();
		}

		$sql = "SELECT * FROM #__limeticket_plugins WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'";
		$db->setQuery($sql);
		$this->plugin = $db->loadObject();

		JToolBarHelper::title( JText::_("Configure Plugin") . " - " . $this->plugin->title, 'limeticket_plugin' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		LIMETICKETAdminHelper::DoSubToolbar();

		$settings = @json_decode($this->plugin->settings);

		$this->setLayout("configure");

		$this->form = new JForm('set', array('control' => 'jform'));
		$this->form->addFieldPath(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'models'.DS.'fields');
		$this->form->addFieldPath(JPATH_ROOT.DS.'components'.DS.'com_limeticket'.DS.'models'.DS.'field');
		$form_file = $this->plugin->settingsfile;
		$this->xml = JFactory::getXML($form_file, true);
		$this->form->load($this->xml, true);
		$this->form->bind($settings);

		parent::display();
	}
	
	function back_to_list()
	{
		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_limeticket&view=plugins', false));
	}
}



