<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );


class LimeticketsViewGlossarys extends JViewLegacy
{
 
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("GLOSSARY_MANAGER"), 'limeticket_glossary' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $lists =  $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ispublished']);


		$this->lists = $lists;

        parent::display($tpl);
    }
}


