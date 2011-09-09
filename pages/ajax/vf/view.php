<?

// view item

$i = vf::getItem(IDE);

if ($i->errors) {
	redirect('/404', 302);
}

redirect($i->src, 302);