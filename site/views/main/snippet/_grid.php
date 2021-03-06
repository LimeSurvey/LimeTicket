<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if ($item['icon'] && $this->hideicons == 0): ?>
	<p>
		<a href='<?php echo $this->getLink($item); ?>' target='<?php echo $item['target']; ?>'>
		<?php if (file_exists(JPATH_SITE.DS.'images'.DS.'limeticket'.DS.'menu'.DS.$item['icon'])) : ?>
			<img src='<?php echo JURI::base(); ?>images/limeticket/menu/<?php echo LIMETICKET_Helper::escape($item['icon']); ?>' width="<?php echo (int)$this->imagewidth; ?>" height="<?php echo (int)$this->imageheight; ?>" />
		<?php else: ?>
			<img src='<?php echo JURI::base(); ?>components/com_limeticket/assets/mainicons/<?php echo LIMETICKET_Helper::escape($item['icon']); ?>' width="<?php echo (int)$this->imagewidth; ?>" height="<?php echo (int)$this->imageheight; ?>" />
		<?php endif; ?>
		</a>
	</p>
<?php endif; ?>
<h4>
	<a href='<?php echo $this->getLink($item); ?>' target='<?php echo $item['target']; ?>'>
		<?php echo JText::_($item['title']); ?>
	</a>
</h4>
<?php if ($item['description'] && $this->show_desc): ?>
	<p>
		<?php 
			$lang = JFactory::getLanguage();
			if ($lang->hasKey($item['description']))
			{
				echo JText::_($item['description']); 
			} else {
				echo $item['description']; 
			}
		?>
	</p>
<?php endif; ?>
				