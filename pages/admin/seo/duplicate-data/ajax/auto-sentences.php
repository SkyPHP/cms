<?
	print_a($_POST);
	$no_sentences = $_POST['no_sentences'];
	$sentences=array();
	for ($x = 0; $x <= $no_sentences; $x++) {
		$sentences[] = $_POST['sentence'+$x];
	}
	if ($_POST['use_first']) { 
		$first = $sentences[0];
		$sentences = array_slice($sentences,1);
	}
	if ($_POST['limit']) {
		$limit = $_POST['limit'];
		if ($first) $limit--;
		$num = $limit;
		foreach($sentences as $key => $sentence) {
			if ($key >= $limit) unset($sentences[$key]);	
		}
	}
	print_a ($sentences);
	$s = permute($sentences);
	echo $s;
	
	function permute($items, $perms = array( )) {
		if (empty($items)) print join(' ', $perms) . "\n"; 
		else { 
			for ($i = count($items) - 1; $i >= 0; --$i) { 
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				permute($newitems, $newperms); 
			} 
		} 
	}
?>