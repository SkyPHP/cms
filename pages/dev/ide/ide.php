<?php

global $db, $dbw;

ob_start();
$p->title = 'Developer Dashboard / IDE';
$p->template('intranet','top');

$link = function($table, $id) {
    return sprintf('/dev/ide/%s/%s', $table, $id);
};

include 'pages/dev/dev-nav.php';

?>

    <div id="idePage">

<?php

    if ($this->ide) {

        if ($this->queryfolders[0] && $this->queryfolders[1] && $this->queryfolders[2]) {

            list($tablename, $fieldname, $value) = $this->queryfolders;
            $value = (is_numeric($value)) ? $value : "'{$value}'";

            $aql =  "
                        $tablename {
                            id
                            where $fieldname = $value
                            ORDER BY id ASC
                        }
                    ";

            $records = aql::select($aql);

            if ($records) {

                if (count($records) == 1) {
                    header("Location: {$link($tablename, $records[0]['id'])}");
                }

                foreach ($records as $r) {
?>
                    <p>
                        <a  href="<?=$link($tablename, $r['id'])?>"
                            style="color:blue"
                            >
                            ID: <?=$r['id']?>
                        </a>
                    </p>
<?php
                }

            } else {
                echo 'No Records!';
                exit;
            }
        } else if ($this->queryfolders[0] && $this->queryfolders[1]) {
            list($tablename, $id) = $this->queryfolders;
            if (!is_numeric($id)) exit('BAD ID');
        } else {
            $ide = $this->ide;
            $id = decrypt($ide);
            if (!is_numeric($id)) {

                $sql = 'SELECT tablename
                        FROM pg_tables
                        WHERE schemaname = \'public\'
                        ORDER BY tablename ASC';

                $tables = sql_array($sql);
                foreach ($tables as $table) {
                    $tablename = $table['tablename'];
                    $id = decrypt($ide, $tablename);
                    if (is_numeric($id)) break;
                }

            }

            if (!is_numeric($id)) {
                exit('BAD IDE');
            }

        }

        $this->title = "$tablename: $id";

        $aql =  "$tablename { * }";
        $aql_data = aql::profile($aql, $id);

        if ($aql_data) {
?>
            <p id="instruction" style="font-size: 2em; font-family: Arial, Helveitca, Sans-serif; color: Blue; margin-bottom: 5px;">
                <span style="color: Crimson; font-weight: bold;">Crtl+Click</span>
                on a field you want to edit...
                <small style="color:red; font-size:0.9em;">Be careful with those that have HTML content!</small>
            </p>

<?php

            print_a((array)$aql_data);
            elapsed('before getting cols');

            $column_name = $tablename . "_id";

            $sql = "SELECT
                        a.table_name
                    FROM information_schema.columns a,
                        information_schema.columns b,
                        information_schema.columns c
                    WHERE a.table_name = b.table_name
                        and a.table_name = c.table_name
                        and b.column_name = 'id'
                        and c.column_name = 'active'
                        and a.column_name = '{$column_name}'";

            $cols = sql_array($sql);
            if ($cols) {
                echo '<hr />';
                foreach ($cols as $col) {
                    $count = aql::count("{$col['table_name']} { where {$column_name} = '{$id}' }");
                    if ($count) {
?>
                    <p>
                        <a  href="/dev/ide/<?=$col['table_name']?>/<?=$column_name?>/<?=$id?>"
                            class="ide"
                            >
                            <?=$col['table_name']?> (<?=$count?>)
                        </a>
                    </p>
<?
                    }
                }
            }

            elapsed('after foreach cols');
        } else {
            echo 'No Data';
        }
    } else {

?>
        <br /><br />
        <div id="goIde" style="text-align: center;">
            <input type="text" id="ide" name="ide" style="font-size: 2em;"/>
            <input type="button" id="ideGo" value="Go &raquo;" onclick="goIde(this)" style="font-size: 2em;"/>
        </div>
<?php

    }

?>

</div>

<?php

$this->template('intranet', 'bottom');

ob_end_flush();
