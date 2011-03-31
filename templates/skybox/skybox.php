<?
if ( $template_area == 'top' ) {
?>
<div id="skybox_template" class="has-floats" style="border: 2px #333 solid; padding: 15px;">
	<div id="skybox_template_title" style="float:left; font-weight:bold;">
		<?=$this->title?>
	</div>
	<div id="skybox_template_close" style="float:right;">
		<a href="javascript:history.back()"><img src="/images/close-x.gif"></a>
	</div>
	<div id="skybox_template_title_clear" style="clear:both; height: 15px;"></div>
<?
} else if ( $template_area == 'bottom' ) {
?>
</div>
<?
}
?>