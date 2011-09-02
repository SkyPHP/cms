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
		
	function permutate($items, $limit, $x = 0, $perms = array( )) {
		if (empty($items)) {
			$x++;
			configure_perm ($perms, $limit, $x);
		}
		else { 
			for ($i = count($items) - 1; $i >= 0; --$i) { 
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				$newperms = $newperms;
				permutate($newitems, $limit, $x, $newperms); 
			} 
		} 
	}
	
	function configure_perm($perms=array( ), $limit, $x) {
		echo '<input type="checkbox" vesion="'.$x.' class="perm_box" /> Version ( '.$x." ) ";
		foreach ($perms as $perm) {			
			echo $perm.' ';
		}
		echo "<br><br><br>";
	}
?>