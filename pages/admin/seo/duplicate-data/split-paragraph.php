<?
	$p->title = "Paragraph Splitter";
	$p->template('seo','top');
?>
<h1><?=$p->title?></h1>
<div class="hideable"><textarea id="paragraph" style="width:1000px; height: 100px;"></textarea></div>
<div class="has-floats">
	<div class="hideable" style="float:left"><input type="button" value="Split" id="split" /></div>
    <div style="float:left">
    	<button class="hide" style="cursor:pointer; font-size:10px; border:none; background:none; margin:0; padding:0;">HIDE -</button>
    </div>
</div>
<div id="results"></div>
<?
	$p->template('seo','bottom');
?>