<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LimeticketsControllerEmail extends LimeticketsController
{

	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'apply', 'save' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_limeticket&view=limetickets';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'email' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function reset()
	{
		$email_id = LIMETICKET_Input::GetInt("id");
		$tmpl = LIMETICKET_Input::GetCmd("tmpl");
		
		$datafile = JPATH_COMPONENT.DS.'data_limeticket.xml';
		echo "File : $datafile<br>";
		
		$db = JFactory::getDBO();
		
		$xml = simplexml_load_file($datafile);
		
		foreach ($xml->table as $table)
		{
			if ((string)$table->attributes()->name != "jos_limeticket_emails") continue;
			
			foreach ($table->rows->row as $row)
			{
				if ((string)$row->tmpl != $tmpl) continue;
				
				$body = (string)$row->body;
				$subject = (string)$row->subject;
				
				$sql = "UPDATE #__limeticket_emails SET subject = '" . $db->escape($subject) . "', body = '" . $db->escape($body) . "', ishtml = " . (int)$row->ishtml;
				$sql .= ", translation = '' WHERE tmpl = '" . $db->escape($tmpl) . "'";
				$db->setQuery($sql);
				$db->Query();
			}
		}
		
		$link = "index.php?option=com_limeticket&controller=email&task=edit&cid[]=" . $email_id;
		$this->setRedirect($link, "Tempalte reset");
	}

	function save()
	{
		$model = $this->getModel('email');

        $post = JRequest::get('post');
        
		if ($post['ishtml'])
		{
			$post['body'] = JRequest::getVar('body_html', '', 'post', 'string', JREQUEST_ALLOWRAW);	
			unset($post['body_html']);	
		}
		$post['translation'] = JRequest::getVar('translation', '', 'post', 'string', JREQUEST_ALLOWRAW);	

		if ($model->store($post)) {
			$msg = JText::_("EMAIL_TEMPLATE_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_EMAIL_TEMPLATE");
		}

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_limeticket&controller=email&task=edit&cid[]=" . $post['id'];
		} else {
			$link = 'index.php?option=com_limeticket&view=emails';
		}
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_limeticket&view=emails', $msg );
	}

}



