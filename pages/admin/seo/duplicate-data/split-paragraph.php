<?
	$p->title = "Paragraph Splitter";
	$p->template('seo','top');
?>
<h1><?=$p->title?></h1>
<div style="width:1000px">
    <div class="hideable"><textarea id="paragraph" style="width:1000px; height: 100px;"></textarea></div>
    <div class="has-floats">
        <div class="hideable" style="float:left"><input type="button" value="Split" id="split" /></div>
        <div style="float:right">
            <a id="hide-or-show" style="cursor:pointer;" do="hide">HIDE ORIGINAL -</a>
        </div>
    </div>
</div>
<div id="results"></div>
<?
	$p->template('seo','bottom');
?>