<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'settings.php');


class LimeticketsViewTranslate extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->data = json_decode(JRequest::getVar('data'), true);
		$this->langs = JLanguage::getKnownLanguages();
		
		parent::display($tpl);	
	}
}