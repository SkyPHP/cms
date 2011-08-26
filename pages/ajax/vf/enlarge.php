<?

$items_id = decrypt(IDE);

$item = vf::getItem($items_id, array(
	'width' => 800
));

?>
<div class="vf-enlarge-skybox-container">
	<div>
		<a href="javascript:history.back()">Click Here To Close</a>
	</div>
	<div>
		<a href="javascript:history.back()" class="vf-enlarge-skybox">
			<?=$item->html?>
		</a>
	</div>
</div>