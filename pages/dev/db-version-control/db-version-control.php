<?
	template::inc('global','top');
?>
<div id = "page_content">
	<h2>Database Version Control</h2>
	<div id="functions">
		<span>Functions:</span>
		<span class = 'likelink' onclick = "paste('add_colulmn')">Add Column</span> | 
		<span class = 'likelink' onclick = "paste('alter_column')">Alter Column</span> |
		<span class = 'likelink' onclick = "paste('create_table')">Create Table</span> |
		<span class = 'likelink' onclick = "paste('clear')">Clear</span>
	</div>
	<div>
		<div id = "db-info" class = 'col'>
			<div>dev db</div>
			<div>info:</div>
			<hr>
			<div>host: <pre>skyphp.org</pre></div>
			<div>db name: <pre>dev_db</pre></div>
		</div>
		<div id = "sql" class = 'col'>
			<h3>SQL:</h3>
			<textarea cols="100" rows="20" id="sql_statement"></textarea>
		</div>
<?
	$code_aql = "sky_codebase {
					name as codebase
				}";
	$code_p = array(
						'select_name' => 'codebase',
						'value_field' => 'codebase',
						'option_field' => 'codebase',
						'null_option' => '- codebase -',
						'selected_value' => ''
					);

?>
		<div id = "options" class = 'col'>
			<div>Codebase:</div>
			<div><? aql::dd($code_aql,$code_p); ?></div>
			<div><input name = "execute" type = "button" onclick = "execute()" value = "Execute" id = "execute" /></div>
			<div id = "output"></div>
		</div>
	</div>
	<div id = "previous-runs">
<?
	#require_once('/pages/dev/db-version-control/ajax/previous-runs.php');
?>	
	</div>
	<div class = "clear"></div>
</div>
<?
	template::inc('global','bottom');
?>