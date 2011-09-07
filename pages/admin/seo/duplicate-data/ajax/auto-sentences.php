	<div style="font-size:18px; font-weight:bold;margin-bottom: 10px;">Auto Permutation List</div>
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
	
	$limit = 50;
	permutate($sentences,$limit);
?>
	<input type="button" id="save-sentences" value="Save" style="margin-top:10px; margin-bottom:10px;" />
<?
	
	function permutate($items, $limit, $perms = array( )) {
		$count = 0;
		$num_items = count($items);
		$limit = $limit + $num_items + $num_items - 3;
		$permutate = function($items, $perms) use(&$limit, &$count, &$permutate) {
			// print_pre($count);
			if (empty($items)) {
				configure_perm($perms);
			} else {
				$count++;
				for ($i = count($items) - 1; $i >= 0; --$i) {
					$newitems = $items;
					$newperms = $perms;
					list($foo) = array_splice($newitems, $i, 1);
					array_unshift($newperms, $foo);
					if ($limit <= $count) {
						configure_perm(array_merge($newitems,$newperms));
						break;
					}
					$permutate($newitems, $newperms);
				}
			}
		};
		$permutate($items, $perms);
	}

	function configure_perm($sentences=array( )) {
		echo '<div class="has-floats" style="margin-bottom:15px;">';
		echo '<div style="float:left; margin-right:10px;"><input type="checkbox" ';
		$x = 0;
		foreach ($sentences as $sentence) {
			$x++;
			$rs = sql("SELECT id FROM dup_sentence_data where lower(sentence) = '".strtolower(addslashes($sentence))."'");
			if ($rs) echo 's'.$x.'="'.$rs->Fields('id').'" ';
		}
		echo 'version="'.$x.' class="perm_box" /></div>';
		echo '<div style="float:left;">';
		foreach ($sentences as $sentence) {			
			echo $sentence.'<br>';
		}
		echo "</div></div>";
	}
	
?>