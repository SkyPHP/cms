<?
	$p->title = "Paragraph Splitter";
	$p->template('seo','top');
?>
<h1><?=$p->title?></h1>
<div class="hideable"><textarea id="paragraph" style="width:1000px; height: 100px;"></textarea></div>
<div class="has-floats">
	<div class="hideable"><input type="button" value="Split" id="split" /></div>
    <div style="font-size:10px" id="hide-show">HIDE -</div>
</div>
<div id="results"></div>
<?
	$p->template('seo','bottom');
?>