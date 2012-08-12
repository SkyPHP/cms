<?php

if (!$_POST['sql'] || !$_POST['db_name']) {

    exit_json(array(
        'error' => "Missing required POST values 'sql' and/or 'db_name'."
    ));
}

$needle = '/pages/';
$end = strrpos(__FILE__, $needle);
$prefix =  substr(__FILE__, 0, $end);
$jar_path = $prefix . '/lib/db/apgdiff-2.3.jar';

\Cms\Apgdiff::$jar_path = $jar_path;

$sql = \Cms\Apgdiff::getDump();
$db_name = \Cms\Apgdiff::getDatabaseName();

$pull = \Cms\Apgdiff::getUpgradeScript($_POST['sql'], $sql);
$pull_filtered = \Cms\Apgdiff::stripDrops($pull);

$push = \Cms\Apgdiff::getUpgradeScript($sql, $_POST['sql']);
$push_filtered = \Cms\Apgdiff::stripDrops($push);


$data = array(

    'local_database' => $_POST['db_name'],
    'remote_database' => $db_name,
    'tabs' => array(
        array(
            'tab' => 'Pull',
            'tab_caps' => 'PULL',
            'left_arrow' => true,
            'left_sql' => $pull_filtered,
            'right_sql' => $pull
        ),
        array(
            'tab' => 'Push',
            'tab_caps' => 'PUSH',
            'right_arrow' => true,
            'left_sql' => $push_filtered,
            'right_sql' => $push
        ),
        array(
            'tab' => 'Dump',
            'tab_caps' => 'DUMP',
            'dump' => true,
            'left_sql' => $_POST['sql'],
            'right_sql' => $sql
        )

    )
);

json_headers();
exit(json_beautify(json_encode($data)));
