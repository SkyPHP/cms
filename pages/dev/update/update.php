<?

function get_revision($svn_info) {
    $needle = 'Revision: ';
    $start = strpos($svn_info,$needle) + strlen($needle);
    $end = strpos($svn_info,"\n",$start);
    $length = $end - $start;
    return trim(substr($svn_info,$start,$length));
}




$r = sql("SELECT pg_catalog.shobj_description(d.oid, 'pg_database') as description
            FROM pg_catalog.pg_database d
            WHERE d.datname = '{$db_name}';");
$schemas = json_decode( $r->Fields('description'), true );
//print_a($schemas);

$title = "Software Update";
template::inc('intranet','top');

include(INCPATH.'/../dev-nav.php');

?>
<div>
    <h2>This website uses the following codebases:</h2>
</div>
<table class="listing">
	<tr>
		<th>Codebase</th>
        <th>Code Version</th>
        <th>DB Version</th>
        <th></th>
        <th></th>
	</tr>
<?
    $codebases = get_codebases();
    foreach ( $codebases as $cb ) {

        $codebase_name = $cb['codebase'];
        $database = $schemas[$codebase_name];

        $latest_version = get_latest_version($codebase_name);

?>
	<tr>
		<td><?=$codebase_name?></td>
		<td>
<?
            if ($cb['release']=='stable') echo $cb['version'];
            else echo 'Trunk ' . $cb['version'] . '+';
?>
        </td>
		<td>
<?
            if ($database['version']) {
                if ($database['trunk']) echo 'Trunk ' . $database['version'] . '+<br />' . $database['trunk'];
                else echo $database['version'];
            }
?>
        </td>
		<td>
            <div>
<?
            if ( version_value($latest_version) <= version_value($database['version']) && version_value($latest_version) <= version_value($cb['version']) ) {
?>
                <img src="/images/success.gif" />
<?
                echo "Version <b>$latest_version</b><br />already installed.";
            
            } else {
?>
                <div style="font-weight:bold;">Update available:</div>
                <input onclick="update('<?=$codebase_name?>','stable',this)" type="button" value="Stable <?=$latest_version?>" />
<?
             }
?>
            </div>
        </td>
        <td>
            <div>Developer update:</div>
            <input onclick="update('<?=$codebase_name?>','trunk',this)" type="button" value="Trunk <?=$latest_version?>+" />
        </td>
	</tr>
<?
    }//foreach
?>
</table>
<pre id="output"></pre>
<?
template::inc('intranet','bottom');
?>