<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.folder' );


class LimeticketsViewFaqcat extends JViewLegacy
{

	function display($tpl = null)
	{
		$faqcat		= $this->get('Data');
		$isNew		= ($faqcat->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("FAQ_CATEGORY").': <small><small>[ ' . $text.' ]</small></small>', 'limeticket_categories' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		LIMETICKETAdminHelper::DoSubToolbar();
		
		$path = JPATH_SITE.DS.'images'.DS.'limeticket'.DS.'faqcats';
		
		if (!file_exists($path))
			mkdir($path,0777,true);
			
		$files = JFolder::files($path,'(.png$|.jpg$|.jpeg$|.gif$)');
		
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
		
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'image', 'class="inputbox" size="1" ', 'id', 'title', $faqcat->image);

		$this->lists = $lists;
		
		$this->faqcat = $faqcat;

		parent::display($tpl);
	}
}


