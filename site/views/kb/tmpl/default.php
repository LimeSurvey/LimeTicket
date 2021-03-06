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
<?php echo LIMETICKET_Helper::PageTitle("KNOWLEDGE_BASE"); ?>

<?php if ($this->main_show_search) : ?>
	<form id="searchProd" action="<?php echo LIMETICKETRoute::_( $this->base_url );// FIX LINK?>" method="post" name="limeticketForm">
		<div class="input-append">	
			<input id='kb_search' type='text' name='kbsearch' value="<?php echo LIMETICKET_Helper::escape($this->search); ?>" placeholder='<?php echo JText::_("SEARCH_KNOWLEDGE_BASE_ARTICLES"); ?>'>
			<input id='kb_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='art_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>
	</form>
<?php endif; ?>
			
<?php if ($this->main_prod_search): ?>
	<form id="searchProd" action="<?php echo LIMETICKETRoute::_( $this->base_url);?>" method="post" name="limeticketForm">
		<input type=hidden name='searchtype' value='products' />
		<input type="hidden" name="limitstart" id='limitstart' value="0">
		<input type="hidden" name="limit" id='limit' value="<?php echo (int)$this->limit; ?>">
		<div class='input-append'>
			<input id='prodsearch' type='text' name='prodsearch' value="<?php echo LIMETICKET_Helper::escape($this->search); ?>" placeholder="<?php echo JText::_("SEARCH_FOR_A_PRODUCT"); ?>">
			<input id='prod_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='prod_reset'  class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>
	</form>		
<?php endif; ?>


<?php if (LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>
<?php endif; ?>

<?php if ($this->main_show_prod && $this->main_show_cat && $this->main_show_sidebyside): ?>

	<table cellpadding="0" cellspacing="0" width="100%" class='limeticket_sidebyside'>
		<tr>
			<td width="50%" valign="top">
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod_list.php'); ?>
			</td>
			<td class='limeticket_sidebyside_sep'>
			</td>
			<td width="50%" valign="top">
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat_list.php'); ?>
			</td>
		</tr>
	</table>
	
<?php else: ?>

	<?php if ($this->main_show_prod): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod_list.php'); ?>
	<?php endif; ?>	
	
	<?php if ($this->main_show_cat): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat_list.php'); ?>
	<?php endif; ?>
	
<?php endif; ?>

<?php if (count($this->arts) > 0): ?>
	<?php foreach ($this->arts as &$art) : ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php'); ?>
	<?php endforeach; ?>	
<?php endif; ?>

<?php if (!LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
