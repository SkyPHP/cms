<?
	$p->title = "Paragraph Splitter";
	$p->template('seo','top');
?>
<h1><?=$p->title?></h1>
<textarea id="paragraph" style="width:90%; height: 100px;"></textarea><br>
<input type="button" value="Split" id="split" />
<div id="results"></div>
<?
	$p->template('seo','bottom');
?>