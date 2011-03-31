<?
if(!$blog_article['venue_ide']){
	$blog_article = aql::profile('blog_article',IDE);
	$venue = aql::profile('venue',$blog_article['venue_ide']);
}
if($venue){
?>
<div id = "venue_info">
	<div id = "new_venue_name"><b><?=$venue['venue_name'] ?></b></div>
	<div><?=$venue['address1'] ?></div>
	<div><?=$venue['city'].', '.$venue['state'] ?></div>
	<div><?=$venue['phone'] ?></div>
	<div><?=$venue['website']?"<a target = '_blank' href = 'http://".$venue['website']."'>".$venue['website']."</a>":'' ?></div>
	<div><?=$venue['name_modifier'] ?></div>
	<div><?=$venue['Formerly']?'Formerly: '.$venue['name_former']:'' ?></div>
	<div><?=$venue['name_aka']?'AKA: '.$venue['name_aka']:'' ?></div>
	<div><input type = "button" value = "Remove" onclick = "remove_venue('<?=$venue['venue_ide'] ?>')"</div>
</div>
<?
}
?>