<?
	$p->title = "Paragraph Splitter";
	$p->template('seo','top');
?>
<div style="margin: 15px;"><a href="/admin/seo/duplicate-data/" >Phrase Manager</a> | <a href="/admin/seo/duplicate-data/phrases/">Phrase Listing</a></div>
<h1><?=$p->title?></h1>
<div style="width:1000px">
	<div class="has-floats" style="margin-bottom:5px;">
    	<div style="float:left; margin-right:15px;">Name: <input type="text" id="name" /></div>
        <div style="float:left;">Source: <input type="text" id="source" /></div>
    </div>
    <div class="hideable"><textarea id="paragraph" style="width:1000px; height: 100px;"></textarea></div>
    <div class="has-floats">
        <div class="hideable" style="float:left"><input type="button" value="Split" style="display:none;" id="split" /></div>
        <div style="float:right">
            <a id="hide-or-show" style="cursor:pointer;" do="hide">HIDE ORIGINAL -</a>
        </div>
    </div>
</div>
<div id="results"></div>
<?
	$p->template('seo','bottom');
?>