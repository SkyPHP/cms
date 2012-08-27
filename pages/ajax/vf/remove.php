<?php

// delete media items_id

$items_id = $_POST['items_id'];

if (!$items_id) {
    exit_json(array(
        'status' => 'Error',
        'errors' => array(
            'Invalid ID'
        )
    ));
}

$re = vf::removeItem($items_id);

exit_json(array(
    'status' => 'OK',
    'data' => $re
));
