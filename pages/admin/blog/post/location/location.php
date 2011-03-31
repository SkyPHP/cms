<?
$blog_article = aql::profile('blog_article',IDE);
if($blog_article['venue_id']){
	$venue = aql::profile('venue',$blog_article['venue_ide']);
}
?>
<div class = "has-floats">
	<div id = "venue_search" class = "float-left">
		<div>
			<label for = "tag_name">Enter Venue Name</label>
		</div>
		<div class = "has-floats" id = "new_venue">
			<input AUTOCOMPLETE = "off" class = "float-left" type = "text" id = "venue_name" />
		</div>
	</div>
	<div id = "venue_profile" class = "float-left">
<?
if($venue){
	include(INCPATH.'/../ajax/view-venue.php');
}
?>
	</div>
	<div class = "clear"></div>
</div>

