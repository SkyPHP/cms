<?
global $db;

ob_start();
$p->title = 'Developer Dashboard / IDE';
$p->template('intranet','top');
include('pages/dev/dev-nav.php');
?>
<div id="idePage">
	<?
	if (IDE) {
		if ($p->queryfolders[0] && $p->queryfolders[1] && $p->queryfolders[2]) {
			$tablename = $p->queryfolders[0];
			$fieldname = $p->queryfolders[1];
			$value = $p->queryfolders[2];
			if (!is_numeric($value)) $value = "'$value'";
			
			$rs_records = aql::select("$tablename {id where $fieldname = $value ORDER BY id ASC}");
			if(!is_array($rs_records) && is_numeric($value)) {
				$value = "'$value'";
				$rs_records = aql::select("$tablename {id where $fieldname = $value ORDER BY id ASC}");
			}
			
			if (is_array($rs_records)) {
				if (count($rs_records) == 1) header("Location: /dev/ide/$tablename/". $rs_records[0]['id']);
				foreach ($rs_records as $rs_record) echo "<a style='color:blue' href='/dev/ide/$tablename/$rs_record[id]'>ID: $rs_record[id]</a><br />";
			}
			else echo "No Records!";
			exit();
		}
		else if ($p->queryfolders[0] && $p->queryfolders[1]) {
			$tablename = $p->queryfolders[0];
			$id = $p->queryfolders[1];
			if (!is_numeric($id)) exit("BAD ID");
		}
		else {
			$ide = IDE;
			$id = decrypt($ide);
			if (!is_numeric($id)) {
				$sql_tables = "select tablename from pg_tables where schemaname = 'public' order by tablename asc";
				$rs_tables = $db->Execute($sql_tables) or die("$sql_tables<br>" . $db->ErrorMsg());
				if (!$rs_tables->EOF) $rs_tables = $rs_tables->GetArray();
				
				if (is_array($rs_tables)) foreach ($rs_tables as $rs_table) {
					$tablename = $rs_table["tablename"];
					$id = decrypt($ide,$tablename);
					if (is_numeric($id)) break;
				}
			}
			
			if (!is_numeric($id)) exit("BAD IDE");
		}
			
		$title = "$tablename: $id";
		
		$aql_data = aql::select("$tablename {* where id = $id limit 1}");
		if (is_array($aql_data)) {
			?>
			<p id="instruction" style="font-size: 1.5em; font-family: Comic Sans MS; color: Blue; margin-bottom: 5px;"><span style="color: Crimson; font-weight: bold;">Crtl+Click</span> on a field you want to edit... <small style="color:red; font-size:0.9em;">Be careful with those that have HTML content!</small></p>
			<?
		
			print_a($aql_data[0]);
			
			$column_name = $tablename . "_id";
			$sql_columns = "select a.table_name from information_schema.columns a,information_schema.columns b,information_schema.columns c where a.table_name=b.table_name and a.table_name=c.table_name and b.column_name = 'id' and c.column_name = 'active' and a.column_name = '$column_name';";
			$rs_columns = $db->Execute($sql_columns) or die("$sql_columns<br>" . $db->ErrorMsg());
			if (!$rs_columns->EOF) {
				$rs_columns = $rs_columns->GetArray();
				echo "<hr />";
				foreach ($rs_columns as $rs_column) {
					$rs_count = aql::select("$rs_column[table_name] {count(id) where $column_name = '$id'}");
					if (is_array($rs_count) && $rs_count[0]["count"]) echo "<a class='ide' href='/dev/ide/$rs_column[table_name]/$column_name/$id'>$rs_column[table_name] (" . $rs_count[0]["count"] . ")</a><br />";
				}
			}
		}
		else echo "No Data";
	}
	else {
		?>
		<br /><br />
		<div id="goIde" style="text-align: center;">
			<input type="text" id="ide" name="ide" style="font-size: 2em;"/>
			<input type="button" id="ideGo" value="Go &raquo;" onclick="goIde(this)" style="font-size: 2em;"/>
		</div>
		<?
	}
	?>
</div>
<?
$p->template('intranet', 'bottom');
ob_end_flush();