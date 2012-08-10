<?php

global $codebase_path_arr;

$find_ini = function($path) {
    $p = $path . 'version.ini';
    if (file_exists($p)) {
        return parse_ini_file($p, true);
    }
};

$data = array();
$all = array();
foreach ($codebase_path_arr as $p) {

    $ini = $find_ini($p, true);
    $r = array(
        'path' => $p
    );

    if (!$ini) {
        $data[] = $r;
        continue;
    }

    $n = $ini['codebase'];
    $name = reset(array_keys($n));

    $r = array_merge($r, array(
        'found' => array(
            'name' => $name,
            'path' => $p,
            'version' => $n[$name],
            'requires' => call_user_func(function($rs) {

                if (!$rs) {
                    return array();
                }

                $list = array();
                foreach ($rs as $name => $version) {
                    $list[] = array(
                        'name' => $name,
                        'version' => $version
                    );
                }

                return array(
                    'list' => $list
                );

            }, $ini['requires']),
            'status' => array(
                'class' => 'ok',
                'text' => 'OK'
            )
        ),

    ));

    $all[$name][] = $n[$name];
    $data[] = $r;
}


foreach ($data as $k => $base) {
    if (!$base['found']) {
        continue;
    }

    $requires = $base['found']['requires'];
    if (!$requires) {
        continue;
    }

    foreach ($requires['list'] as $b) {

        if (!$all[$b['name']]) {
            $data[$k]['found']['status']['class'] = 'error';
            $data[$k]['found']['status']['text'] = $b['name'].' not found.';
            break 2;
        }

        $found = $all[$b['name']];
        foreach ($found as $version) {
            if (version_compare($b['version'], $version) > 0) {
                $data[$k]['found']['status']['class'] = 'error';
                $data[$k]['found']['status']['text'] = $b['name'].' '.$b['version'].' not found.';
                break 2;
            }
        }


    }

}

$this->template('intranet', 'top', array(
    'title' => 'Version'
));

echo $this->mustache('version.m', array('codebases' => $data), $this->incpath);

$this->template('intranet', 'bottom');
