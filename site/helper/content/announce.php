<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/*
Generic editor and list class

List:
	Fields to list
	Main Field for link
	always has published
	always has author
	sometimes has ordering
	sometimes has added date
	sometimes has modifed date
	
	optional lookup fields for category
	
	optional split fields based on page break (annoucne + faq)
	
	*/

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'content.php');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'adminhelper.php');

class LIMETICKET_ContentEdit_Announce extends LIMETICKET_ContentEdit
{
	function __construct()
	{
		$this->id = "announce";
		$this->descs = JText::_("Announcements");
		
		$this->table = "#__limeticket_announce";
		$this->has_added = 1;
		
		$this->fields = array();

		$field = new LIMETICKET_Content_Field("title",JText::_("TITLE"));
		$field->link = 1;
		$field->search = 1;
		$this->AddField($field);

		$field = new LIMETICKET_Content_Field("subtitle",JText::_("DESCRIPTION_FOR_MODULE"));
		$field->search = 1;
		$field->required = 0;
		$this->AddField($field);

		$field = new LIMETICKET_Content_Field("featured",JText::_("Featured"));
		$this->AddField($field);
		
		if (empty(LIMETICKETAdminHelper::$langs))
		{
			LIMETICKETAdminHelper::LoadLanguages();
			LIMETICKETAdminHelper::LoadAccessLevels();
		}

		$filter_langs = array();
		$filter_access = array();
			
		$field = new LIMETICKET_Content_Field("language",JText::_("LANGUAGE"),"lookup","lang_art");
		$field->lookup_required = 1;
		$field->lookup_id = "id";
		$field->lookup_title = "title";
		foreach (LIMETICKETAdminHelper::$langs as $lang)
		{
			$filter_langs[$lang->value] = $lang->text;
			$field->lookup_extra[$lang->value] = $lang->text;
		}
		if (!LIMETICKET_Helper::langEnabled())
			$field->hide = 1;
		$this->AddField($field);

		$field = new LIMETICKET_Content_Field("access",JText::_("Access"),"lookup");
		$field->lookup_required = 1;
		$field->lookup_id = "id";
		$field->lookup_title = "title";
		$field->default = 1;
		foreach (LIMETICKETAdminHelper::$access_levels as $lang)
		{
			$filter_access[$lang->value] = $lang->text;
			$field->lookup_extra[$lang->value] = $lang->text;
		}
		$this->AddField($field);
		
		$field = new LIMETICKET_Content_Field("body",JText::_("ARTICLE"),"text");
		$field->show_pagebreak = 1;
		$field->more = "fulltext";
		$this->AddField($field);
		
		$this->list = array();
		$this->list[] = "title";
		if (LIMETICKET_Helper::langEnabled())
			$this->list[] = "language";
		$this->list[] = "access";
		
		$this->edit = array();
		$this->edit[] = "title";
		$this->edit[] = "subtitle";
		$this->edit[] = "language";
		$this->edit[] = "access";

		$this->edit[] = "body";
				
		$this->order = "added DESC";
		
		$this->link = "index.php?option=com_limeticket&view=announce&announceid=%ID%";
		
		$this->list_added = 1;
		
		if (LIMETICKET_Helper::langEnabled())
		{
			$filter = new LIMETICKET_Content_Filter("language","id","title","","","SELECT_LANGUAGE", "lang_filter", $filter_langs);
			$this->AddFilter($filter);
		}
			
		$filter = new LIMETICKET_Content_Filter("access","id","title","","","SELECT_ACCESS", "", $filter_access);
		$this->AddFilter($filter);
	}
}