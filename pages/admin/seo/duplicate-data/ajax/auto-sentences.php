<?
	$no_sentences = $_POST['no_sentences'];
	$sentences=array();
	for ($x = 0; $x < $no_sentences; $x++) {
		$sentences[$x] = $_POST['sentence'.$x];
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
	
	permute($sentences);
	print_a($perm0);
	
	print_a($perm1);
		
	function permute($items, $perms = array( )) {
		if (empty($items)) {
			print_a ($perms); 
		}
		else { 
			for ($i = count($items) - 1; $i >= 0; --$i) { 
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				$newperms = $newperms;
				permute($newitems, $newperms); 
			} 
		} 
	}
?>