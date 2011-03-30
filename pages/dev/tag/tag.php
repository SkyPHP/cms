<?

$title = "Tag A New Version";
template::inc('intranet','top');

include(INCPATH.'/../dev-nav.php');

?>
<div>
    <h2>This website uses the following codebases:</h2>
</div>
<table class="listing">
	<tr>
		<th>Codebase</th>
        <th>Latest Version</th>
        <th>Tag New Version</th>
	</tr>
<?
    $codebases = get_codebases();
    foreach ( $codebases as $cb ) {

        $codebase_name = $cb['codebase'];
        $database = $schemas[$codebase_name];

?>
	<tr>
		<td><?=$codebase_name?></td>
		<td>
<?
            echo get_latest_version($codebase_name);
?>
        </td>
		<td>
<?
            if ($cb['release']=='stable') echo 'n/a';
            else {
?>
            <form action="">
                <input type="hidden" name="codebase" value="<?=$codebase_name?>" />
                <input type="text" name="version" />
                <input type="button" value="Tag New Version" onclick="tag_new_version(this.form);" />
            </form>
<?
            }
?>
        </td>
	</tr>
<?
    }//foreach
?>
</table>
<pre id="output"></pre>
<img style="display:none;" src="/images/loading3.gif"/>
<?
template::inc('intranet','bottom');
?>