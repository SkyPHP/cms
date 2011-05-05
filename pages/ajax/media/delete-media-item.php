<?
$o = new model(IDE, 'media_item { }');
$arr = $o->delete();
json_headers();
exit(json_encode($arr));