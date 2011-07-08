<?
	$p->title = "Website Setup";
	$p->template('skybox','top');
?>
	<h2><?=$_SERVER['SERVER_NAME']?></h2>
	<br>
	<label class="label" for="website_name">Name the Website</label><br>
	<input type="text" id="website_name" size="40">
    <br><br>
    <button id="add-website">Add Website</button>
<?
	$p->template('skybox','bottom');
?>
<script type="text/javascript">
	$(function(){
		$('#add-website').live('click',function() {
			if ($('#website_name').val()) {
			 	$.post('/admin/seo/website/ajax/insert-website', { website_name: $('#website_name').val() }, function (data) {
					$('#skybox').html(data)
				})
			}
		})	
	})
</script>