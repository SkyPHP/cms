<?php

if (!$_POST['sql'] || !$_POST['db_name']) {

    exit_json(array(
        'error' => "Missing required POST values 'sql' and/or 'db_name'."
    ));
}

$needle = '/cms/';
$end = strrpos(__FILE__, $needle) + strlen($needle);
$prefix =  substr(__FILE__, 0, $end);
$jar_path = $prefix . 'lib/db/apgdiff-2.3.jar';

\Cms\Apgdiff::$jar_path = $jar_path;

$sql = \Cms\Apgdiff::getDump();
$db_name = \Cms\Apgdiff::getDatabaseName();

$pull = \Cms\Apgdiff::getUpgradeScript($_POST['sql'], $sql);
$push = \Cms\Apgdiff::getUpgradeScript($sql, $_POST['sql']);



$data = array(

    'local_database' => $_POST['db_name'],
    'remote_database' => $db_name,
    'tabs' => array(
        array(
            'tab' => 'Pull',
            'tab_caps' => 'PULL',
            'left_arrow' => true,
            'create' => $pull_filtered,
            'create_drop' => $pull
        ),
        array(
            'tab' => 'Push',
            'tab_caps' => 'PUSH',
            'right_arrow' => true,
            'create' => $push_filtered,
            'create_drop' => $push
        )
    )
);

exit_json($data);
