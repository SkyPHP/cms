	<div style="font-size:18px; font-weight:bold;margin-bottom: 10px;">Auto Permutation List</div>
	<input type="button" class="save-auto-sentences" value="Save" style="margin-top:10px; margin-bottom:10px;" />
<?
	$no_sentences = $_POST['no_sentences'];
	$sentences=array();
	for ($x = 0; $x < $no_sentences; $x++) {
		$sentences[$x] = $_POST['sentence'.$x.'_id'];
	}
		
	if ($_POST['use_first']) { 
		$first = $sentences[0];
		$sentences = array_slice($sentences,1);
	}
		
	$limit = 50;
	permutate($sentences,$limit);
	
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
		echo '<div style="float:left; margin-right:10px;"><input type="checkbox" class="perm-box" s_order="';
		$x = 0;
		foreach ($sentences as $sentence_id) {
			$x++;
			echo $sentence_id;
			if ($x!=count($sentences)) echo ",";
		}
		echo  '"/></div>';
		echo '<div style="float:left;">';
		foreach ($sentences as $sentence_id) {			
			$rs = aql::select("dup_sentence_data { sentence where id = ".$sentence_id." } ");
			echo $rs[0]['sentence'].'<br>';
		}
		echo "</div></div>";
	}
	
?>