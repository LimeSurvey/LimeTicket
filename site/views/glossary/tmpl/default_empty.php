<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageStyle(); ?>
<?php echo LIMETICKET_Helper::PageTitle("GLOSSARY"); ?>

<?php echo JText::_('GLOSSARY_EMPTY'); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>