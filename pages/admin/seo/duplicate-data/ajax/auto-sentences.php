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
	
	permutate($sentences, 10);
		
	function permutate($items, $limit, $perms = array( )) {
		if (empty($items)) {
			configure_perms ($perms, $limit); 
		}
		else { 
			for ($i = count($items) - 1; $i >= 0; --$i) { 
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				$newperms = $newperms;
				permutate($newitems, $limit, $newperms); 
			} 
		} 
	}
	
	function configure_perms($perms=array( ),$limit) {
		$x = 0;
		foreach ($perms as $key => $arr) {
			$x++;
			echo '<input type="checkbox" vesion="'.$x.' class="perm_box" /> Version ( '.$x." ) ";
				print join(' ', $arr) . "<br><br>"; 
			
		}
	}
?>