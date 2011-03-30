<?
//if ($_GET['value'] && !is_numeric($_GET['value'])) redirect("/dev/ide/$_GET[value]");
$title = 'Developer Dashboard';
template::inc('intranet','top');
include('dev-nav.php');
?>


<form method="get">
value: <input type="text" name="value" value="<?=$_GET['value']?>" />
key: <input type="text" name="key" value="<?=$_GET['key']?>" />
<input type="submit" value="submit" />
</form>
<hr />
decrypt: <?=decrypt($_GET['value'],$_GET['key'])?><br />
<br />
encrypt: <a href="/dev/ide/<?=encrypt($_GET['value'],$_GET['key'])?>"><?=encrypt($_GET['value'],$_GET['key'])?></a>


<?
template::inc('intranet','bottom');
?>