<?php

// view item

$i = vf::getItem($this->ide);

$this->redirect(
    $i->errors ? '/404' : $i->http_url
);
